<template>
    <Page>
        <template #action>
            <VButton v-if="$app.permission.has('user.create')"
                     warning
                     shadow
                     @click="createUser"
            >
                + {{ $t('common.button.add') }}
            </VButton>
        </template>
        <div class="w-full bg-white rounded-md shadow-lg py-3">
            <VTable pagination="user/pagination"
                    :columns="columns"
                    url="/settings/user/pagination"
                    basic
            >
                <template #name="{data}">
                    <span class="font-semibold">
                        {{ data }}
                    </span>
                </template>
                <template #user_group="{data}">
                    {{ data ? data.title : 'Nenurodyta' }}
                </template>
                <template #created_at="{data}">
                    {{ $app.formatter.date(data) }}
                </template>
                <template #actions="{index, item}">
                    <CrudContextMenu
                        :delete-url="`/settings/user/${item.id}`"
                        permission="user"
                        @edit="edit(index)"
                    />
                </template>
            </VTable>
        </div>
    </Page>
</template>
<script>
import Page from '@/Components/Layout/Page';
import VTable from '@/Elements/Table';
import VButton from '@/Elements/Button';
import { inject} from 'vue';
import UserForm from '@/Components/Settings/Users/Form';
import { useStore } from 'vuex';
import CrudContextMenu from '@/Elements/Crud/ContextMenu';

export default {
    name: 'Users',
    components: {
        CrudContextMenu,
        VButton,
        VTable,
        Page
    },
    setup(){
        const app = inject('app');
        const store = useStore();

        function openForm(user) {
            app.modal({
                component: UserForm,
                props: {
                    user: user || {},
                }
            });
        }

        return {
            columns: [
                {
                    name: 'name',
                },{
                    name: 'email',
                }, {
                    name: 'user_group',
                },{
                    name: 'created_at'
                }, {
                    name: 'actions',
                    titleDisabled: true,
                    overflow: true,
                    class: 'max-w-16'
                }
            ],
            edit(index) {
                openForm(store.getters['user/pagination'].data[index]);
            },
            createUser: () => openForm(),
        }
    }
}
</script>
