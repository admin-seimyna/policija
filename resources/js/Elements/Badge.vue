<template>
    <div class="flex items-center rounded p-2 h-10"
         :class="{
            'bg-gray-100 hover:bg-gray-200': basic,
            'shadow': shadow,
            'bg-primary-500 hover:bg-primary-400 text-white': primary,
            'bg-gray-500 hover:bg-gray-400 text-white': dark,
         }"
    >
        <span class="text-xs font-semibold">
            <slot />
        </span>

        <i v-if="removable"
           class="icon-cross-mark mt-1 ml-2 cursor-pointer text-xs"
           @click="emit('remove')"
        />
    </div>
</template>
<script>
export default {
    name: 'Badge',
    props: {
        removable: Boolean,
        basic: Boolean,
        primary: Boolean,
        dark: Boolean,
        shadow: Boolean
    },
    emits: ['remove'],
    setup(props, { emit }) {
        return {
            emit,
        };
    }
}
</script>
