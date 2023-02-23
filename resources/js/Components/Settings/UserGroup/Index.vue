<template>
    <Page>
        <template #action>
            <VButton v-if="$app.permission.has('user-group.create')"
                     warning
                     shadow
                     @click="createGroup"
            >
                + {{ $t('common.button.add') }}
            </VButton>
        </template>

        <div class="w-full bg-white rounded-md shadow-lg py-3">
            <VTable pagination="user_group/pagination"
                    :columns="columns"
                    url="/settings/user-group/pagination"
                    basic
            >
                <template #title="{data}">
                    <span class="ellipsis font-semibold">
                        {{ data }}
                    </span>
                </template>
                <template #created_at="{data}">
                    {{ $app.formatter.date(data) }}
                </template>
                <template #actions="{index, item}">
                    <CrudContextMenu :delete-url="`/settings/user-group/${item.id}`"
                                     permission="user-group"
                                     @edit="edit(index)"
                    />
                </template>
            </VTable>
        </div>
    </Page>
</template>
<script>
import Page from '@/Components/Layout/Page';
import VButton from '@/Elements/Button';
import VTable from '@/Elements/Table';
import {inject} from 'vue';
import UserGroupForm from '@/Components/Settings/UserGroup/Form';
import {useStore} from 'vuex';
import CrudContextMenu from '@/Elements/Crud/ContextMenu';

export default {
    name: 'UserGroups',
    components: {CrudContextMenu, VTable, VButton, Page},
    setup(props) {
        const app = inject('app');
        const store = useStore();

        function openForm(group) {
            app.modal({
                component: UserGroupForm,
                props: {
                    group: group || {},
                }
            })
        }

        return {
            columns: [
                {
                    name: 'title',
                }, {
                    name: 'created_at',
                }, {
                    name: 'actions',
                    titleDisabled: true,
                    overflow: true,
                    class: 'max-w-16'
                }
            ],
            edit(index) {
                openForm(store.getters['user_group/pagination'].data[index]);
            },
            createGroup() {
                openForm();
            }
        }
    }
}
</script>
