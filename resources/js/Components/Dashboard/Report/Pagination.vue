<template>
    <VTable pagination="report/pagination"
            :columns="columns"
            url="/report/pagination"
            basic
    >
    </VTable>
</template>
<script>
import VTable from '@/Elements/Table';
export default {
    name: 'DashboardReports',
    components: {VTable},
    setup(props) {
        return {
            columns: [
                {
                    name: 'events_count',
                }, {
                    name: 'processed_events_count'
                }, {
                    name: 'an_count'
                }, {
                    name: 'traffic_events_count'
                }, {
                    name: 'unprocessed_events_count'
                }, {
                    name: 'total'
                }
            ],
        }
    }
}
</script>
