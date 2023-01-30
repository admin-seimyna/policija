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

            <VSelect v-model="data.user_group_id"
                     name="user_group_id"
                     name-key="title"
                     class="mt-5"
                     :errors="errors"
                     :options="userGroups"
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
export default {
    name: 'UserCreate',
    components: {VSelect, CrudForm, VButton, VInput, VForm},
    emits: ['close'],
    props: {
        user: Object,
    },
    setup(props, {emit}){
        const t = useI18n().t;
        const store = useStore();

        return {
            emit,
            userGroups: computed(() => store.getters['user_group/list']),
            title: computed(() => {
                return props.user ? t('user.title.create') : t('user.title.update');
            }),
        };
    },
}
</script>
