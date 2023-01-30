<template>
    <Page>
        <template #action>
            <VButton primary
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
                <template #actions="{index}">
                    <div class="w-5 h-5 cursor-pointer flex-center"
                         @click="edit(index)"
                    >
                        <i class="icon-pencil" />
                    </div>
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

export default {
    name: 'UserGroups',
    components: {VTable, VButton, Page},
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
