<template>
    <app-layout :name="name">
        <div class="transactions">
            <div class="flex items-center justify-between pt-10 mb-4">
                <div class="pt-2">
                    <h1 class="text-3xl font-semibold text-gray-900">Операции</h1>
                </div>
                <button class="text-base text-orange-400 flex items-center">
                    <icon name="excel" class="w-5 mr-3 block fill-current text-gray-900"/>
                    Экспорт в MS Excel
                </button>
            </div>
            <div class="flex items-center mb-5">
                <button class="text-base text-orange-400 mr-4" @click="$modal.open('balance_replenishment')">
                    Пополнить баланс организации
                </button>
                <button class="text-base text-orange-400 mr-4" @click="$modal.open('transfer_to_balance')">
                    Перевести на баланс
                </button>
                <button class="text-base text-orange-400 mr-4" @click="$modal.open('issue_a_loan')">Выдать кредит</button>
                <button class="text-base text-orange-400 mr-4" @click="$modal.open('pay_off_commission')">Погасить комиссию</button>
            </div>
            <div class="flex">

                <branch-select v-model="filters.selectedBranches" />
                <courier-select v-model="filters.selectedCouriers"/>

                <div class="flex items-center text-base mr-6">
                    <span class="mr-3">Дата с</span>
                    <div class="flex items-center">
                        <v-date-picker v-model="filters.dateFrom" mode="date" is-expanded  ref="datepicker" :masks="masks">
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
                        <v-date-picker v-model="filters.dateTo" mode="date" is-expanded  ref="datepicker" :masks="masks">
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
                <div class="flex items-center">
                    <span class="mr-2 text-gray-900">Статус:</span>
                    <div class="w-40">
                        <multiselect v-model="filters.status"
                                     placeholder="По статусу"
                                     track-by="value"
                                     label="label"
                                     :options="statuses">
                        </multiselect>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button class="text-orange-400 text-base" @click="setFilters()">
                    Сбросить фильтр
                </button>
            </div>
            <transactions-table :operations="operations.data" @cancellation="cancellation"/>
            <pagination v-if="operations.meta && operations.meta.last_page > 1" :meta="operations.meta"/>
            <div class="modal">
                <cancellation-modal ref="cancellationModal" />
                <modal name="balance_replenishment" close-text>
                    <div class="-mt-6">
                        <h2 class="text-xl text-gray-900 font-medium mb-5">Пополнить баланс организации</h2>
                        <div class="flex pb-4 mb-6">
                            <div class="w-1/4 px-2 flex flex-col">
                                <div class="flex flex-col w-full">
                                    <label class="text-gray-900 text-xl font-medium mb-3">Срок взноса</label>
                                    <v-date-picker v-model="date" mode="date" is-expanded  ref="datepicker">
                                        <template v-slot="{ inputValue, togglePopover }">
                                            <div class="flex flex-col w-full relative">
                                                <input class="pl-3 pr-12 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                                       @click="togglePopover"
                                                       :value="inputValue"
                                                       type="text"
                                                       placeholder="Срок взноса">
                                                <div class="w-12 h-full absolute top-0 right-0 flex justify-center items-center">
                                                    <icon name="calendar" class="h-5 w-5 fill-current text-orange-400"></icon>
                                                </div>
                                            </div>
                                        </template>
                                    </v-date-picker>
                                </div>
                            </div>
                            <div class="w-1/4 px-2 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Сумма пополнения</label>
                                <input v-model="amount"
                                       type="text"
                                       placeholder="Ввод"
                                       class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                            <div class="w-2/4 pl-4 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Примечание</label>
                                <input v-model="comment"
                                       type="text"
                                       placeholder="Ввод"
                                       class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7 mr-5"
                                    @click="saveOperation('balance_replenishment')">
                                Пополнить
                            </button>
                            <button class="leading-tight py-3 border border-orange-400 text-orange-400 text-white rounded-md px-7"
                                    @click="$modal.close('balanceReplenishment')">
                                Отмена
                            </button>
                        </div>
                    </div>
                </modal>
                <modal name="transfer_to_balance" close-text>
                    <div class="-mt-6">
                        <h2 class="text-xl text-gray-900 font-medium mb-5">Перевести на баланс</h2>
                        <div class="flex pb-4 mb-6">
                            <div class="w-1/4 px-2 flex flex-col">
                                <account-number-selection :owners="['branch', 'courier']" @wallet-selected="onWalletSelected" />
                            </div>
                            <div class="w-1/4 px-2 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Сумма перевода</label>
                                <input v-model="amount"
                                       type="number"
                                       placeholder="Ввод"
                                       class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                            <div class="w-2/4 pl-4 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Примечание</label>
                                <input v-model="comment"
                                       type="text"
                                       placeholder="Ввод"
                                       class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7 mr-5"
                                    @click="saveOperation('transfer_to_balance')">
                                Перевести
                            </button>
                            <button class="leading-tight py-3 border border-orange-400 text-orange-400 text-white rounded-md px-7"
                                    @click="$modal.close('transfer_to_balance')">
                                Отмена
                            </button>
                        </div>
                    </div>
                </modal>

                <modal class="credit" name="issue_a_loan" close-text>
                    <div class="-mt-6">
                        <h2 class="text-xl text-gray-900 font-medium mb-5">Выдать кредит</h2>
                        <div class="flex pb-4 mb-6">
                            <div class="w-1/3 pr-4 flex flex-col">
                                <account-number-selection :owners="['courier']" @wallet-selected="onWalletSelected"/>
                            </div>
                            <div class="w-1/3 px-2 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Сумма кредита</label>
                                <input v-model="amount"
                                       type="number"
                                       placeholder="Ввод"
                                       class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                            <div class="w-1/3 pl-4 flex flex-col">
                                <div class="flex flex-col w-full">
                                    <label class="text-gray-900 text-xl font-medium mb-3">Срок кредита</label>
                                    <v-date-picker v-model="dateTo" mode="date" is-expanded  ref="datepicker">
                                        <template v-slot="{ inputValue, togglePopover }">
                                            <div class="flex flex-col w-full relative">
                                                <input class="pl-3 pr-12 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                                    @click="togglePopover"
                                                    :value="inputValue"
                                                    type="text"
                                                    placeholder="Срок взноса">
                                                <div class="w-12 h-full absolute top-0 right-0 flex justify-center items-center">
                                                    <icon name="calendar" class="h-5 w-5 fill-current text-orange-400"></icon>
                                                </div>
                                            </div>
                                        </template>
                                    </v-date-picker>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div class="w-2/4 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Примечание</label>
                                <input v-model="comment"
                                       type="text"
                                       placeholder="Ввод"
                                       class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                            <div class="flex items-end">
                                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-8 mr-5"
                                        @click="saveOperation('issue_a_loan')">
                                    Выдать кредит
                                </button>
                                <button class="leading-tight py-3 border border-orange-400 text-orange-400 text-white rounded-md px-8"
                                        @click="$modal.close('issue_a_loan')">
                                    Отмена
                                </button>
                            </div>
                        </div>
                    </div>
                </modal>
                <modal class="commission" name="pay_off_commission" close-text>
                    <div class="-mt-6">
                        <h2 class="text-xl text-gray-900 font-medium mb-5">Погасить комиссию</h2>
                        <div class="flex pb-4 mb-6">
                            <div class="w-3/12 pr-4 flex flex-col">
                                <account-number-selection :owners="['branch']" @wallet-selected="onWalletSelected"/>
                            </div>
                            <div class="w-3/12 px-2 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Сумма кредита</label>
                                <input v-model="amount"
                                       type="number"
                                       placeholder="Ввод"
                                       class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                            <div class="w-5/12 pl-4 flex flex-col">
                                <div class="flex justify-between">
                                    <span class="text-gray-900 text-xl font-medium mb-3">Дата комиссии</span>
                                    <button class="text-base text-orange-400 flex items-center">
                                        <icon name="word" class="w-5 mr-3 block fill-current text-gray-900"/>
                                        Получить акт сверки
                                    </button>
                                </div>
                                <div class="flex">
                                    <div class="flex items-center mr-5">
                                        <span class="text-base text-gray-900 mr-2">Дата с</span>
                                        <div class="flex items-center">
                                            <v-date-picker v-model="dateFrom" mode="date" is-expanded  ref="datepicker" :masks="masks">
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
                                            <span class="text-base text-gray-900 mr-2">Дата по</span>
                                            <div class="flex items-center">
                                                <v-date-picker v-model="dateTo" mode="date" is-expanded  ref="datepicker" :masks="masks">
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
                                </div>
                                <span class="text-base text-gray-900">Сумма комиссии за период: 1300 сом</span>
                            </div>

                            </div>
                    </div>
                    <div class="flex justify-between">
                        <div class="w-2/4 flex flex-col">
                            <label class="text-gray-900 text-xl font-medium mb-3">Примечание</label>
                            <input v-model="comment"
                                   type="text"
                                   placeholder="Ввод"
                                   class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                          focus:border-gray-150 focus:outline-none text-gray-900">
                        </div>
                        <div class="flex items-end">
                            <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-8 mr-5"
                                    @click="saveOperation('pay_off_commission')">
                                Погасить комиссию
                            </button>
                            <button class="leading-tight py-3 border border-orange-400 text-orange-400 text-white rounded-md px-8"
                                    @click="$modal.close('pay_off_commission')">
                                Отмена
                            </button>
                        </div>
                    </div>
                </modal>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'
