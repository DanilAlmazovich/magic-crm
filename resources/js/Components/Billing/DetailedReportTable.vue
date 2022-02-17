<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    Оператор
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col leading-none">
                        <span>Заказ</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    Клиент
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    Забрали
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    Доставили
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">Курьер</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Доставка</th>
                <th class="text-sm text-left text-gray-200 list pr-1">В офис</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Сумма</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Оплата</th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    Примечание
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">Действия</th>
            </tr>
            <template v-for="operator in operators">
                <tr v-for="(order, index) in operator.relationships.orders"
                    :key="`detailed-report-operator-${operator.id}-order-${order.id}`"
                    class="cursor-pointer hover:bg-gray-100"
                    @click="openOrderDetails(order)">
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2">
                        {{ operator.attributes.operator_name }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        <div>
                            № {{ order.id }}
                        </div>
                        <div>
                            от {{ getFormattedDate(order.created_at) }} {{ getFormattedTime(order.created_at) }}
                        </div>
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        <div>{{ order.client ? order.client.name : null }}</div>
                        <div>{{ order.client ? order.client.phone : null }}</div>
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ getFirstPickupAddress(order) }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ getLastDeliveryAddress(order) }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ order.courier? order.courier.full_name : null }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ order.delivery_cost }} сом
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ order.sum_to_office }} сом
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ order.amount }} сом
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ order.payment_type === 'online' ? 'online' : null }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        <collapse class="w-86" :initialHeight=height>
                            <p>{{ order.comment }}</p>
                            <template v-slot:trigger="slotProps">
                                <div class="mb-1">
                                    <span v-if="!slotProps.expanded" class="text-base text-orange-400">Развернуть</span>
                                    <span v-else class="text-base text-orange-400">Свернуть</span>
                                </div>
                            </template>
                        </collapse>
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        <button class="text-orange-400"
                                @click.stop="$refs.refStatusesListModal.open(order.id)">
                            Открыть историю статусов
                        </button>
                    </td>
                </tr>
                <tr class="cursor-pointer hover:bg-gray-100">
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2 font-medium">Итого</td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 font-medium">
                        {{ operator.attributes.total_delivery_cost }} сом
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 font-medium">
                        {{ operator.attributes.total_sum_to_office }} сом
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 font-medium">
                        {{ operator.attributes.total_amount }} сом
                    </td>
                </tr>
            </template>
        </table>
        <statuses-list-modal ref="refStatusesListModal" />
    </div>
</template>
<script>
import Collapse from '@/Components/Collapse'
import DateTimeHelpers from './DateTimeHelpersMixin'
import StatusesListModal from "../Orders/StatusesListModal";

export default {
    props: {
        operators: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            height: 40
        }
    },
    methods: {
        getFirstPickupAddress(order) {
            const addresses = order.addresses
            if (! Array.isArray(addresses) || addresses.length === 0) {
                return null
            }

            const pickupAddresses = addresses.filter(address => address.type === 'pickup')
            const length = pickupAddresses.length

            return length ? pickupAddresses[0].address : null
        },
        getLastDeliveryAddress(order) {
            const addresses = order.addresses
            if (! Array.isArray(addresses) || addresses.length === 0) {
                return null
            }

            const deliveryAddresses = addresses.filter(address => address.type === 'delivery')
            const length = deliveryAddresses.length

            return length ? deliveryAddresses[length - 1].address : null
        },
        openOrderDetails(order) {
            window.open(route('orders.show', order.id, '_blank'))
        }
    },
    components: {
        StatusesListModal,
        Collapse
    },
    mixins: [DateTimeHelpers]
}
</script>
<style scoped>
.list:nth-child(1) {
    width: 4%;
}
.list {
    width: 10%;
}
</style>
