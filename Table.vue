<template>
    <div class="table"
         :class="{
            'table--basic': basic,
         }"
    >
        <div class="flex items-center pl-5 py-3">
            <div v-if="loading"
                 class="flex items-center"
            >
                <VSpinner class="w-6 h-6" />
                <span class="ml-3 text-primary-500 text-xs font-semibold">
                    {{ $t('common.message.loading_data') }}...
                </span>
            </div>

            <span v-else-if="exportUrl"
                  class="text-xs font-semibold cursor-pointer"
                  @click="exportData"
            >
                <i class="icon-download mr-2" />
                <span class="link">
                    {{ $t('common.button.export') }}
                </span>
            </span>

            <Pagination v-if="data.length"
                        v-model="page"
                        :total="total"
                        class="ml-auto"
                        @change="load"
            />
        </div>


        <div class="table__container my-8"
             :class="{
                'opacity-50': loading
             }"
        >
            <div class="table__head">
                <div class="table__row">
                    <div v-for="col in columns"
                         :key="col.name"
                         :class="[col.class, {
                             'overflow-hidden': !col.overflow,
                             'cursor-pointer': col.sortable
                         }]"
                         class="table__col"
                         :title="col.title || $t(`field.title.${col.name}`)"
                         @click="sort(col)"
                    >
                        <slot :name="`head-${col.name}`">
                            <span class="ellipsis">
                                {{ col.titleDisabled ? '' : (col.title || $t(`field.title.${col.name}`)) }}
                            </span>
                        </slot>

                        <i v-if="col.sortable"
                           class="ml-auto"
                           :class="{
                                'icon-sort text-gray-300': !sortable[col.name],
                                'icon-sort-desc text-gray-500': sortable[col.name] === 'asc',
                                'icon-sort-asc text-gray-500': sortable[col.name] === 'desc',
                           }"
                        />
                    </div>
                </div>
            </div>

            <div class="table__body">
                <div v-if="!loading && !data.length"
                     class="w-full h-48 flex-center"
                >
                    {{ $t('common.message.list_is_empty') }}
                </div>

                <div v-else
                     v-for="(row, index) in data"
                     :key="`body-${index}`"
                     class="table__row"
                >
                    <div v-for="col in columns"
                         :key="col.name"
                         :class="[col.class, {
                             'overflow-hidden': !col.overflow,
                         }]"
                         class="table__col"
                    >
                        <slot :name="col.name"
                              v-bind="{data: row[col.name], index, item: row}"
                        >
                            {{ col.overflow }}
                            <span class="ellipsis"
                                  :title="`${col.title || $t(`field.title.${col.name}`)}: ${row[col.name]}`"
                            >
                                {{ row[col.name] }}
                            </span>
                        </slot>
                    </div>
                </div>
            </div>
        </div>


        <div class="flex-center">
            <Pagination v-if="data.length"
                        v-model="page"
                        :total="total"
                        class="ml-auto"
                        @change="load"
            />
        </div>
    </div>
</template>
<script>
import Pagination from '@/Elements/Pagination';
import {computed, inject, onMounted, reactive, ref, watch} from 'vue';
import axios from 'axios';
import VSpinner from '@/Elements/Spinner';
import {useStore} from 'vuex';
import TableExport  from '@/Elements/TableExport';

export default {
    name: 'VTable',
    components: {
        VSpinner,
        Pagination
    },
    emits: ['load'],
    props: {
        url: {
            type: String,
            required: true
        },
        pagination: {
            type: String,
            required: true
        },
        columns: Array,
        basic: Boolean,
        filters: Array,
        exportUrl: String,
    },
    setup(props, { emit }) {
        const app = inject('app');
        const store = useStore();
        const page = ref(1);
        const loading = ref(false);
        const getter = computed(() => store.getters[props.pagination]);
        const sortable = reactive({});

        watch(() => props.filters, load, {deep: true});

        function makeUrl(url) {
            if (!props.filters) return url;
            url += url.match(/[?]/) ? '&' : '?';
            url += `filters=${JSON.stringify(props.filters)}&sort=${JSON.stringify(sortable)}`;
            return url;
        }

        function load(emitCall = true) {
            const url = makeUrl(`${props.url}?page=${page.value}`);

            if (emitCall) {
                emit('load');
            }
            loading.value = true;
            axios.get(url).then(() => {
                loading.value = false;
                //todo: do something
            });
        }

        onMounted(load);

        return {
            page,
            loading,
            sortable,
            data: computed(() => getter.value.data || []),
            total: computed(() => {
                const total = Math.ceil(getter.value.total / getter.value.per_page);
                return total || 1;
            }),
            load,
            exportData() {
                app.modal({
                    component: TableExport,
                    props: {
                        url: makeUrl(props.exportUrl)
                    }
                });
            },
            sort(col) {
                if (!col.sortable) return;

                if (!sortable[col.name]) {
                    sortable[col.name] = 'asc';
                } else if (sortable[col.name] === 'asc') {
                    sortable[col.name] = 'desc';
                } else {
                    delete sortable[col.name];
                }
                load(false);
            }
        }
    }
}
</script>
