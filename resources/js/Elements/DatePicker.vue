<template>
    <div class="w-full">
        <Popper class="popper w-full"
                @close:popper="onClose"
                @open:popper="onOpen"
        >
            <div class="input"
                 :class="{
                    'input--focus': focused,
                    'input--error': hasError,
                }"
            >
                <div class="input__wrapper">
                    <label :for="name"
                           class="text-xs font-semibold mt-2 ml-3 text-text-light"
                    >
                        {{ label }}
                    </label>

                    <div class="input__container w-full cursor-pointer">
                        <span :class="{
                                'font-semibold': valueOutput,
                                'text-gray-500': !valueOutput,
                              }"
                              v-html="output"
                        />
                    </div>

                    <input type="hidden"
                           :name="name"
                           :value="valueOutput"
                    />
                </div>
            </div>


            <template #content="{close}">
                <div class="flex flex-col bg-white border shadow-lg p-3 rounded-md">
                    <div class="flex">
                        <Calendar v-model="range.dates"
                                  v-model:activeDate="activeDate"
                                  :range-select="rangeSelect"
                        />
                        <TimeSelect v-if="!disableTimeSelect"
                                    v-model="range.time[activeDate]"
                                    :date="range.dates[activeDate]"
                                    class="border-l"
                        />
                    </div>
                    <div class="flex items-center justify-end">
                        <VButton type="span"
                                 basic
                                 class="mr-auto"
                                 @click="close"
                        >
                            {{ $t('common.button.cancel')}}
                        </VButton>

                        <VButton type="span"
                                 basic
                                 soft
                                 class="mr-2"
                                 @click="clear"
                        >
                            {{ $t('common.button.clear')}}
                        </VButton>
                        <VButton type="span"
                                 primary
                                 @click="apply(close)"
                        >
                            {{ $t('common.button.apply')}}
                        </VButton>
                    </div>
                </div>
            </template>
        </Popper>
    </div>
</template>
<script>
import Calendar from '@/Elements/Calendar';
import TimeSelect from '@/Elements/TimeSelect';
import moment from 'moment';
import {computed, reactive, ref, watch} from 'vue';
import VButton from '@/Elements/Button';
import Popper from 'vue3-popper';
import { fieldProps, useField } from '@/Elements/Field';

export default {
    name: 'DatePicker',
    components: {
        VButton,
        TimeSelect,
        Calendar,
        Popper
    },
    props: {
        modelValue: [String, Object],
        rangeSelect: Boolean,
        disableTimeSelect: Boolean,
        defaultIsToday: Boolean,
        ...fieldProps,
    },
    emits: ['update:modelValue'],
    setup(props, {emit}) {
        const focused = ref(false);
        const inputValue = reactive({ value: null });
        const range = reactive({ dates: [], time: [], });
        const activeDate = ref(0);
        const format = computed(() => {
            return props.disableTimeSelect ? 'YYYY-MM-DD' : 'YYYY-MM-DD HH:mm'
        });

        if (props.modelValue) {
            if (props.rangeSelect) {
                (Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue]).forEach((date, index) => {
                    range.dates[index] = moment(date);
                    range.time[index] = moment(date);
                });
            } else {
                range.dates = moment(props.modelValue);
                range.time = moment(props.modelValue);
            }
            setValue();
        } else {
            const now = moment();
            range.dates = now;
            range.time = now;

            if (props.defaultIsToday) {
                setValue();
            }
        }

        function setValue() {
            if (props.rangeSelect) {
                inputValue.value = [];
                range.dates.forEach((date, index) => {
                    inputValue.value.push(date.clone().set({
                        hour: range.time[index].get('hour'),
                        minute: range.time[index].get('minutes')
                    }))
                });
            } else {
                if (!range.dates) {
                    inputValue.value = null;
                    return;
                }
                inputValue.value = range.dates.clone().set({
                    hour: range.time.get('hour'),
                    minute: range.time.get('minutes')
                });
            }
        }

        watch(
            () => range.dates,
            (value, oldValue) => {
                if (!value) {
                    range.dates = props.rangeSelect ? [] : null;
                    range.time = props.rangeSelect ? [] : null;
                    return;
                }
                const index = props.rangeSelect ? range.dates.findIndex((date, index) => date.format('YYYY-MM-DD') !== oldValue[index].format('YYYY-MM-DD')) : range.dates;
                range.dates[index] = value[index];
            }
        )

        const field = useField(props);
        return {
            ...field,
            focused,
            range,
            activeDate,
            output: computed(() => {
                if (props.rangeSelect) {

                }
                return inputValue.value ? inputValue.value.format(format.value) : field.placeholder.value;
            }),
            valueOutput: computed(() => {
                if (props.rangeSelect) {

                }
                return inputValue.value ? inputValue.value.format(format.value) : null;
            }),
            apply(close) {
                setValue();
                emit('update:modelValue', inputValue.value);
                close();
            },

            clear(close) {
                if (props.rangeSelect) {
                    range.dates.splice(0, 2);
                } else {
                    range.dates = null;
                }
            },

            onOpen() {
                focused.value = true;
            },

            onClose() {
                range.dates = inputValue.value || moment();
                range.time = inputValue.value || moment();
                focused.value = false;
            }
        }
    }
}
</script>
