<template>
    <RouterLink :to="{ name: link.name }"
                class="h-10 rounded-md flex items-center pl-5"
                :class="[{
                    'hover:bg-primary-50 cursor-pointer': !link.active,
                    'bg-primary-100 text-primary-600': link.active
                }, link.class]"
    >
        <i v-if="link.icon"
           :class="[link.icon, {
               'text-gray-400': !link.active,
               'text-primary-500': link.active,
           }]"
           class="mr-2"
        />
        <span :class="{
            'font-semibold': link.active
        }">
            {{ link.title }}
        </span>
    </RouterLink>
</template>
<script>
export default {
    name: 'SidebarLink',
    props: {
        link: Object,
    }
}
</script>
