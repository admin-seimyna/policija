<template>
    <CrudForm
        action="/settings/user"
        :title="title"
        :data="user"
        @close="emit('close')"
    >
        <template #default="{data,errors}">
            <VInput v-model="data.name"
                    name="name"
                    :errors="errors"
            />

            <VInput v-model="data.email"
                    name="email"
                    class="mt-5"
                    :errors="errors"
            />

            <VInput v-model="data.password"
                    name="password"
                    class="mt-5"
                    :errors="errors"
            >
                <template #append>
                    <span class="text-xs font-semibold cursor-pointer hover:underline"
                          @click="generatePassword"
                    >
                        {{ $t('common.button.generate_password')}}
                    </span>
                </template>
            </VInput>

            <VCheckbox v-if="authUser.is_owner"
                       v-model="data.role"
                       name="role"
                       true-value="owner"
                       :false-value="null"
                       :title="$t('field.title.role.owner')"
                       class="mt-5"
            />

            <VSelect v-model="data.user_group_id"
                     name="user_group_id"
                     name-key="title"
                     class="mt-5"
                     :errors="errors"
                     :payload="userGroups"
            />
        </template>
    </CrudForm>
</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
import {computed} from 'vue';
import CrudForm from '@/Elements/Crud/From';
import {useI18n} from 'vue-i18n';
import VSelect from '@/Elements/VSelect';
import {useStore} from 'vuex';
import VCheckbox from '@/Elements/Checkbox';

export default {
    name: 'UserCreate',
    components: {VCheckbox, VSelect, CrudForm, VButton, VInput, VForm},
    emits: ['close'],
    props: {
        user: Object,
    },
    setup(props, {emit}){
        const t = useI18n().t;
        const store = useStore();

        function generate(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while (counter < length) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
                counter += 1;
            }
            return result;
        }

        return {
            emit,
            userGroups: computed(() => store.getters['user_group/list']),
            authUser: computed(() => store.getters['auth/user']),
            title: computed(() => {
                return props.user ? t('user.title.create') : t('user.title.update');
            }),
            generatePassword() {
                props.user.password = generate(16);
            }
        };
    },
}
</script>
