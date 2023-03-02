<template>
    <div class="w-full flex items-center px-10 h-16 shrink-0 shadow-md bg-white z-50">
        <DropDown v-if="user"
                  class="ml-auto"
        >
            <Avatar class="w-10 h-10"
                    :subject="user"
            />
            <template #content>
                <ul class="flex flex-col py-2">
                    <li class="cursor-pointer hover:bg-gray-100 p-2"
                        @click="openProfile"
                    >
                        {{ $t('user.button.profile') }}
                    </li>
                    <li class="cursor-pointer hover:bg-gray-100 p-2"
                        @click="logout"
                    >
                        {{ $t('common.button.logout') }}
                    </li>
                </ul>
            </template>
        </DropDown>
    </div>
</template>
<script>
import DropDown from '@/Elements/DropDown';
import Avatar from '@/Elements/Avatar';
import {computed, inject} from 'vue';
import {useStore} from 'vuex';
import {useI18n} from 'vue-i18n';
import { useRouter } from 'vue-router';
import axios from 'axios';
import ProfileForm from '@/Components/Profile/Form';

export default {
    name: 'Navigation',
    components: {Avatar, DropDown},
    setup(props) {
        const store = useStore();
        const { t } = useI18n();
        const router = useRouter();
        const app = inject('app');
        return {
            user: computed(() => store.getters['auth/user']),
            openProfile() {
                app.modal({
                    component: ProfileForm,
                })
            },
            logout() {
                app.prompt(
                    t('auth.title.logout_prompt'),
                    t('auth.message.logout_prompt'),
                ).then(() => {
                    axios.post('/logout').then(() => {
                        store.commit('auth/user', null);
                        router.push({ name: 'login' });
                    })
                });
            }
        }
    }
}
</script>
