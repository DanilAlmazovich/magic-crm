<template>
    <app-layout :name="name" content-width="w-10/12">
        <div class="flex items-center justify-between pt-10 mb-4">
            <div class="pt-2">
                <h1 class="text-3xl font-semibold text-gray-900">Состояние счетов</h1>
            </div>
            <div class="flex">
                <button
                    class="text-base flex items-center py-1 px-3 leading-none border-r-2 border-gray-150"
                    :class="couriersTabClass"
                    @click="setActiveTab('courier')">
                    Курьеры
                </button>
                <button
                    class="text-base flex items-center py-1 px-3 leading-none"
                    :class="branchesTabClass"
                    @click="setActiveTab('branch')">
                    Партнеры
                </button>
            </div>
        </div>
        <div class="flex">
            <div class="flex mr-5">

                <courier-select v-if="filters.ownerType === 'courier'"
                                v-model="filters.selectedCouriers"/>
                <branch-select v-else v-model="filters.selectedBranches"/>

            </div>
            <button @click="resetFilters" class="text-orange-400 text-base">Сбросить фильтр</button>
        </div>

        <accounts-table :wallets="wallets.data" />

        <pagination v-if="wallets.meta && wallets.meta.last_page > 1" :meta="wallets.meta"/>

        <div class="modal">
            <modal name="transactionsModal" close-text>
                <div class="-mt-6">
                    <h2 class="text-xl text-gray-900 font-medium mb-5">
                        Состояние текущего баланса: {{ currentAmount }} сом
                    </h2>
                    <div class="flex mb-4">
                        <div class="flex items-center text-base mr-6">
                            <span class="mr-3">Дата с</span>
                            <div class="flex items-center">
                                <v-date-picker v-model="transactionFilters.dateFrom" mode="date" is-expanded  ref="datepicker" :masks="masks">
                                    <template v-slot="{ inputValue, togglePopover }">
                                        <div class="flex items-center">
                                            <span class="font-medium mr-3"
                                                @click="togglePopover">{{ inputValue }}</span>
                                            <button class="flex items-center" @click="togglePopover">
                                                <icon name="calendar" class="dropdown-icon w-4 inline-block fill-current text-orange-400"></icon>
                                            </button>
                                        </div>
                                    </template>
                                </v-date-picker>
                            </div>
                        </div>
                        <div class="flex items-center text-base mr-6">
                            <span class="mr-3">Дата по</span>
                            <div class="flex items-center">
                                <v-date-picker v-model="transactionFilters.dateTo" mode="date" is-expanded  ref="datepicker" :masks="masks">
                                    <template v-slot="{ inputValue, togglePopover }">
                                        <div class="flex items-center">
                                            <span class="font-medium mr-3"
                                                @click="togglePopover">{{ inputValue }}</span>
                                            <button class="flex items-center" @click="togglePopover">
                                                <icon name="calendar" class="dropdown-icon w-4 inline-block fill-current text-orange-400"></icon>
                                            </button>
                                        </div>
                                    </template>
                                </v-date-picker>
                            </div>
                        </div>
                    </div>
                    <h2 class="text-xl text-gray-900 font-medium mb-5">Транзакции</h2>
                    <table class="w-full">
                        <tr>
                            <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                            <th class="text-sm text-left text-gray-200 list pr-1">Дата изменения</th>
                            <th class="text-sm text-left text-gray-200 list pr-1">
                                <span class="flex flex-col">
                                    <span>Сумма</span>
                                    <span>изменения</span>
                                </span>
                            </th>
                            <th class="text-sm text-left text-gray-200 list pr-1">№ заказа</th>
                            <th class="text-sm text-left text-gray-200 list pr-1">
                                <span class="flex flex-col">
                                    <span>Тип</span>
                                    <span>транзакции</span>
                                </span>
                            </th>
                            <th class="text-sm text-left text-gray-200 list pr-1">
                                <span class="flex flex-col">
                                    <span>Оператор</span>
                                    <span>CRM</span>
                                </span>
                            </th>
                            <th class="text-sm text-left text-gray-200 list pr-1">Комментарий</th>
                        </tr>
                        <tr v-for="transaction in transactions.data"
                            class="cursor-pointer hover:bg-gray-100"
                            :key="`transactions-modal-list-${transaction.id}`">
                            <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none pt-1 pb-3 pl-2 list">
                                {{ transaction.id }}
                            </td>
                            <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none pt-1 pb-3 list">
                                {{ transaction.attributes.createdAt }}
                            </td>
                            <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none pt-1 pb-3 list">
                                {{ getFormattedAmount(transaction) }} сом
                            </td>
                            <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none pt-1 pb-3 list">
                                {{ transaction.attributes.orderId }}
                            </td>
                            <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none pt-1 pb-3 list">
                                {{ transaction.attributes.typeLabel}}
                            </td>
                            <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none pt-1 pb-3 list">
                                {{ operatorName(transaction) }}
                            </td>
                            <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none pt-1 pb-3 list">
                                {{ transaction.attributes.comment }}
                            </td>
                        </tr>
                    </table>
                </div>
            </modal>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'
