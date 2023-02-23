<template>
    <div class="checkbox"
         :class="{'checkbox--checked': inputValue}"
    >
        <input type="hidden"
               :name="name"
               :value="inputValue ? trueValue : falseValue"
        />

        <div class="checkbox__value"
             @click="toggle"
        >
            <i class="icon-check" />
        </div>

        <label :for="name"
               class="text-xs font-semibold ml-3 text-text-light"
        >
            {{ label }}
        </label>
    </div>
</template>
<script>
import { useField, fieldProps } from '@/Elements/Field';
import {ref, watch} from 'vue';

export default {
    name: 'VCheckbox',
    props: {
        ...fieldProps,
        modelValue: [Number, Boolean],
        value: [Number, Boolean],
        danger: Boolean,
        disabled: Boolean,
        trueValue: {
            type: [Number, Boolean, String],
            default: 1,
        },
        falseValue: {
            type: [Number, Boolean, String],
            default: 0,
        }
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const inputValue = ref(props.value ? props.value === props.trueValue : props.modelValue === props.trueValue);

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
