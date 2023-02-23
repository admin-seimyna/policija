<template>
    <Page>
        <template #action>
            <VButton
                warning
                shadow
                @click="create"
            >
                + {{ $t('report.button.create') }}
            </VButton>
        </template>
        <ReportStatistic ref="statsRef" />
        <DashboardReports @reloadStatistic="reloadStatistic"/>
    </Page>
</template>
<script>
import Page from '@/Components/Layout/Page';
import DashboardReports from '@/Components/Dashboard/Report/Pagination';
import VButton from '@/Elements/Button';
import ReportForm from '@/Components/Dashboard/Report/Form';
import {inject, ref} from 'vue';
import ReportStatistic from '@/Components/Dashboard/Report/Statistic';

export default {
    name: 'Dashboard',
    components: {
        ReportStatistic,
        VButton,
        DashboardReports,
        Page
    },

    setup(props) {
        const statsRef = ref(null);
        const app = inject('app');

        function openForm(report) {
            app.modal({
                component: ReportForm,
                props: {
                    report: report || {},
                    onSuccess: () => {
                        statsRef.value.load();
                    }
                }
            });
        }

        return {
            statsRef,
            create() {
                openForm();
            },
            reloadStatistic(filters) {
                statsRef.value.load(filters);
            }
        }
    }
}
</script>
