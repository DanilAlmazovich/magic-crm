<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Дата</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Партнер</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Филиал</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Курьер</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Оператор</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Позиции</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Комментарий</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Итого</th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>Комиссия</span>
                        <span>партнера</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>Статус</span>
                        <span>заказа</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">Действия</th>
            </tr>
            <tr v-for="item in items" :key="`partner-report-order-list-${item.id}`"
                @click="openOrderDetail(item.id)"
                class="cursor-pointer hover:bg-gray-100">
                <td
                    class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2">
                        {{ item.attributes.orderId }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    <span class="flex flex-col">
                        <span>{{ getFormattedDate(item.attributes.createdAt) }}</span>
                        <span>{{ getFormattedTime(item.attributes.createdAt) }}</span>
                    </span>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ item.attributes.partner }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ item.attributes.branch }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ item.attributes.courier }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ item.attributes.operator }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    <collapse class="w-86" @toggle='toggle' :initialHeight=height>
                        <ul v-if="item.attributes.items">
                            <li v-for="orderItem in item.attributes.items"
                                :key="`order-item-list-${orderItem.id}`">
                                {{ `${orderItem.attributes.name} x${orderItem.attributes.quantity}` }}
                            </li>
                        </ul>
                        <template v-slot:trigger="slotProps">
                            <div class="mb-1">
                                <span v-if="!slotProps.expanded" class="text-base text-orange-400">Развернуть</span>
                                <span v-else class="text-base text-orange-400">Свернуть</span>
                            </div>
                        </template>
                    </collapse>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ item.attributes.comment }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ item.attributes.total }} сом
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ Number(item.attributes.total * item.attributes.remuneration, 2).toFixed(2) }} сом
                </td>
                <td class="text-sm text-left text-green-700 border-b border-solid border-gray-150 leading-none py-6">
                    {{ item.attributes.statusLabel }}
                </td>
                <td class="text-sm text-left text-green-700 border-b border-solid border-gray-150 leading-none py-6">
                    <a href="#" @click.prevent.stop="openStatusesModal(item.id)">История</a>
                </td>
            </tr>
        </table>
        <statuses-list-modal ref="refStatusesListModal" />
    </div>
</template>
<script>
import Collapse from '@/Components/Collapse'
import DateTimeHelpers from "./DateTimeHelpersMixin";
import StatusesListModal from "../Orders/StatusesListModal";

export default {
    props: {
        items: {
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
        openOrderDetail(orderId) {
            window.open(route('orders.show', orderId, '_blank'))
        },
        openStatusesModal(orderId) {
            this.$refs.refStatusesListModal.open(orderId)
        },
        toggle(expanded) {
            this.expanded = expanded
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
    width: 3%;
}
.list {
    width: 10%;
}
</style>
