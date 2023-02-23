<template>
    <div class="flex items-center flex-wrap">
        <span class="mr-2">
            {{ $t('filter.title.filters')}}:
        </span>
        <Badge v-for="filter in selected.values"
              :key="filter.name"
               shadow
               removable
               class="mr-2"
               @remove="removeFilter(filter)"
        >
            <slot name="select"
                  v-bind="{filter}"
            >
                <span class="mr-2">
                    {{ filter.title || $t(`filter.title.${filter.name}`)}}:
                </span>
                <span>
                    {{ filter.output(filter) }}
                </span>
            </slot>
        </Badge>
        <Popper
            @close:popper="onClose"
        >
            <Badge basic
                   class="cursor-pointer"
            >
                + {{ $t('filter.title.add') }}
            </Badge>
            <template #content="{ close }">
                <div class="w-full bg-white rounded-md shadow-lg border"
                     :class="{
                        'w-80': currentFilter && currentFilter.type === 'select',
                     }"
                >
                    <ul v-if="!currentFilter"
                        class="flex flex-col min-w-56"
                    >
                        <li v-for="filter in filters"
                            :key="filter.name"
                            class="flex items-center w-full p-2 cursor-pointer hover:bg-gray-100"
                            @click="selectFilter(filter)"
                        >
<!--                            <div class="flex-center w-5 h-5">-->
<!--                                <i class="icon-calendar mt-1" />-->
<!--                            </div>-->
                            <span class="font-semibold ml-2">
                                {{ filter.title || $t(`filter.title.${filter.name}`)}}
                            </span>
                        </li>
                    </ul>

                    <div v-else
                         class="w-full flex flex-col"
                    >
                        <component :is="filterComponent"
                                   v-model="currentFilter.value"
                                   :name="currentFilter.name"
                                   :payload="currentFilter.options"
                                   :name-key="currentFilter.nameKey"
                                   :value-key="currentFilter.valueKey"
                                   :range-select="currentFilter.rangeSelect"
                                   disable-title
                        />

                        <div class="flex items-center justify-center py-3">
                            <VButton
                                basic
                                @click="close"
                            >
                                {{ $t('common.button.cancel') }}
                            </VButton>
                            <VButton
                                primary
                                @click="apply(close)"
                            >
                                {{ $t('filter.button.apply') }}
                            </VButton>
                        </div>
                    </div>
                </div>
            </template>
        </Popper>
    </div>
</template>
<script>
import Popper from 'vue3-popper';
import {computed, reactive, ref} from 'vue';
import Calendar from '@/Elements/Calendar';
import VButton from '@/Elements/Button';
import Badge from '@/Elements/Badge';
import VSelect from '@/Elements/VSelect';
import moment from 'moment';

export default {
    name: 'Filters',
    components: {
        Badge,
        VButton,
        Popper,
    },
    props: {
        modelValue: Array,
        filters: Array,
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const currentFilter = ref(null);
        const selected = reactive({ values: [] });
        const parsedFilters = computed(() => {
            const data = {};
            selected.values.forEach((filter) => {
                const value = filter.parse(filter);

                if (!data[filter.name]) {
                    data[filter.name] = filter.type === 'select' ? [] : value;
                }

                if (filter.type === 'select') {
                    data[filter.name].push(value);
                }
            });
            return data;
        });

        return {
            selected,
            currentFilter,
            filters: computed(() => {
                return [...props.filters].map((filter) => {
                    return Object.assign({
                        nameKey: 'name',
                        valueKey: 'id',
                    }, filter)
                })
            }),
            filterComponent: computed(() => {
                switch (currentFilter.value.type) {
                    case 'date': return Calendar;
                    case 'select': return VSelect;
                }
            }),
            selectFilter(filter) {
                currentFilter.value = {...filter};
                currentFilter.value.value = null;
                currentFilter.value.output = (filter) => filter.value;
                currentFilter.value.validate = (filter) => Array.isArray(filter.value) ? filter.value.length : !!filter.value;
                currentFilter.value.parse = (filter) => Array.isArray(filter.value) ? filter.value.join(',') : filter.value;

                switch (filter.type) {
                    case 'date':
                        if (currentFilter.value.rangeSelect) {
                            currentFilter.value.value = reactive([]);
                            currentFilter.value.validate = (filter) => !!filter.value.length;
                        }
                        currentFilter.value.output = (filter) => {
                            if (filter.rangeSelect) {
                                return filter.value.map(date => moment(date).format('YYYY.MM.DD')).join(' - ');
                            }
                            return moment(filter.value).format('YYYY.MM.DD');
                        };
                        currentFilter.value.parse = (filter) => {
                            if (filter.rangeSelect) {
                                const dates = filter.value.map(date => moment(date).format('YYYY-MM-DD'));
                                return { from: dates[0], to: dates[1] || null };
                            }
                            return { from: moment(filter.value).format('YYYY-MM-DD')};
                        };
                        break;
                    case 'select':
                        let used = [...selected.values].filter(f => f.name === filter.name).map(f => f.value);
                        currentFilter.value.value = reactive([]);
                        currentFilter.value.options = filter.payload.value.filter(o => used.indexOf(o[filter.valueKey]) === -1);
                        currentFilter.value.output = (filter) => filter.payload.find(option => option[filter.valueKey] === filter.value)[filter.nameKey];
                        break;
                }
            },
            apply(close) {
                if (!currentFilter.value.validate(currentFilter.value)) {
                    close();
                    return;
                }
                selected.values.push({ ...currentFilter.value });
                emit('update:modelValue', parsedFilters.value);
                close();
            },
            onClose() {
                setTimeout(() => {
                    currentFilter.value = null;
                }, 200);
            },
            removeFilter(filter) {
                const index = selected.values.findIndex(f => f.type === filter.type && f.value === filter.value);
                selected.values.splice(index, 1);
                emit('update:modelValue', parsedFilters.value);
            }
        };
    }
}
</script>
