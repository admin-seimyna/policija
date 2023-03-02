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
            <transition v-if="loading"
                        name="fade"
            >
                <div class="absolute top-0 left-0 w-full h-full bg-gray-100 flex-center z-50">
                    <VSpinner class="w-10 h-10" />
                </div>
            </transition>
            <slot />
        </div>
    </div>
</template>
<script>
import {useRoute} from 'vue-router';
import {computed} from 'vue';
import {useI18n} from 'vue-i18n';
import {useStore} from 'vuex';
import VSpinner from '@/Elements/Spinner';

export default {
    name: 'Page',
    components: {VSpinner},
    setup(props) {
        const route = useRoute();
        const store = useStore();
        const t = useI18n().t;
        return {
            loading: computed(() => store.getters['app/loading']),
            title: computed(() => {
                return t(`page.title.${route.name}`);
            })
        }
    }
}
</script>
