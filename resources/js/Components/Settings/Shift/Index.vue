<template>
    <Page>
        <template #action>
            <VButton primary
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
                <template #name="{data}">
                    <span class="font-semibold">
                        {{ data }}
                    </span>
                </template>
                <template #time="{data}">
                    Time...
                </template>
                <template #created_at="{data}">
                    {{ $app.formatter.date(data) }}
                </template>
                <template #actions="{index}">
                    <div class="w-5 h-5 cursor-pointer flex-center">
                        <i class="icon-pencil"
                           @click="edit(index)"
                        />
                    </div>
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

export default {
    name: 'Shifts',
    components: {
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
                    name: 'name',
                },{
                    name: 'time',
                },{
                    name: 'created_at'
                }, {
                    name: 'actions',
                    titleDisabled: true,
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
