<template>
    <CrudForm action="/profile"
              :data="user"
              keep-original-action
              :title="$t('user.title.profile')"
    >
        <template #default="{data, progress, errors}">
            <VInput
                v-model="data.name"
                name="name"
            />


            <div class="flex flex-col bg-gray-100 rounded mt-5 p-3">
                <VCheckbox v-model="data.change_password"
                           name="change_password"
                />

                <VInput v-if="data.change_password"
                        type="password"
                        name="password"
                        class="mt-3"
                />
            </div>
        </template>
    </CrudForm>
</template>
<script>
import CrudForm from '@/Elements/Crud/From';
import VInput from '@/Elements/Input';
import {computed} from 'vue';
import {useStore} from 'vuex';
import VCheckbox from '@/Elements/Checkbox';
export default {
    name: 'ProfileForm',
    components: {VCheckbox, VInput, CrudForm},
    setup(props) {
        const store = useStore();

        return {
            user: computed(() => {
                return {...store.getters['auth/user']};
            }),
        }
    }
}
</script>
