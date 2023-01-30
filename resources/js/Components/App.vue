<template>
    <div class="flex w-full h-full fixed top-0 left-0 bg-gray-100">
        <Sidebar />
        <div class="flex flex-col w-full h-full">
            <Navigation />
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
export default {
    name: 'App',
    components: {
        Navigation,
        Sidebar,
        Modal
    },
}
</script>
