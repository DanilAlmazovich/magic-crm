<template>
    <app-layout-map content-width="w-10/12" name-page="orders">
        <div class="orders pl-6 pr-4">
            <div class="flex justify-between pt-6 mb-4">
                <div class="pt-2">
                    <h1 class="text-3xl font-semibold text-gray-900">Список заказов</h1>
                </div>
                <!--                TODO: filters-->
                <div class="flex">
                <!--                    <div class="flex items-center mr-8">-->
                <!--                        <span class="mr-4 text-gray-900">Сортировка:</span>-->
                <!--                        <div class="flex items-center">-->
                <!--                            <select class="border-none py-1 pl-0 leading-none outline-none"-->
                <!--                                    v-model="sort">-->
                <!--                                <option value="">Все</option>-->
                <!--                                <option :value="option.value" v-for="option in sortOptions">{{ option.label }}</option>-->
                <!--                            </select>-->
                <!--                        </div>-->
                <!--                    </div>-->
                    <div class="flex pl-1">
                        <input type="text"
                            class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100"
                            placeholder="Поиск заказов"
                            @keyup.enter="filter"
                            v-model="query">
                        <button class="text-white py-3 px-7 rounded-r-md"
                                :disabled="filterLoading" :class="[filterLoading ? 'bg-gray-600 cursor-not-allowed' : 'bg-yellow-400']"
                                @click="filter">{{ filterLoading ? 'Поиск...' : 'Найти' }}</button>
                    </div>
                </div>
            </div>
            <div class="flex items-center text-base hidden">
                <div class="flex items-center text-base mr-6">
                    <span class="mr-3">Дата с</span>
                    <div class="flex items-center">
                        <v-date-picker v-model="fromDate" mode="date" is-expanded ref="datepicker" :masks="masks">
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
                        <v-date-picker v-model="toDate" mode="date" is-expanded ref="datepicker" :masks="masks">
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
                <div class="flex items-center mr-8">
                    <span class="mr-2 text-gray-900">Источник:</span>
                    <div class="w-40">
                        <multiselect v-model="selectedSource"
                                    placeholder="По источнику"
                                    track-by="value"
                                    label="label"
                                    :options="sources"
                                    @input="filter">
                        </multiselect>
                    </div>
                </div>
            </div>
            <div class="flex items-center mb-6">
                <icon class="mr-3 w-4 h-4" name="question"></icon>
                <span class="text-sm text-gray-900">Для подробной информации нажмите на строку необходимого заказа</span>
            </div>
            <!--<statuses-filter></statuses-filter>-->
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center flex-wrap">
                    <button class="py-1 px-3 rounded-lg mr-3 mb-2 text-sm border border-solid"
                            :style="`color: ${status.color}; border-color: ${index === selectedStatusIndex ? status.color : '#BFBFBF'}` "
                            v-for="(status, index) in statusButtons"
                            @click="selectType(status.type, status)">
                        {{ status.label }}
                    </button>
                </div>
                <button class="text-gray-700" @click="clearFilter">Сбросить фильтры</button>
            </div>
            <div class="flex items-center flex-wrap mb-4" v-if="selectedStatus">
                <button class="py-1 px-3 rounded-lg mr-3 mb-2 text-sm border border-solid"
                        :style="`color: ${status.value === selectedStatus ? status.color : '#BFBFBF'}; border-color: ${status.value === selectedStatus ? status.color : '#BFBFBF'}` "
                        v-for="status in statusButtons[selectedStatusIndex].statuses"
                        @click="updateStatus(status.value)">
                    {{ status.label }}
                </button>
            </div>
            <div v-if="orders.data.length">
                <order-table @select-order="orderSelected"
                             :orders="ordersList"
                             :payment-types="paymentTypes"
                             :pickup-address-types="pickupAddressTypes"
                             :status="selectedStatus"
                />
                <div class="py-6">
                    <pagination v-if="orders.meta.last_page > 1" :meta="orders.meta"></pagination>
                </div>
            </div>
            <p class="bg-yellow-400 text-gray-800 py-3 px-5" v-else>Нет заказов.</p>

        </div>
        <modal name="orderAction" close-text>
            <div class="flex items-center" slot="title">
                <span class="text-xl font-medium mr-36">Применить действие</span>
            </div>
            <div class="pt-8 flex mb-4 flex-col">
                <div v-if="$page.props.auth.user.isAdmin || $page.props.auth.user.isCourierOperator"
                     class="w-full mb-3">
                    <radio-button width="w-full" v-model="selectedAction" value="Назначить курьера"></radio-button>
                </div>
                <div class="w-full mb-3">
                    <radio-button width="w-full" v-model="selectedAction" value="Передать в кафе"></radio-button>
                </div>
                <div v-if="$page.props.auth.user.isAdmin || $page.props.auth.user.isCourierOperator"
                     class="w-full mb-3">
                    <radio-button width="w-full" v-model="selectedAction" value="Отменить"></radio-button>
                </div>
            </div>
            <div class="flex justify-end">
                <button class="py-3 px-7 border border-solid border-orange-400 bg-orange-400 rounded-lg text-white" @click="handleActions">Применить</button>
            </div>
        </modal>
        <modal name="orderCanceled">
            <div class="py-4 mb-2 flex flex-col items-center px-10">
                <icon name="green-check" class="mb-4"></icon>
                <div class="text-center w-32">
                    <span class="font-medium">Заказ успешно отменен</span>
                </div>
            </div>
        </modal>
        <modal name="orderTransferred">
            <div class="py-4 mb-2 flex flex-col items-center px-10">
                <icon name="green-check" class="mb-4"></icon>
                <div class="text-center w-32">
                    <span class="font-medium">Заказ успешно передан в кафе</span>
                </div>
            </div>
        </modal>
        <modal name="orderConfirmAction">
            <div class="py-4 mb-2 flex flex-col items-center px-4">
                <icon name="green-check" class="mb-4"></icon>
                <div class="text-center w-32 mb-6">
                    <span class="font-medium">Подтвердите ваше действие</span>
                </div>
                <div class="flex items-center">
                    <button class="text-orange-400 mr-2">Отмена</button>
                    <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7 ml-2">
                        Подтвердить
                    </button>
                </div>
            </div>
        </modal>
        <modal name="orderAssignCourier" close-text>
            <active-couriers-table :order-id="selectedOrderId"></active-couriers-table>
        </modal>

        <cancel-order-modal />
    </app-layout-map>
