<template>
    <CrudForm
        action="/report"
        :title="title"
        :data="report"
        @close="emit('close')"
    >
        <template #default="{data,errors}">
            <DatePicker
                v-model="data.date"
                name="date"
                disable-time-select
                default-is-today
            />

            <VSelect v-model="data.user"
                     name="user_id"
                     class="mt-5"
                     :errors="errors"
                     :options="users"
            />
        </template>
    </CrudForm>
</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
import {computed} from 'vue';
import CrudForm from '@/Elements/Crud/From';
import {useI18n} from 'vue-i18n';
import VSelect from '@/Elements/VSelect';
import {useStore} from 'vuex';
import DatePicker from '@/Elements/DatePicker';
export default {
    name: 'ReportForm',
    components: {DatePicker, VSelect, CrudForm, VButton, VInput, VForm},
    emits: ['close'],
    props: {
        report: Object,
    },
    setup(props, {emit}){
        const t = useI18n().t;
        const store = useStore();

        return {
            emit,
            users: computed(() => store.getters['user/list']),
            title: computed(() => {
                return props.user ? t('report.title.create') : t('report.title.update');
            }),
        };
    },
}
</script>
