<template>
    <app-layout :name="layoutName">
        <div class="transactions">
            <div class="flex items-center justify-between pt-10 mb-4">
                <div class="pt-2">
                    <h1 class="text-3xl font-semibold text-gray-900">Отчет по партнерам</h1>
                </div>
                <a @click.prevent="exportToXlsx" href="#" class="text-base text-orange-400 flex items-center">
                    <icon name="excel" class="w-5 mr-3 block fill-current text-gray-900"/>
                    Экспорт в MS Excel
                </a>
            </div>
<!--            <div class="flex justify-between">-->
<!--                <div class="flex">-->
<!--                    <div class="flex items-center text-base mr-6">-->
<!--                        <span class="mr-3">Дата с</span>-->
<!--                        <div class="flex items-center">-->
<!--                            <v-date-picker v-model="filters.dateFrom" mode="date" is-expanded  ref="datepicker" :masks="masks">-->
<!--                                <template v-slot="{ inputValue, togglePopover }">-->
<!--                                    <div class="flex items-center">-->
<!--                                        <span class="font-medium mr-3"-->
<!--                                            @click="togglePopover">{{ inputValue }}</span>-->
<!--                                        <button class="flex items-center" @click="togglePopover">-->
<!--                                            <icon name="calendar" class="dropdown-icon w-4 inline-block fill-current text-orange-400"></icon>-->
<!--                                        </button>-->
<!--                                    </div>-->
<!--                                </template>-->
<!--                            </v-date-picker>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="flex items-center text-base mr-6">-->
<!--                        <span class="mr-3">Дата по</span>-->
<!--                        <div class="flex items-center">-->
<!--                            <v-date-picker v-model="filters.dateTo" mode="date" is-expanded  ref="datepicker" :masks="masks">-->
<!--                                <template v-slot="{ inputValue, togglePopover }">-->
<!--                                    <div class="flex items-center">-->
<!--                                        <span class="font-medium mr-3"-->
<!--                                            @click="togglePopover">{{ inputValue }}</span>-->
<!--                                        <button class="flex items-center" @click="togglePopover">-->
<!--                                            <icon name="calendar" class="dropdown-icon w-4 inline-block fill-current text-orange-400"></icon>-->
<!--                                        </button>-->
<!--                                    </div>-->
<!--                                </template>-->
<!--                            </v-date-picker>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <branch-select v-model="filters.branchIds" @selected="selected" :removedEstablishment="establishment"/>-->
<!--                    <div class="flex mb-5 items-center justify-between rounded min-w-56 leading-tight rounded-md mr-4 pl-2">-->
<!--                        <multiselect v-model="filters.status"-->
<!--                                    placeholder="Выбрать статус"-->
<!--                                    track-by="value"-->
<!--                                    :show-labels="false"-->
<!--                                    label="label"-->
<!--                                    :options="orderStatuses">-->
<!--                            <template slot="singleLabel">-->
<!--                                <span class="option__desc">-->
<!--                                    <span class="option__title" :style="`color: ${selectedColor}`">{{ filters.status? filters.status.label : null}}</span>-->
<!--                                </span>-->
<!--                            </template>-->
<!--                            <template slot="option" slot-scope="props">-->
<!--                                <div class="option__desc"><span class="option__title" :style="`color: ${props.option.color}`">{{ props.option.label }}</span></div>-->
<!--                            </template>-->
<!--                        </multiselect>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <button class="text-orange-400 text-base" @click="setFilters">Сбросить фильтр</button>-->
<!--            </div>-->
            <filter-panel is-branches is-categories-select @change="onFilterPanelChange" />
            <div class="py-3 flex flex-wrap">
                <div v-for="(institution, index) in selectedInstitution" class="mr-3 mb-3 border border-gray-400 rounded-lg px-2 py-1">
                    <span class="text-sm text-gray-400">{{ institution.full_name }}</span>
                    <button class="w-2 h-2 ml-3" @click="remove(institution)">
                        <icon name="cross" class="fill-current text-gray-400"></icon>
                    </button>
                </div>
            </div>
            <div v-if="totals.orders_count" class="text-base text-gray-700">
                <span class="mr-5">Всего заказов: {{ totals.orders_count }}</span>
                <span>Общая сумма комиссии: {{ Number(totals.total_commission).toFixed(2) }}</span>
            </div>
            <partner-report-table :items="items.data" />
            <pagination :meta="items.meta" />
        </div>
    </app-layout>
</template>
<script>

import AppLayout from '@/Layouts/AppLayout'
import PartnerReportTable from  '@/Components/Billing/PartnerReportTable'
import JetDropdown from '@/Jetstream/Dropdown'
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'
import statusColors from '@/utils/statusColors'
import debounce from 'lodash/debounce'
import BranchSelect from '../../Components/Billing/BranchSelect'
import CategoriesSelect from '../../Components/Billing/CategoriesSelect'
import FilterPanel from '../../Components/Billing/FilterPanel'

export default {
    props: {
        items: {
            type: Object,
            default: () => ({
                data: [],
                meta: {}
            })
        },
        statuses: {
            type: Array,
            default: () => ([])
        },
        totals: {
            type: Object,
            default: () => ({
                orders_count: 0,
                total_commission: 0
            })
        }
    },
    data() {
        return {
            layoutName: 'Billing',
            statusColors: statusColors,
            filters: {
                dateFrom: null,
                dateTo: null,
                branchIds: [],
                status: null
            },
            masks: {
                input: 'DD.MM',
            },
            selectedInstitution: null,
            establishment: null,
        }
    },
    components: {
        FilterPanel,
        CategoriesSelect,
        BranchSelect,
        AppLayout,
        PartnerReportTable,
        JetDropdown,
        VDatePicker,
    },
    computed: {
        getReloadRequestData() {
            let data = {}

            if (this.filters.dateFrom) data.date_from = this.filters.dateFrom.toISOString().slice(0,10)
            if (this.filters.dateTo) data.date_to = this.filters.dateTo.toISOString().slice(0,10)
            if (this.filters.status && this.filters.status.value !== 'all')
                data.stat = this.filters.status.value
            if (this.filters.branchIds.length || this.selectedCategories.length) data.branch_ids = this.filters.branchIds.map(branch => branch.id)

            return data
        },
        orderStatuses() {
            return [{ label: 'Все', value: 'all' }]
                .concat(this.statuses)
                .map(status => ({
                    ...status,
                    color: statusColors[status.value]
                }))
        },
        selectedColor(){
            return this.filters.status ? this.filters.status.color : null
        }
    },
    methods: {
        onFilterPanelChange(filters) {
            this.$inertia.get('/billing/partner-report', filters, {
                only: ['items', 'totals'],
                preserveState: true
            })
        },
        selected(val) {
            this.selectedInstitution = val;
        },
        remove(i) {
            this.establishment = i;
        },
        exportToXlsx() {
            window.location.href = '/billing/partner-report/export' + window.location.search
        },
        reload(only = ['items', 'totals']) {
            this.$inertia.get('/billing/partner-report', this.getReloadRequestData, {
                only,
                preserveState: true
            })
        },
        setFilters(params = {}) {
            this.filters.dateFrom = params.date_from || null
            this.filters.dateTo = params.date_to || null
            this.filters.branchIds = params.branch_ids || []
            this.filters.status = params.stat || null
        }
    },
    watch: {
        filters: {
            handler: debounce(function () {
                this.reload()
            }, 300),
            deep: true
        },
    }
}
</script>
