<template>
    <CrudForm
        action="/settings/shift"
        :title="title"
        :data="shift"
        @close="emit('close')"
    >
        <template #default="{data,errors}">
            <VInput v-model="data.title"
                    name="title"
                    :errors="errors"
            />

            <Datepicker />
        </template>
    </CrudForm>
</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import {computed} from 'vue';
import CrudForm from '@/Elements/Crud/From';
import {useI18n} from 'vue-i18n';
import Datepicker from '@/Elements/Datepicker';

export default {
    name: 'ShiftForm',
    components: {Datepicker, CrudForm, VInput, VForm},
    emits: ['close'],
    props: {
        shift: Object,
    },
    setup(props, {emit}){
        const t = useI18n().t;

        return {
            date: null,
            emit,
            title: computed(() => {
                return props.shift ? t('shift.title.create') : t('shift.title.update');
            }),
        };
    },
}
</script>
