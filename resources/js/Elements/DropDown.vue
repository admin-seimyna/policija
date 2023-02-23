<template>
    <div class="dropdown">
        <Popper placement="bottom-start" offset-distance="5" arrow>
            <div class="flex cursor-pointer">
                <slot />
            </div>
            <template #content="{close}">
                <div class="bg-white rounded-md shadow-lg min-w-56 border">
                    <slot name="content" />
                </div>
            </template>
        </Popper>
    </div>
</template>
<script>
import Popper from 'vue3-popper';

export default {
    name: 'DropDown',
    components: { Popper }
}
</script>
