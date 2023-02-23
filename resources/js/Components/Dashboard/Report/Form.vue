<template>
    <CrudForm
        action="/report"
        :title="title"
        :data="report"
        @success="onSuccess"
        @close="emit('close')"
    >
        <template #default="{data,errors}">

            <div class="w-full flex mt-5">
                <VSelect v-if="hasPermissionToCreatForUser"
                         v-model="data.user_id"
                         name="user_id"
                         class="mr-2"
                         :errors="errors"
                         :payload="users"
                />

                <VSelect v-model="data.shift_id"
                         name="shift_id"
                         :class="{
                            'ml-2': hasPermissionToCreatForUser
                         }"
                         name-key="title"
                         :errors="errors"
                         :payload="shifts"
                />
            </div>

            <div class="flex flex-col border rounded-md mt-5 p-5">
                <div v-for="column in columns"
                     :key="column"
                     class="flex items-center mb-3"
                >
                    <span class="font-semibold">
                        {{ $t(`field.title.${column}`)}}
                    </span>
                    <div class="ml-auto w-32">
                        <Counter v-model="data[column]"
                                 :name="column"
                                 disable-title
                                 placeholder="0"
                        />
                    </div>
                </div>
            </div>

            <TipTapEditor v-model="data.comment"
                          class="mt-5"
                          name="comment"
            />
        </template>
    </CrudForm>
</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
import {computed, inject} from 'vue';
import CrudForm from '@/Elements/Crud/From';
import {useI18n} from 'vue-i18n';
import VSelect from '@/Elements/VSelect';
import {useStore} from 'vuex';
import Counter from '@/Elements/Counter';
import TipTapEditor from '@/Elements/TipTapEditor';

export default {
    name: 'ReportForm',
    components: {TipTapEditor, Counter, VSelect, CrudForm, VButton, VInput, VForm},
    emits: ['close'],
    props: {
        report: Object,
        onSuccess: Function,
    },
    setup(props, {emit}){
        const app = inject('app');
        const t = useI18n().t;
        const store = useStore();

        return {
            emit,
            columns: computed(() => {
                return [
                    'events_count',
                    'processed_events_count',
                    'an_count',
                    'traffic_events_count',
                    'unprocessed_events_count',
                ];
            }),
            users: computed(() => store.getters['user/list']),
            shifts: computed(() => store.getters['shift/list']),
            title: computed(() => {
                return props.user ? t('report.title.create') : t('report.title.update');
            }),
            hasPermissionToCreatForUser: computed(() => {
                return app.permission.has(`report.${props.report.id ? 'edit' : 'create'}`);
            })
        };
    },
}
</script>
