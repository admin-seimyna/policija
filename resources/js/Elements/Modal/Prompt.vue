<template>
    <div class="flex flex-col">
        <div class="flex justify-center my-5">
            <span class="text-xl font-bold">
                {{ title }}
            </span>
        </div>
        <div class="flex flex-col items-center">
            {{ message }}
        </div>
        <div class="flex-center mt-5 py-5">
            <VButton basic
                     class="mr-3"
                     @click="cancel"
            >
                {{ $t('common.button.cancel') }}
            </VButton>
            <VButton
                :primary="action !== 'delete'"
                :danger="action === 'delete'"
                shadow
                @click="onTrue"
            >
                {{ $t('common.button.yes') }}
            </VButton>
        </div>
    </div>
</template>
<script>
import VButton from '@/Elements/Button';
export default {
    name: 'Prompt',
    components: {VButton},
    emits: ['close'],
    props: {
        title: String,
        message: String,
        action: String,
    },
    setup(props, { emit }) {
        return {
            cancel() {
                emit('close');
            },

            onTrue() {
                emit('true');
            }
        }
    }
}
</script>
