<template>
    <VForm :action="action"
           :method="method"
           class="p-8"
           :data="data"
           @success="onSuccess"
    >
        <template #default="{data,progress,errors}">
            <div class="flex items-center mb-8">
                <span class="h2">
                    {{ title }}
                </span>

                <VButton type="span"
                         rounded
                         basic
                         soft
                         class="w-10 h-10 ml-auto"
                         @click="emit('close')"
                >
                    <i class="icon-cross-mark mt-1" />
                </VButton>
            </div>

            <slot v-bind="{data, errors}" />

            <div class="flex items-center mt-8">
                <VButton type="span"
                         basic
                         soft
                         class="mr-2 ml-auto"
                         @click="emit('close')"
                >
                    {{ $t('common.button.cancel') }}
                </VButton>

                <VButton primary
                         shadow
                         :progress="progress"
                >
                    {{ $t('common.button.save')}}
                </VButton>
            </div>
        </template>
    </VForm>
</template>
<script>
import VButton from '@/Elements/Button';
import VForm from '@/Elements/Form';
import {computed} from 'vue';
export default {
    name: 'CrudForm',
    components: {VForm, VButton},
    emits: ['close'],
    props: {
        data: Object,
        action: String,
        keepOriginalAction: Boolean,
        title: String,
    },
    setup(props, { emit }) {
        return {
            emit,
            action: computed(() => {
                if (!props.data.id || props.keepOriginalAction) return props.action;
                return `${props.action}/${props.data.id}`;
            }),

            method: computed(() => {
                return props.data.id ? 'put' : 'post';
            }),

            onSuccess() {
                emit('success');
                emit('close');
            }
        };
    },
}
</script>
