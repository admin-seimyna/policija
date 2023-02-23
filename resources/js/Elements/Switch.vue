<template>
    <div class="flex items-center">
        <input :value="inputValue ? trueValue : falseValue"
               type="hidden"
               :name="name"
        />

        <div class="switch"
             :class="{
                'switch--on': inputValue,
                'switch--danger': danger
             }"
             @click="toggle"
        >
            <span class="switch__circle"></span>
        </div>

        <label v-if="!disableTitle"
               :for="name"
               class="ml-2"
               :class="{
                    'font-semibold': inputValue
               }"
        >{{ label }}</label>
    </div>
</template>
<script>
import { useField, fieldProps } from '@/Elements/Field';
import {ref, watch} from 'vue';

export default {
    name: 'Switch',
    props: {
        ...fieldProps,
        modelValue: [Number, Boolean],
        value: [Number, Boolean],
        danger: Boolean,
        disabled: Boolean,
        trueValue: {
            type: [Number, Boolean],
            default: 1,
        },
        falseValue: {
            type: [Number, Boolean],
            default: 0,
        }
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const inputValue = ref(props.value ? !!props.value : !!props.modelValue);

        watch(
            () => props.modelValue,
            (value) => {
                inputValue.value = !!value;
            }
        )

        watch(
            () => inputValue.value,
            (value) => {
                emit('update:modelValue', value ? props.trueValue : props.falseValue);
            }
        )

        return {
            inputValue,
            ...useField(props),
            toggle() {
                if (props.disabled) return;
                inputValue.value = !inputValue.value;
            }
        }
    }
}
</script>