import TransactionsTable from '@/Components/Billing/TransactionsTable'
import JetDropdown from '@/Jetstream/Dropdown'
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'
import AccountNumberSelection from '@/Components/Billing/AccountNumberSelection'
import CancellationModal from '../../Components/Billing/Operations/Modals/CancellationModal'
import throttle from 'lodash/throttle'
import Modals from '@/Plugins/Modal/modals'
import CourierSelect from '../../Components/Billing/CouriersSelect'
import BranchSelect from '../../Components/Billing/BranchSelect'
import Pagination from '../../Components/Pagination'

export default {
    props: {
        partners: {
            type: Object,
            default: () => ({data: []})
        },
        couriers: {
            type: Object,
            default: () => ({data: []})
        },
        operations: {
            type: Object,
            default: () => ({data: []})
        },
        statuses: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        partnerOptions() {
            let data = this.partners.data;
            return Array.isArray(data) ? data.map(p => {
                return {
                    id: p.id,
                    fullName: p.attributes.fullName,
                    branches: Array.isArray(p.relationships.branches) ? p.relationships.branches : []
                }
            }) : []
        },
        courierOptions() {
            return this.couriers.data && Array.isArray(this.couriers.data) ? this.couriers.data : []
        }
    },
    data() {
        return {
            // Save operation data
            date: new Date(),
            amount: null,
            comment: null,
            dateFrom: null,
            dateTo: null,
            wallet: {},

            filters: {
                dateFrom: null,
                dateTo: null,
                status: null,
                selectedCouriers: [],
                selectedBranches: []
            },
            orderType: null,
            masks: {
                input: 'DD.MM',
            },
            name: 'billing',
        }
    },
    components: {
        Pagination,
        BranchSelect,
        CourierSelect,
        CancellationModal,
        AppLayout,
        TransactionsTable,
        JetDropdown,
        VDatePicker,
        AccountNumberSelection,
    },
    methods: {
        onWalletSelected(wallet) {
            this.wallet = wallet
        },
        getReloadRequestData() {
            let data = {}
            if (this.filters.status && this.filters.status.value !== 'all') {
                data.status = this.filters.status.value
            }
            if (this.filters.dateFrom) {
                data.date_from = this.getServerFormatDate(this.filters.dateFrom)
            }
            if (this.filters.dateTo) {
                data.date_to = this.getServerFormatDate(this.filters.dateTo)
            }
            if (this.filters.selectedCouriers.length) {
                data.courier_ids = this.filters.selectedCouriers.map(c => c.id)
            }
            if (this.filters.selectedBranches.length) {
                data.branch_ids = this.filters.selectedBranches.map(c => c.id)
            }

            return data
        },
        getSaveOperationRequestData(type) {
            let data = {
                date: this.getServerFormatDate(this.date),
                comment: this.comment,
                amount: this.amount,
                type
            }

            switch (type) {
                case 'transfer_to_balance':
                    data.receiver_wallet_id = this.wallet.id
                    break
                case 'issue_a_loan':
                    data.receiver_wallet_id = this.wallet.id
                    data.date_to = this.getServerFormatDate(this.dateTo)
                    break
                case 'pay_off_commission':
                    data.sender_wallet_id = this.wallet.id
                    data.date_from = this.getServerFormatDate(this.dateFrom)
                    data.date_to = this.getServerFormatDate(this.dateTo)
            }

            return data
        },
        async cancellation(operation) {
            await this.$refs.cancellationModal.open(operation)
            this.reload()
        },
        async saveOperation(type) {
            try {
                await window.axios.post('/billing/operations', this.getSaveOperationRequestData(type))
                this.$modal.close(type)
                this.reload()
            } catch (e) {
                if (e.response && e.response.status === 422) {
                    this.errors = e.response.data.errors
                } else {
                    throw e
                }
            }
        },
        reload(only = ['operations']) {
            this.$inertia.get('/billing/operations', this.getReloadRequestData(), {
                only,
                preserveState: true
            })
        },
        setFilters(params = {}) {
            this.filters = {
                dateFrom: params.date_from ? new Date(params.date_from) : null,
                dateTo: params.date_to ? new Date(params.date_to) : null,
                status: params.status ? this.statuses.find(s => s.value === params.status) : '',
                selectedCouriers: [],
                selectedBranches: []
            }
        },
        onModalOpen(){
            this.date = new Date()
            this.amount = null
            this.comment = null
            this.dateFrom = null
            this.dateTo = null
            this.wallet = {}
        },
        getServerFormatDate(date) {
            return date.toISOString().slice(0, 10)
        }
    },
    created() {
        Modals.$on('open', this.onModalOpen)
        this.setFilters(route().params)
    },
    beforeDestroy() {
        Modals.$off('open')
    },
    watch: {
        filters: {
            deep: true,
            handler: throttle(function() {
                this.reload()
            }, 200)
        }
    }
}
</script>
<style>
    .multiselect--above.multiselect--active .multiselect__tags {
        border-radius: 0.5rem;
    }
</style>
