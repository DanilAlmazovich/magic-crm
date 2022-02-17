<template>
    <div class="w-full overflow-x-auto" id="order-list">
        <table class="w-full border-separate table">
            <tr class="border-l-2 border-solid border-white">
                <th class="text-sm text-gray-200 text-left font-normal pr-3 py-1 pl-2">Номер</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">Клиент</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">Заведение</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">Откуда</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">Куда</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">Сумма</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">Оплата</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">Доставка</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">В офис</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">Оператор</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1 pl-3">Курьер</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">Дата</th>
                <th class="text-sm text-gray-200 text-left font-normal pr-2 py-1">Действие</th>
            </tr>
            <tbody v-for="(order, index) in ordersItems">
                <tr :style="`background: ${order.attributes.color}`">
                    <td class="cursor-pointer text-sm text-left text-gray-900 font-bold leading-none pr-3 py-2 pl-2 border-white border-l-2 border-solid"
                        :style="`border-color: ${order.attributes.borderColor}`"
                        @click.prevent="$inertia.visit(`orders/${order.id}`)">{{ order.id }}</td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">
                        <span>{{ order.relationships.client.attributes.name }}</span><br><span class="whitespace-nowrap">{{ phone(order) }}</span>
                    </td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">{{ order.relationships.branch ? order.relationships.branch.relationships.partner.full_name : pickupAddressType(order) }}</td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">{{ pickupAddress(order) }}</td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">{{ deliveryAddress(order) }}</td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">{{ order.attributes.price }} сом</td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">{{ paymentTypes[order.attributes.paymentType] }}</td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">{{ order.attributes.deliveryCost }} сом</td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">{{ order.attributes.sumToOffice }} сом</td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">
                        {{ order.relationships.operator ? order.relationships.operator.attributes.name : '-' }}
                    </td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">
                        <template v-if="order.relationships.courier">
                            {{ order.relationships.courier.attributes.fullName }}
                            <br>
                            {{ order.relationships.courier.attributes.phone }}
                        </template>
                        <span v-else>-</span>
                    </td>
                    <td class="text-sm text-left text-gray-900 font-bold leading-none pr-2 py-2 border-white">
                        <div>{{ moment(order.attributes.createdAt).format('HH:mm DD.MM.YYYY') }}</div>
                        <div v-if="order.attributes.deliveryType === 'pre-order'">на {{ moment(order.attributes.deliveryTime).format('HH:mm') }}</div>
                    </td>
                    <td class="text-sm text-left text-orange-400 font-semibold leading-none pr-2 py-2 border-white font-medium"><button class="font-semibold" @click.stop="handleActions(order.id)">Действие</button></td>
                </tr>
                <tr class="bg-gray-150 line"><td v-for="n in 13"></td></tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            orders: Object,
            paymentTypes: Object,
            pickupAddressTypes: Object,
            statuses: Array,
            status: String,
        },

        computed: {
            ordersItems() {
                return this.orders.data.map(order => ({
                    ...order,
                    attributes: {
                        ...order.attributes,
                        color: this.getOrderColor(order),
                        borderColor: this.getOrderBorderColor(order),
                    }
                }))
            }
        },

        data() {
            return {
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
            }
        },

        methods: {
            pickupAddress(order) {
                if(order.relationships.branch) {
                    return order.relationships.branch.attributes.address
                }

                let pickupAddress = order.relationships.addresses.find(address => address.attributes.type === 'pickup')
                if(pickupAddress) {
                    return pickupAddress.attributes.address
                }
            },
            pickupAddressType(order) {
                let pickupAddress = order.relationships.addresses.find(address => address.attributes.type === 'pickup')
                if(pickupAddress) {
                    return this.pickupAddressTypes[pickupAddress.attributes.pickupAddressType]
                }
            },
            deliveryAddress(order) {
                let deliveryAddress = order.relationships.addresses.find(address => address.attributes.type === 'delivery')
                return deliveryAddress ? deliveryAddress.attributes.address : ''
            },
            phone(order) {
                return '+' + order.relationships.client.attributes.phone
            },
            establishment(branch) {
                return branch ? branch.attributes.name : '-'
            },
            addresses(order) {
                return new Array(1)
                    .fill({ attributes: { address: '' }})
                    .map((address, i) => {
                        return order.relationships.addresses[i] || address
                    })
            },
            handleActions(orderId) {
                this.$emit('select-order', orderId)
                this.$modal.open('orderAction')
            },
            getOrderColor(order) {
                if (order.attributes.status !== 'new' || order.attributes.deliveryType === 'pre-order') {
                    return order.attributes.color
                }

                if (order.attributes.paymentType === 'online' && !order.attributes.isPaid) {
                    return 'rgba(236, 236, 236, 1)'
                }

                if (order.attributes.source === 'website') {
                    return 'rgba(24, 103, 255, .2)'
                }

                return order.attributes.color
            },
            getOrderBorderColor(order) {
                if (order.attributes.status !== 'new' || order.attributes.deliveryType === 'pre-order') {
                    return order.attributes.color
                }

                if (order.attributes.paymentType === 'online' && !order.attributes.isPaid) {
                    return 'rgba(174, 174, 174, 1)'
                }

                if (order.attributes.source === 'website') {
                    return 'rgba(24, 103, 255, 1)'
                }

                return order.attributes.borderColor
            },
        },
    }
</script>
<style>
    #order-list::-webkit-scrollbar {
        height: 8px;
        background: #E4E4E4;
    }
    #order-list::-webkit-scrollbar-thumb {
        background-color: #A7A7A7;
    }
    #order-list .table tbody:last-child .line {
        background: transparent;
    }
    #order-list .table {
        border-spacing: 0 20px;
    }
    #order-list .multiselect {
        background: transparent;
    }
    #order-list .multiselect__select {
        display: none;
    }
    #order-list .multiselect__select {
        display: none;
    }
    #order-list .multiselect__tags {
        padding-right: 0;
        background: transparent;
    }
    #order-list .multiselect__tags .multiselect__single {
        font-size: 0.875rem;
        background-color: transparent;
    }
</style>
