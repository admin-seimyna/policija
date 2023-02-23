<template>
    <div class="flex flex-col w-64 shrink-0 h-full shadow-xl bg-white z-20">
        <div class="bg-gray-600 flex-center w-full h-16 text-center">
            <span class="text-white font-bold">
                LOGO
            </span>
        </div>

        <ul class="flex flex-col mt-10">
            <li v-for="link in links"
                :key="link.name"
                class="w-full px-3 mb-1"
            >
                <SidebarLink v-if="!link.children"
                             :link="link"
                />

                <div v-else
                     class="flex flex-col"
                >
                    <div class="h-10 rounded-md flex items-center pl-5 hover:bg-primary-50 cursor-pointer"
                         @click="expand(link)"
                    >
                        <i v-if="link.icon"
                           :class="[link.icon, {
                               'text-gray-400': !link.expanded,
                           }]"
                           class="mr-2"
                        />

                        <span :class="{
                            'font-semibold': link.expanded
                        }">
                            {{ link.title }}
                        </span>

                        <span class="ml-auto w-10 h-10 flex-center">
                             <i class="mt-1"
                                :class="{
                                    'icon-angle-right': !link.expanded,
                                    'icon-angle-down': link.expanded,
                                    }"
                             />
                        </span>
                    </div>


                    <ul v-if="link.expanded"
                        class="flex flex-col pl-3 mt-1"
                    >
                        <li v-for="child in link.children"
                            :key="child.name"
                        >
                            <SidebarLink :link="child"
                                         class="mb-1"
                            />
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
import {reactive, onMounted, computed, inject} from 'vue';
import { useRoute } from 'vue-router';
import {useI18n} from 'vue-i18n';
import SidebarLink from '@/Components/Layout/SidebarLink';

export default {
    name: 'Sidebar',
    components: {SidebarLink},
    setup(props) {
        const route = useRoute();
        const t = useI18n().t;
        const app = inject('app');
        const links = reactive([
            {
                name: 'dashboard',
                icon: 'icon-graph-bar',
            }, {
                name: 'settings',
                permission: 'settings.list',
                title: t('page.title.settings.title'),
                icon: 'icon-cog',
                children: [
                    {
                        name: 'settings.user-group.list',
                        permission: 'user-group.list',
                    },{
                        name: 'settings.user.list',
                        permission: 'user.list',
                    }, {
                        name: 'settings.shift.list',
                        permission: 'shift.list',
                    }
                ]
            }
        ]);

        function mapLink(link) {
            if (link.permission) {
                if (!app.permission.has(link.permission)) {
                    return null;
                }
            }

            link.active = route.name === link.name;
            link.title = link.title || t(`page.title.${link.name}`);
            link.expanded = false;

            if (link.children) {
                const index = link.children.findIndex((link) => link.name === route.name);
                link.expanded = index !== -1;
            }
            return link;
        }

        return {
            links: computed(() => {
                return links.map((link) => {
                    link = mapLink(link);
                    if (!link) return null;
                    if (link.children) {
                        link.children = link.children.map(mapLink)
                            .filter(item => item);
                    }
                    return link;
                }).filter(item => item);
            }),

            expand(link) {
                link.expanded = !link.expanded;
            }
        }
    }
}
</script>
