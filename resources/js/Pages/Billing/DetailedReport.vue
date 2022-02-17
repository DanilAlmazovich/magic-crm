<template>
    <app-layout name="billing">
        <div class="summary-report">
            <div class="flex items-center justify-between pt-10 mb-4">
                <div class="pt-2">
                    <h1 class="text-3xl font-semibold text-gray-900">
                        {{ this.$page.props.auth.user.isOrderOperator ? 'Детальный отчет' : 'Детальный отчет по операторам' }}
                    </h1>
                </div>
                <button @click.prevent="exportToXlsx"
                        class="text-base text-orange-400 flex items-center">
                    <icon name="excel" class="w-5 mr-3 block fill-current text-gray-900"/>
                    Экспорт в MS Excel
                </button>
            </div>
            <filter-panel :is-operators="! this.$page.props.auth.user.isOrderOperator"
                          @change="onFilterChange"/>
            <detailed-report-table :operators="operators.data" />
            <pagination :meta="operators.meta"/>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'
import DetailedReportTable from  '@/Components/Billing/DetailedReportTable'
import FilterPanel from '../../Components/Billing/FilterPanel'
import Pagination from '../../Components/Pagination'

export default {
    props: {
        operators: {
            type: Object,
            default: () => ({ data: [] })
        }
    },
    components: {
        FilterPanel,
        AppLayout,
        DetailedReportTable,
        Pagination
    },
    data() {
        return {

        }
    },
    methods: {
        exportToXlsx() {
            window.location.href = '/billing/detailed-report/export' + window.location.search
        },
        onFilterChange(filters) {
            this.reload(filters)
        },
        reload(filters = {}, only = ['operators']) {
            this.$inertia.get('/billing/detailed-report', filters, {
                only,
                preserveState: true
            })
        }
    }
}
</script>
<style>
    .checkbox {
        flex-direction: row-reverse;
    }
    .checkbox .name {
        margin-left: 15px;
    }
</style>
