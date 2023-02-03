<template>
    <Page>
        <template #action>
            <VButton
                primary
                @click="create"
            >
                + {{ $t('report.button.create') }}
            </VButton>
        </template>
        <DashboardReports />
    </Page>
</template>
<script>
import Page from '@/Components/Layout/Page';
import DashboardReports from '@/Components/Dashboard/Report/Pagination';
import VButton from '@/Elements/Button';
import ReportForm from '@/Components/Dashboard/Report/Form';
import {inject} from 'vue';

export default {
    name: 'Dashboard',
    components: {
        VButton,
        DashboardReports,
        Page
    },

    setup(props) {
        const app = inject('app');

        function openForm(report) {
            app.modal({
                component: ReportForm,
                props: {
                    report: report || {}
                }
            });
        }

        return {
            create() {
                openForm();
            }
        }
    }
}
</script>
