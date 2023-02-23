<template>
    <Popper offset-distance="0">
        <div class="flex-center w-10 h-10 cursor-pointer rounded hover:bg-gray-100">
            <i class="icon-dot-y" />
        </div>
        <template #content="{close}">
            <div class="bg-white rounded-md shadow-lg min-w-56 border">
                <slot v-bind="{ close }" />
            </div>
        </template>
    </Popper>
</template>
<script>
import Popper from 'vue3-popper';

export default {
    name: 'ContextMenu',
    components: { Popper }
}
</script>
