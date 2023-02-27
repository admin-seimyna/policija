<template>
    <div class="w-full bg-white rounded-md shadow-lg py-3">
        <Filters v-model="selectedFilters.values"
                 class="px-5"
                 :filters="filters"
        />

        <VTable pagination="report/pagination"
                export-url="/report/export"
                :columns="columns"
                :filters="selectedFilters.values"
                url="/report/pagination"
                basic
                @load="reloadStatistics"
        >
            <template #date="{data, item}">
                <div class="flex-center w-7">
                    <Popper v-if="item.comment"
                            class="tooltip"
                    >
                        <i class="icon-comment text-warning-500 cursor-pointer" />
                        <template #content>
                            <div v-html="item.comment"
                                 class="editor-output text-xs"
                            />
                        </template>
                    </Popper>
                </div>
                <span class="ellipsis">
                    {{ data }}
                </span>
            </template>
            <template #user="{data}">

                <span class="font-semibold ellipsis"
                      :title="data.name"
                >
                    {{ data.name }}
                </span>
            </template>

            <template #shift="{data}">
                <span class="ellipsis"
                      :class="{
                        'line-through': !data,
                        'font-semibold': data,
                      }"
                      :title="data ? data.title : $t('common.message.deleted')"
                >
                    {{ data ? data.title : $t('common.message.deleted') }}
                </span>
            </template>

            <template #total="{data}">
                <span class="font-semibold">
                    {{ data }}
                </span>
            </template>

            <template #actions="{data, index, item}">
                <CrudContextMenu
                    @edit="edit(index)"
                    :delete-url="`/report/${item.id}`"
                    :can-edit="canEdit(item)"
                    :can-delete="canDelete(item)"
                    @delete="reloadStatistics"
                />
            </template>
        </VTable>
    </div>
</template>
<script>
import VTable from '@/Elements/Table';
import {computed, inject, reactive} from 'vue';
import CrudContextMenu from '@/Elements/Crud/ContextMenu';
import Form from '@/Components/Dashboard/Report/Form';
import {useStore} from 'vuex';
import Filters from '@/Elements/Filters';
import Popper from 'vue3-popper';

export default {
    name: 'DashboardReports',
    components: {Filters, CrudContextMenu, VTable, Popper},
    emits: ['reloadStatistic'],
    setup(props, { emit }) {
        const app = inject('app');
        const store = useStore();
        const selectedFilters = reactive({ values: [] });
        const user = computed(() => store.getters['auth/user']);

        return {
            emit,
            selectedFilters,
            filters: computed(() => {
                return [
                    {
                        name: 'date',
                        type: 'date',
                        rangeSelect: true,
                    }, {
                        name: 'user_id',
                        type: 'select',
                        payload: computed(() => store.getters['user/list']),
                    }, {
                        name: 'shift_id',
                        type: 'select',
                        nameKey: 'title',
                        payload: computed(() => store.getters['shift/list']),
                    }
                ];
            }),
            columns: computed(() => {
                return [
                    {
                        name: 'date',
                        sortable: true,
                        overflow: true,
                    }, {
                        name: 'user',
                        sortable: true,
                    }, {
                        name: 'shift',
                        sortable: true,
                    }, {
                        name: 'events_count',
                        sortable: true,
                    }, {
                        name: 'processed_events_count',
                        sortable: true,
                    }, {
                        name: 'an_count',
                        sortable: true,
                    }, {
                        name: 'traffic_events_count',
                        sortable: true,
                    }, {
                        name: 'unprocessed_events_count',
                        sortable: true,
                    }, {
                        name: 'total',
                        sortable: true,
                    }, {
                        name: 'actions',
                        titleDisabled: true,
                        class: 'max-w-16',
                        overflow: true
                    }
                ];
            }),
            canEdit(item) {
                if (app.permission.has('report.edit')) return true;
                return item.user_id === user.value.id;
            },
            canDelete(item) {
                if (app.permission.has('report.delete')) return true;
                return item.user_id === user.value.id;
            },
            edit(index) {
                app.modal({
                    component: Form,
                    props: {
                        report: store.getters['report/pagination'].data[index],
                    }
                })
            },
            reloadStatistics() {
                emit('reloadStatistic', selectedFilters.values);
            },
        }
    }
}
</script>
