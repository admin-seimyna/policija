<template>
    <Page>
        <template #action>
            <VButton v-if="$app.permission.has('shift.create')"
                     warning
                     shadow
                     @click="create"
            >
                + {{ $t('common.button.add') }}
            </VButton>
        </template>
        <div class="w-full bg-white rounded-md shadow-lg py-3">
            <VTable pagination="shift/pagination"
                    :columns="columns"
                    url="/settings/shift/pagination"
                    basic
            >
                <template #title="{data}">
                    <span class="font-semibold">
                        {{ data }}
                    </span>
                </template>
                <template #time_from="{data}">
                    {{ data }}
                </template>
                <template #time_to="{data}">
                    {{ data }}
                </template>
                <template #created_at="{data}">
                    {{ $app.formatter.date(data) }}
                </template>
                <template #actions="{index,item}">
                    <CrudContextMenu
                        :delete-url="`/settings/shift/${item.id}`"
                        permission="shift"
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
import ShiftForm from '@/Components/Settings/Shift/Form';
import { useStore } from 'vuex';
import CrudContextMenu from '@/Elements/Crud/ContextMenu';

export default {
    name: 'Shifts',
    components: {
        CrudContextMenu,
        VButton,
        VTable,
        Page
    },
    setup(){
        const app = inject('app');
        const store = useStore();

        function openForm(shift) {
            app.modal({
                component: ShiftForm,
                props: {
                    shift: shift || {},
                }
            });
        }

        return {
            columns: [
                {
                    name: 'title',
                }, {
                    name: 'time_from',
                },{
                    name: 'time_to',
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
                openForm(store.getters['shift/pagination'].data[index]);
            },
            create: () => openForm(),
        }
    }
}
</script>
