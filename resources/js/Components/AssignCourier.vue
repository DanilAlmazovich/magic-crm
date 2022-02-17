<template>
    <div>
        <div class="flex justify-between pt-6 mb-4">
            <div class="pt-2">
                <h1 class="text-3xl font-semibold text-gray-900">Список заказов</h1>
            </div>
            <div class="flex">
                <div class="flex items-center mr-8">
                    <span class="mr-4 text-gray-900">Сортировка:</span>
                    <div class="flex items-center">
                        <select class="border-none py-1 pl-0 leading-none outline-none"
                                v-model="sort">
                            <option value="">Все</option>
                            <option :value="option.value" v-for="option in sortOptions">{{ option.label }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex pl-1">
                    <input type="text" class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100" placeholder="Поиск заказов">
                    <button class="bg-orange-400 text-white py-3 px-7 rounded-r-md">Найти</button>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end py-3">
            <div class="flex mr-8 items-center">
                <span class="mr-2 leading-tight">Дата с</span>
                <v-date-picker v-model="calendarValue" mode="date" is24hr ref="datepicker">
                    <template v-slot="{ inputValue, togglePopover, inputEvents }">
                        <div class="flex items-center" v-on="inputEvents">
                            <input class="leading-tight w-20"
                                   :value="inputValue" />
                            <icon name="calendar" class="w-4 h-4 flex fill-current text-orange-400"></icon>
                        </div>
                    </template>
                </v-date-picker>
            </div>
            <div class="flex items-center">
                <span class="mr-2 leading-tight">Дата по</span>
                <v-date-picker class="mr-4" v-model="calendarValue" mode="date" is24hr ref="datepicker">
                    <template v-slot="{ inputValue, togglePopover, inputEvents }">
                        <div class="flex items-center" v-on="inputEvents">
                            <input class="leading-tight w-20"
                                   :value="inputValue" />
                            <icon name="calendar" class="w-4 h-4 flex fill-current text-orange-400"></icon>
                        </div>
                    </template>
                </v-date-picker>
            </div>
        </div>
        <div class="flex items-center mb-6">
            <icon class="mr-3 w-4 h-4" name="question"></icon>
            <span class="text-sm text-gray-900">Для подробной информации нажмите на строку необходимого заказа</span>
        </div>
        <div class="flex items-center flex-wrap mb-4">
            <button class="py-1 px-3 rounded-lg mr-3 mb-2 text-sm border border-solid"
                    :style="`color: ${status.color}; border-color: ${status.value === selectedSortButton ? status.color : '#BFBFBF'}` "
                    :class="{'text-gray-900': status.value === selectedSortButton}"
                    v-for="status in statusButtons"
                    @click="selectSortButton(status.value)">
                {{ status.label }}
            </button>
        </div>
        <div class="mb-4">
            <order-table @select-order="orderSelected" v-if="orders.data.length"
                         :orders="ordersList"
                         :payment-types="paymentTypes"
                         :couriers="couriers"
                         assign-courier
            />
            <p class="bg-yellow-400 text-gray-800 py-3 px-5" v-else>Нет заказов.</p>
        </div>
        <div class="flex justify-end mb-2">
            <div class="flex">
                <button class="w-10 h-10 flex items-center justify-center border border-solid border-gray-400 rounded-l-md"><icon class="w-3 h-3 fill-current flex transform -rotate-90" name="arrow"/></button>
                <button class="w-10 h-10 flex items-center justify-center border border-solid border-gray-400 border-r-none" v-for="n in 10"><span>{{ n }}</span></button>
                <button class="w-10 h-10 flex items-center justify-center border border-solid border-gray-400 rounded-r-md"><icon class="w-3 h-3 fill-current flex transform rotate-90" name="arrow"/></button>

            </div>
        </div>
    </div>
</template>

<script>
    import OrderTable from '@/Components/OrderTable'
    import VDatePicker from 'v-calendar/lib/components/date-picker.umd'

    export default {
        components: {
            OrderTable,
            VDatePicker
        },

//        props: {
//            orders: Object,
//            paymentTypes: Object,
//            couriers: Array,
//            statuses: Object,
//        },

        data() {
            return {
                statusColors: {
                    'new': 'rgb(218, 219, 46)',
                    'accepted': 'rgb(69, 204, 231)',
                    'transferred-to-cafe': 'rgb(66, 158, 189)',
                    'is-preparing': 'rgb(242, 127, 12)',
                    'is-ready': 'rgb(21, 153, 71)',
                    'courier-assigned': 'rgb(39, 65, 146)',
                    'courier-accepted': 'rgb(0, 121, 46)',
                    'in-way-to-client': 'rgb(192, 81, 0)',
                    'delivered': 'rgb(153, 211, 27)',
                    'canceled-by-client': 'rgb(171, 0, 42)',
                    'canceled-by-establishment': 'rgb(236, 50, 50)',
                    'canceled-by-operator': 'rgb(235, 62, 27)',
                    'canceled-by-partner': 'rgb(235, 62, 27)',
                    'canceled-by-courier': 'rgb(221, 0, 40)',
                    'courier-took': 'rgb(74, 224, 207)',
                    'courier-completed-order': 'rgb(73, 206, 109)',
                    'draft': 'rgb(255, 190, 23)', //нужно добвить цвет
                    'pre-order': 'rgb(255, 190, 23)', //нужно добвить цвет
                    'closed': 'rgb(255, 190, 23)', //нужно добвить цвет
                    'removed': 'rgb(255, 190, 23)', //нужно добвить цвет
                    'for-realization': 'rgb(255, 190, 23)', //нужно добвить цвет
                    'completed': 'rgb(255, 190, 23)', //нужно добвить цвет
                    'canceled': 'rgb(255, 190, 23)', //нужно добвить цвет
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
                selectedSortButton: '',
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
            }
        },

        computed: {
            statusButtons() {
                return [
                    {
                        label: 'Все',
                        value: 'all',
                        color: 'rgb(255, 190, 23)'
                    },
                    ...Object.keys(this.statuses).map(status => ({
                        label: this.statuses[status],
                        value: status,
                        color: this.statusColors[status]
                    }))
                ]
            },
            ordersList() {
                return {
                    data: this.orders.data.map(order => ({
                        ...order,
                        attributes: {
                            ...order.attributes,
                            color: this.statusColors[order.attributes.status]
                        },
                }))
                }
            }
        },

        created() {
            this.addOrderColors()
        },

        methods: {
            addOrderColors() {
                this.orders.data.forEach(order => {
                    order.attributes['color'] = this.statusColors[order.attributes.status]
                })
            },
            selectSort(sort) {
                this.selectedSortOption = sort
            },
            selectSortButton(sort) {
                this.selectedSortButton = sort
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
                    default:
                        break
                }
            }
        }
    }
</script>
