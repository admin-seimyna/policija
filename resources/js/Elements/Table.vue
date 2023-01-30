<template>
    <div class="table"
         :class="{
            'table--basic': basic,
         }"
    >
        <div class="flex">
            <div v-if="loading"
                 class="flex items-center ml-5"
            >
                <VSpinner class="w-6 h-6" />
                <span class="ml-3 text-primary-500 text-xs font-semibold">
                    {{ $t('common.message.loading_data') }}...
                </span>
            </div>
`
            <Pagination v-if="data.length"
                        v-model="page"
                        :total="total"
                        class="ml-auto"
                        @change="load"
            />
        </div>


        <div class="table__container"
             :class="{
                'opacity-50': loading
             }"
        >
            <div class="table__head">
                <div class="table__row">
                    <div v-for="col in columns"
                         :key="col.name"
                         :class="col.class"
                         class="table__col"
                    >
                        <slot :name="`head-${col.name}`">
                            {{ col.titleDisabled ? '' : (col.title || $t(`field.title.${col.name}`)) }}
                        </slot>
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
                         :class="col.class"
                         class="table__col"
                    >
                        <slot :name="col.name" v-bind="{data: row[col.name], index}">
                            {{ row[col.name] }}
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
import {computed, ref} from 'vue';
import axios from 'axios';
import VSpinner from '@/Elements/Spinner';
import {useStore} from 'vuex';
export default {
    name: 'VTable',
    components: {
        VSpinner,
        Pagination
    },
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
    },
    setup(props) {
        const store = useStore();
        const page = ref(1);
        const loading = ref(false);
        const getter = computed(() => store.getters[props.pagination]);

        function load() {
            loading.value = true;
            axios.get(`${props.url}?page=${page.value}`).then(() => {
                loading.value = false;
                //todo: do something
            });
        }

        load();

        return {
            page,
            loading,
            data: computed(() => getter.value.data || []),
            total: computed(() => {
                const total = Math.ceil(getter.value.total / getter.value.per_page);
                return total || 1;
            }),
            load,
        }
    }
}
</script>
