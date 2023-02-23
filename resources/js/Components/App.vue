<template>
    <div class="flex w-full h-full fixed top-0 left-0 bg-gray-100">
        <Sidebar v-if="auth && !public" />
        <div class="flex flex-col w-full h-full overflow-y-auto">
            <Navigation v-if="auth && !public" />
            <div class="w-full h-full overflow-y-auto">
                <RouterView />
            </div>
            <Modal />
        </div>
    </div>
</template>
<script>
import Modal from '@/Elements/Modal';
import Sidebar from '@/Components/Layout/Sidebar';
import Navigation from '@/Components/Layout/Navigation';
import {useRoute} from 'vue-router';
import {computed, onMounted, ref} from 'vue';
import {useStore} from 'vuex';
import VSpinner from '@/Elements/Spinner';

export default {
    name: 'App',
    components: {
        VSpinner,
        Navigation,
        Sidebar,
        Modal
    },
    setup(props) {
        const ready = ref(false);
        const route = useRoute();
        const store = useStore();
        const auth = computed(() => store.getters['auth/auth']);

        onMounted(() => {
            ready.value = true;
        })
        return {
            ready,
            auth,
            public: computed(() => !!route.meta.public),
            loading: computed(() => store.getters['app/loading']),
        }
    }
}
</script>
