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

            <div class="flex w-1/2 mt-5">
                <Timepicker v-model="data.time_from"
                            name="time_from"
                            class="w-1/2"
                            :errors="errors"
                />
                <Timepicker v-model="data.time_to"
                            name="time_to"
                            class="w-1/2 ml-5"
                            :errors="errors"
                />
            </div>
        </template>
    </CrudForm>
</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import {computed, reactive, ref} from 'vue';
import CrudForm from '@/Elements/Crud/From';
import {useI18n} from 'vue-i18n';
import moment from 'moment';
import Timepicker from '@/Elements/TimePicker';

export default {
    name: 'ShiftForm',
    components: {Timepicker, CrudForm, VInput, VForm},
    emits: ['close'],
    props: {
        shift: Object,
    },
    setup(props, {emit}){
        const t = useI18n().t;
        const dates = reactive({ values: [moment('2022-12-15 10:51'), moment('2022-12-18 15:51')] });

        return {
            dates,
            emit,
            title: computed(() => {
                return props.shift ? t('shift.title.create') : t('shift.title.update');
            }),
        };
    },
}
</script>
