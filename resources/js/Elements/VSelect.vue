<template>
    <div class="select"
         ref="selectRef"
         :class="{
            'select--focus': isOpen,
            'select--error': hasError,
         }"
    >
        <div class="select__wrapper">
            <label v-if="!disableTitle"
                   :for="name"
                   class="text-xs font-semibold mt-2 ml-3 text-text-light"
            >
                {{ label }}
            </label>

            <div class="select__container"
                 @mouseup="open"
            >

                <template v-if="!isOpen">
                    <span v-if="!selectedOptions.length"
                          class="select__placeholder"
                    >
                        {{ placeholder }}
                    </span>
                    <template v-else>
                        <div v-if="!multiple"
                             class="flex items-center w-full"
                        >
                            <span class="font-semibold">
                                {{ selectedOptions[0][nameKey]}}
                            </span>
                        </div>
                    </template>

                    <i class="icon-angle-down ml-auto" />
                </template>

                <input v-if="isOpen"
                       ref="inputRef"
                       v-model="search"
                       type="text"
                       :placeholder="searchPlaceholder"
                />
            </div>

            <div v-if="isOpen"
                 class="select__content"
            >
                <div class="flex flex-col w-full">
                    <ul v-for="(group, groupIndex) in availableOptions"
                        :key="`group-${groupIndex}`"
                        class="flex flex-col w-full border"
                    >
                        <li v-if="grouped"
                            class="bg-gray-200"
                        >
                            <span class="font-semibold text-xs p-2">
                                {{ group[nameKey] }}
                            </span>
                        </li>

                        <li v-for="option in group.options"
                            :key="option"
                            class="flex w-full border-b last:border-b-0"
                            @click="select(option)"
                        >
                            <div class="w-full flex items-center py-3 px-2 cursor-pointer hover:bg-gray-100">
                                <span :class="{
                                    'font-semibold text-primary-500': option._isSelected
                                }">
                                    {{ option[nameKey]}}
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <InputError
            v-if="hasError"
            :message="errorMessage"
            class="mt-1"
        />

        <input v-for="option in selectedOptions"
               type="hidden"
               :name="name"
               :value="option[valueKey]"
        />
    </div>
</template>
<script>
import { fieldProps, useField } from '@/Elements/Field';
import {computed, nextTick, onBeforeUnmount, reactive, ref, watch} from 'vue';
import {cloneDeep} from 'lodash/lang';
import InputError from '@/Elements/InputError';
import {useI18n} from 'vue-i18n';

export default {
    name: 'VSelect',
    components: {InputError},
    props: {
        ...fieldProps,
        modelValue: [String, Number, Array],
        payload: Array,
        grouped: Boolean,
        multiple: Boolean,
        filter: {
            type: Function,
            default: (option, search, key) => {
                return option[key].toLowerCase().match(search.toLowerCase())
            }
        },
    },
    emits: ['change', 'modelValue:update'],
    setup(props, { emit }) {
        const t = useI18n().t;
        const isOpen = ref(false);
        const search = ref(null);
        const inputRef = ref(null);
        const selectRef = ref(null);
        const selected = reactive([]);
        const selectedOptions = reactive([]);

        const mappedOptions = computed(() => {
            return (!props.grouped
                ? [{name: 'default', options: [...props.payload]}]
                : [...props.payload]
            ).map(group => {
                return Object.assign({ ...group }, {
                    _id: `group-${props.valueKey}`,
                    options: [...group.options].map((option, optionIndex) => {
                        const id = `${group[props.valueKey]}-${option[props.valueKey]}`;
                        return Object.assign({...option}, {
                            _id: id,
                            _isSelected: selected.indexOf(id) !== -1,
                        });
                    }),
                });
            });
        });

        function setValues(values) {
            (Array.isArray(values) ? values : [values]).forEach((value) => {
                [...mappedOptions.value].forEach((group) => {
                    group.options
                        .filter(o => o[props.valueKey] === parseInt(value))
                        .forEach((option) => {
                            selected.push(option._id);
                            selectedOptions.push(option);
                        });
                });
            });
            emit('change', selectedOptions);
        }

        function open() {
            isOpen.value = true;
            nextTick(() => {
                inputRef.value.focus();
                document.addEventListener('click', clickOnOutside, false);
            });
        }

        function close() {
            isOpen.value = false;
            document.removeEventListener('click', clickOnOutside);
        }

        function clickOnOutside(e) {
            if (selectRef.value && !selectRef.value.contains(e.target)) {
                close();
            }
        }

        if (props.modelValue) {
            setValues(props.modelValue);
        }

        watch(() => props.modelValue, setValues);

        onBeforeUnmount(() => {
            document.removeEventListener('click', clickOnOutside);
        });

        return {
            ...useField(props),
            placeholder: computed(() => {
                return `${props.placeholder || t('field.placeholder.select')}`;
            }),
            searchPlaceholder: computed(() => {
                return `${props.searchPlaceholder || t('field.placeholder.search')}`;
            }),
            availableOptions: computed(() => {
                if (!search.value) {
                    return mappedOptions.value;
                }

                return cloneDeep(mappedOptions.value).map((group) => {
                    group.options = [...group.options].filter((option) => {
                        return props.filter(option, search.value, props.nameKey);
                    });
                    return group;
                }).filter(group => group.options.length);
            }),
            isOpen,
            search,
            inputRef,
            selectRef,
            selected,
            selectedOptions,
            mappedOptions,
            select(option) {
                const index = selected.indexOf(option._id);
                if (index !== -1) {
                    selected.splice(index,1);
                    selectedOptions.splice(index,1);
                } else {
                    if (!props.multiple) {
                        selected.splice(0,1);
                        selectedOptions.splice(0,1);
                    }

                    selected.push(option._id);
                    selectedOptions.push(option);
                }

                const values = selectedOptions.map(option => option[props.valueKey]);
                emit('update:modelValue', props.multiple ? values : values[0]);
                emit('change', selectedOptions);

                if (!props.multiple) {
                    close();
                }
            },
            open,
            close,
            toggle() {
                isOpen.value ? close() : open();
            },
        }
    }
}
</script>
