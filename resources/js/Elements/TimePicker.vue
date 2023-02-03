<template>
    <div class="w-full">
        <Popper class="popper w-full"
                @close:popper="onClose"
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
                        <span class="font-semibold">
                            {{ output }}
                        </span>

                        <input type="hidden"
                               :name="name"
                               :value="valueOutput"
                        >
                    </div>
                </div>
            </div>

            <template #content="{close}">
                <div class="flex flex-col bg-white rounded-md shadow-lg p-3">
                    <TimeSelect
                        v-model="time"
                    />

                    <div class="flex justify-center mt-3">
                        <VButton type="span"
                                 basic
                                 soft
                                 @click="close"
                        >
                            {{ $t('common.button.cancel')}}
                        </VButton>

                        <VButton type="span"
                                 primary
                                 class="ml-2"
                                 @click="apply(close)"
                        >
                            {{ $t('common.button.apply')}}
                        </VButton>
                    </div>
                </div>
            </template>
        </Popper>

        <InputError
            v-if="hasError"
            :message="errorMessage"
            class="mt-1"
        />
    </div>
</template>
<script>
import {computed, ref} from 'vue';
import { fieldProps, useField } from '@/Elements/Field';
import Popper from 'vue3-popper';
import moment from 'moment';
import TimeSelect from '@/Elements/TimeSelect';
import VButton from '@/Elements/Button';
import InputError from '@/Elements/InputError';

export default {
    name: 'TimePicker',
    components: {InputError, VButton, TimeSelect, Popper},
    emits: ['update:modelValue'],
    props: {
        ...fieldProps,
        modelValue: [String, Object],
    },
    setup(props, { emit }) {
        const focused = ref(false);
        const time = ref(moment());
        const inputValue = ref(null);

        if (props.modelValue) {
            time.value = moment(`${moment().format('YYYY-MM-DD')} ${props.modelValue}`);
            inputValue.value = time.value;
        }

        return {
            ...useField(props),
            inputValue,
            time,
            focused,
            output: computed(() => {
                return inputValue.value ? inputValue.value.format('HH:mm') : null;
            }),
            valueOutput: computed(() => inputValue.value ? inputValue.value.format('HH:mm') : null),
            apply(close) {
                inputValue.value = time.value;
                emit('update:modelValue', inputValue.value);
                close();
            },
            onClose() {
                time.value = inputValue.value;
            }
        };
    }
}
</script>
