<template>
    <div class="input"
         :class="{
            'input--focus': focused,
            'input--error': hasError,
         }"
    >
        <div class="input__wrapper">
            <label v-if="!disableTitle"
                   :for="name"
                   class="text-xs font-semibold mt-2 ml-3 text-text-light"
            >
                {{ label }}
            </label>

            <div class="flex items-center">
                <div class="flex-center w-14 h-input cursor-pointer hover:bg-gray-100"
                     @click="decrease"
                >
                    <i class="icon-angle-left"/>
                </div>
                <input v-model="inputValue"
                       :name="name"
                       type="number"
                       class="text-center"
                       :placeholder="placeholder"
                       @focus="onFocus"
                       @blur="onBlur"
                />
                <div class="flex-center w-14 h-input cursor-pointer hover:bg-gray-100"
                     @click="increase"
                >
                    <i class="icon-angle-right"/>
                </div>
            </div>
        </div>

        <InputError
            v-if="hasError"
            :message="errorMessage"
            class="mt-1"
        />
    </div>
</template>
<script>
import {fieldProps, useField} from '@/Elements/Field';
import InputError from '@/Elements/InputError';
import {ref, watch} from 'vue';

export default {
    name: 'Counter',
    components: {InputError},
    props: {
        ...fieldProps,
        modelValue: [String, Number],
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const focused = ref(false);
        const inputValue = ref(props.modelValue || 0);

        watch(
            () => inputValue.value,
            (value) => {
                emit('update:modelValue', value);
            }
        );

        watch(
            () => props.modelValue,
            (value) => inputValue.value = value,
        );
        return {
            ...useField(props),
            focused,
            inputValue,
            increase() {
                inputValue.value += 1;
            },
            decrease() {
                inputValue.value -= 1;
            },
            onFocus() {
                focused.value = true;
            },
            onBlur() {
                focused.value = false;
            }
        }
    }
}
</script>