</template>

<script>
    import AppLayoutMap from '@/Layouts/AppLayoutMap'
    import OrderTable from '@/Components/OrderTable'
    import VDatePicker from 'v-calendar/lib/components/date-picker.umd'
    import ActiveCouriersTable from '@/Components/Couriers/ActiveCouriersTable'
    import CancelOrderModal from '@/Components/Modals/CancelOrderModal'
    import { Inertia } from '@inertiajs/inertia'

    export default {
        components: {
            AppLayoutMap,
            OrderTable,
            VDatePicker,
            ActiveCouriersTable,
            CancelOrderModal,
        },

        props: {
            orders: Object,
            paymentTypes: Object,
            pickupAddressTypes: Object,
            couriers: Array,
            statuses: Object,
            sources: Array,
            status: String,
            search: String,
            dateRange: Object,
            source: String,
        },

        data() {
            return {
                selectedSource: this.source ?
                    this.sources.find(source => source.value === this.source) :
                    '',
                fromDate: this.dateRange.from || this.moment().subtract(1, 'days').format('YYYY-MM-DD'),
                toDate: this.dateRange.to || this.moment().format('YYYY-MM-DD'),
                masks: {
                    input: 'DD.MM.YYYY',
                },
                statusesItems: {
                    'new': {
                        color: 'rgba(225, 134, 255, 1)',
                        type: 'order',
                        tableColor: 'rgba(225, 134, 255, 0.3)'
                    },
                    'new_pre-order': {
                        color: 'rgba(235, 113, 0, 1)',
                        type: 'order',
                        tableColor: 'rgba(235, 113, 0, 0.2)',

                    },
                    'in-way-to-client': {
                        color: 'rgba(0, 195, 195, 1)',
                        type: 'order',
                        tableColor: 'rgba(0, 195, 195, 0.2)',
                    },
                    'delivered': {
                        color: 'rgba(91, 206, 0, 1)',
                        type: 'order',
                        tableColor: 'rgba(91, 206, 0, 0.2)',
                    },
                    'canceled-by-client': {
                        color: 'rgba(223, 0, 0, 1)',
                        type: 'cancel',
                        tableColor: 'rgba(223, 0, 0, 0.6)',
                    },
                    'canceled-by-courier': {
                        color: 'rgba(247, 102, 128, 1)',
                        type: 'cancel',
                        tableColor: 'rgba(247, 102, 128, 0.2)',
                    },
                    'canceled-by-partner': {
                        color: 'rgba(255, 136, 99, 1)',
                        type: 'cancel',
                        tableColor: 'rgba(255, 136, 99, 0.2)',
                    },
                    'canceled-by-operator': {
                        color: 'rgba(255, 128, 151, 1)',
                        type: 'cancel',
                        tableColor: 'rgba(255, 128, 151, 0.2)',
                    },
                    'removed': {
                        color: 'rgba(215, 219, 16, 1)',
                        type: 'cancel',
                        tableColor: 'rgba(215, 219, 16, 1)',
                    },
                    'transferred-to-cafe': {
                        color: 'rgba(16, 165, 31, 1)',
                        type: 'process',
                        tableColor: 'rgba(16, 165, 31, 0.2)',
                    },
                    'is-preparing': {
                        color: 'rgba(255, 79, 40, 1)',
                        type: 'process',
                        tableColor: 'rgba(255, 79, 40, 0.2)',
                    },
                    'is-ready': {
                        color: 'rgba(137, 0, 245, 1)',
                        type: 'process',
                        tableColor: 'rgba(137, 0, 245, 0.2)',
                    },
                    'courier-accepted': {
                        color: 'rgba(165, 87, 16, 1)',
                        type: 'courier',
                        tableColor: 'rgba(165, 87, 16, 0.2)',
                    },
                    'courier-assigned': {
                        color: 'rgba(0, 202, 166, 1)',
                        type: 'courier',
                        tableColor: 'rgba(0, 202, 166, 0.2)',
                    },
                    'is_delivering': {
                        color: 'rgba(165, 87, 16, 1)',
                        type: 'courier',
                        tableColor: 'rgba(165, 87, 16, 1)',
                    },
                    'completed': {
                        color: 'rgba(215, 219, 16, 1)',
                        type: 'courier',
                        tableColor: 'rgba(215, 219, 16, 0.2)',
                    },
                    'closed': {
                        color: 'rgba(215, 219, 16, 1)',
                        type: '',
                        tableColor: 'rgba(215, 219, 16, 1)',
                    },
                },
                selectedAction: null,
                sortButtons: [
                    'Все',
                    'Новые',
                    'В ожидании',
                    'Принят',
                    'На выполнении',
                    'Отмененные',
                    'Удаленные',
                    'Выполнены',
                    'Предварительные'
                ],
                selectedStatus: '',
                selectedSortOption: '',
                sortOptions: [
                    {
                        value: 'operator',
                        label: 'По оператору',
                    },
                    {
                        value: 'courier',
                        label: 'По курьеру',
                    },
                    {
                        value: 'partner',
                        label: 'По заведению',
                    },
                ],
                sort: '',
                selectedOrderId: null,
                selectedCourier: null,
                query: '',
                filterLoading: false,
                selectedStatusIndex: 0,
                statusTypes: [
                    {
                        type: 'all',
                        label: 'Все',
                        color: 'rgba(255, 190, 23, 1)',
                    },
                    {
                        type: 'order',
                        label: 'Заказ',
                        color: 'rgba(225, 134, 255, 1)',
                    },
                    {
                        type: 'process',
                        label: 'Процесс',
                        color: 'rgba(16, 165, 31, 1)',
                    },
                    {
                        type: 'courier',
                        label: 'Курьер',
                        color: 'rgba(165, 87, 16, 1)',
                    },
                    {
                        type: 'cancel',
                        label: 'Отмена',
                        color: 'rgba(223, 0, 0, 1)',
                    }
                ],
                pollingPaused: false,
            }
        },

        computed: {
            statusButtons() {
                return this.statusTypes.map(type => ({
                    ...type,
                    statuses: [
                        ...Object.keys(this.statuses).map(status => ({
                            label: this.statuses[status],
                            value: status,
                            color: this.statusesItems[status].color,
                            type: this.statusesItems[status].type,
                        }))
                    ].filter((status) => {
                        if(status.type === type.type) return status

                    })

                }))
            },
            ordersList() {
                let orders = []
                this.orders.data.forEach(order => {
                    order.attributes['color'] = this.statusesItems[order.attributes.status].tableColor
                    order.attributes['borderColor'] = this.statusesItems[order.attributes.status].color

                    if (order.attributes['requestedToCancel']) {
                        order.attributes['color'] = this.statusesItems['canceled-by-courier'].tableColor
                        order.attributes['borderColor'] = this.statusesItems['canceled-by-courier'].color
                    }

                    if (order.attributes.status === 'new' && order.attributes.deliveryType === 'pre-order') {
                        order.attributes['color'] = this.statusesItems['new_pre-order'].tableColor
                        order.attributes['borderColor'] = this.statusesItems['new_pre-order'].color
                    }
                })

                return orders = this.orders
            }
        },
        created() {
            this.addOrderColors()
        },
        mounted() {
            this.selectedSortOption = this.status || this.sortOptions[0]
            this.selectedStatus = this.status
            this.query = this.search
            this.$firebase.firebase.messaging().onMessage(payload => {
                if (location.pathname === '/orders' && payload.data.type === 'order-created') {
                    this.$inertia.reload()
                    new Audio('sounds/notification.mp3').play()
                } else {
                    this.$firebase.onMessaging(payload)

                    if (payload.data.type === 'preorder-reminder') {
                        this.$inertia.reload()
                    }
                }
            })
            if (this.status) {
                this.selectType(this.statusesItems[this.status].type)
            }

            this.pollOrders()
        },
        methods: {
            addOrderColors() {
                this.orders.data.forEach(order => {
                    if (order.attributes['requestedToCancel']) {
                        order.attributes['color'] = this.statusesItems['canceled-by-courier'].tableColor
                    } else {
                        order.attributes['color'] = this.statusesItems[order.attributes.status].tableColor
                    }
                })
            },
            selectSort(sort) {
                this.selectedSortOption = sort
            },
            orderSelected(id) {
                this.selectedOrderId = id
            },
            async transferOrderToPartner() {
                await this.$inertia.post(`orders/${this.selectedOrderId}/transfer-to-partners`)
                    .then(() => {
                        this.selectedOrderId = null
                        this.$modal.close('orderAction')
                        this.$modal.open('orderTransferred')
                    })
            },
            handleActions() {
                switch (this.selectedAction) {
                    case 'Передать в кафе':
                        this.transferOrderToPartner()
                        break
                    case 'Назначить курьера':
                        this.orderAssignCourier()
                        break
                    case 'Отменить':
                        this.$modal.close('orderAction')
                        this.$modal.open('orderCancel', { orderId: this.selectedOrderId })
                        break
                    default:
                        break
                }
            },
            orderAssignCourier() {
                this.$modal.close('orderAction')
                this.$modal.open('orderAssignCourier')
            },
            updateStatus(status) {
                if (status === 'pre-order') {
                    status = 'new_pre-order'
                }

                this.selectedStatus = status
                this.filter()
            },
            filter() {
                this.filterLoading = true

                this.withoutPolling(async () => {
                    await this.$inertia.reload({
                        only: ['orders'],
                        data: {
                            q: this.query,
                            status: this.selectedStatus,
                            from_date: this.moment(this.fromDate).format('YYYY-MM-DD'),
                            to_date: this.moment(this.toDate).format('YYYY-MM-DD'),
                            source: this.selectedSource.value,
                        }
                    })

                    this.filterLoading = false
                })
            },
            clearFilter() {
                this.$inertia.visit('/orders')
            },
            selectType(type, status) {
                this.statusTypes.forEach((item, index) => {
                    if(type === item.type) {
                        this.selectedStatusIndex = index
                    }
                })
                if(status) {
                    if(status.statuses.length) {
                        this.updateStatus(status.statuses[0].value)
                    }else {
                        this.updateStatus('')
                    }
                }
            },
            pollOrders() {
                const interval = setInterval(() => {
                    if (!this.pollingPaused && !this.query && !this.filterLoading) {
                        this.$inertia.reload({
                            only: ['orders'],
                        })
                    }
                }, 5000)

                Inertia.on('before', (event) => {
                    if (event.detail.visit.url.pathname !== '/orders') {
                        clearInterval(interval)
                    }
                })
            },
            withoutPolling(fn) {
                this.pollingPaused = true

                fn()

                setTimeout(() => {
                    this.pollingPaused = false
                }, 1000)
            }
        },
        watch: {
            fromDate() {
                this.filter()
            },
            toDate() {
                this.filter()
            },
        }
    }
</script>
<style scoped>
    .orders {
        height: calc(100vh - 76px);
        overflow-y: scroll;
    }
    .orders::-webkit-scrollbar {
        width: 4px;
        background: #E4E4E4;
    }
    .orders::-webkit-scrollbar-thumb {
        background: #A7A7A7;
    }
</style>