import AccountsTable from  '@/Components/Billing/AccountsTable'
import JetDropdown from '@/Jetstream/Dropdown'
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'
import CourierSelect from '../../Components/Billing/CouriersSelect'
import BranchSelect from '../../Components/Billing/BranchSelect'
import modals from '@/Plugins/Modal/modals'
import Pagination from "../../Components/Pagination"

export default {
    props: {
        transactions: {
            type: Object,
            default: () => ({ data: [] })
        },
        wallets: {
            type: Object,
            default: () => ({data: []})
        }
    },
    computed: {
        couriersTabClass() {
            return {
                'text-orange-400': this.filters.ownerType === 'courier',
                'text-gray-150': this.filters.ownerType === 'branch'
            };
        },
        branchesTabClass() {
            return {
                'text-orange-400': this.filters.ownerType === 'branch',
                'text-gray-150': this.filters.ownerType === 'courier'
            }
        }
    },
    data() {
        return {
            name: 'billing',

            currentAmount: 0,

            filters: {
                ownerType: 'courier',
                selectedCouriers: [],
                selectedBranches: []
            },
            transactionFilters: {
                walletId: 0,
                dateFrom: null,
                dateTo: null
            },

            masks: {
                input: 'DD.MM',
            },
        }
    },
    components: {
        BranchSelect,
        CourierSelect,
        AppLayout,
        AccountsTable,
        JetDropdown,
        Pagination,
        VDatePicker,
    },
    methods: {
        operatorName(transaction) {
            let operator = transaction.relationships.operator

            return operator.hasOwnProperty('full_name') ? operator.full_name : operator.name
        },
        getFilters() {
            return {
                ownerType: this.filters.ownerType,
                selectedCouriers: [],
                selectedBranches: []
            }
        },
        getFormattedAmount(transaction) {
            return (this.transactionFilters.walletId === transaction.attributes.senderWalletId
                ? -transaction.attributes.amount
                : transaction.attributes.amount)
        },
        setActiveTab(tab) {
            if (tab === 'courier') {
                this.filters.selectedBranches = []
            } else if (tab === 'branch') {
                this.filters.selectedCouriers = []
            }
            this.filters.ownerType = tab
        },
        getWalletsReloadRequestData(){
            const data = {
                owner_type: this.filters.ownerType
            }

            if (this.filters.selectedCouriers.length) {
                data.owner_ids = this.filters.selectedCouriers.map(c => c.id)
            }

            if (this.filters.selectedBranches.length) {
                data.owner_ids = this.filters.selectedBranches.map(b => b.id)
            }

            if (this.transactionFilters.walletId) {
                data.wallet_id = this.transactionFilters.walletId
            }

            if (this.transactionFilters.dateFrom) {
                data.date_from = this.transactionFilters.dateFrom
            }

            if (this.transactionFilters.dateTo) {
                data.date_to = this.transactionFilters.dateTo
            }

            return data
        },
        reload(only = ['wallets']) {
            this.$inertia.get('/billing/wallets', this.getWalletsReloadRequestData(), {
                only,
                preserveState: true
            })
        },
        resetFilters() {
            this.filters = this.getFilters()
        }
    },
    created() {
        modals.$on('open', (name, data) => {
            this.transactionFilters.walletId = data.walletId
            this.currentAmount = data.amount
        })
    },
    beforeDestroy() {
        modals.$off('open')
    },
    watch: {
        filters: {
            handler() {
                this.reload()
            },
            deep: true,
            immediate: true
        },
        transactionFilters: {
            handler() {
                this.reload(['transactions'])
            },
            deep: true
        }
    }
}
</script>
<style scoped>
    .pagination {
        border-left: none;
    }
    .active {
        border-top: none;
        border-bottom: none;
        background: #FFBE17;
    }
    .checkbox {
        flex-direction: row-reverse;
    }
    .checkbox span:nth-child(1) {
        margin-left: 15px;
    }
    .content:before {
        right: calc(50% - 0.75rem);
    }
    .select-content::-webkit-scrollbar {
        width: 4px; /* ширина для вертикального скролла */
        height: 8px; /* высота для горизонтального скролла */
        background-color: #E4E4E4;
    }
    .select-content::-webkit-scrollbar-thumb {
        background-color: #d0d0d0;
        border-radius: 9em;
        box-shadow: inset 1px 1px 10px #f3faf7;
    }
    .modal .list:nth-child(1) {
    width: 4%;
    }
    .modal .list:nth-child(7) {
        width: 25%;
    }
    .modal .list {
        width:10%;
    }
</style>
