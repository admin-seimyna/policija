<template>
    <div class="w-full px-10 pt-5 pb-16">
        <div class="w-full flex h-16 items-center">
            <slot name="title">
                <span class="font-semibold uppercase text-lg">
                    {{ title }}
                </span>
            </slot>

            <div class="flex ml-auto">
                <slot name="action" />
            </div>
        </div>
        <div class="w-full mt-8">
            <slot />
        </div>
    </div>
</template>
<script>
import {useRoute} from 'vue-router';
import {computed} from 'vue';
import {useI18n} from 'vue-i18n';

export default {
    name: 'Page',
    setup(props) {
        const route = useRoute();
        const t = useI18n().t;
        return {
            title: computed(() => {
                return t(`page.title.${route.name}`);
            })
        }
    }
}
</script>
