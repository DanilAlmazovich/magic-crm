<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Дата</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Тип операции</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Сумма</th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>№ кошелька</span>
                        <span>источника</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>№ кошелька</span>
                        <span>бенефициар</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">Дата с</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Дата по</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Оператор</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Статус</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr v-for="operation in operations" :key="`operation.${operation.id}`" class="cursor-pointer hover:bg-gray-100">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2">
                    {{ operation.id }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    <span class="flex flex-col">
                        <span>{{ getFormattedDate(operation.attributes.date) }}</span>
                        <span>{{ getFormattedTime(operation.attributes.date) }}</span>
                    </span>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ operation.attributes.typeLabel }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ operation.attributes.amount }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ operation.attributes.sender_wallet_number }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ operation.attributes.receiver_wallet_number }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    <span class="flex flex-col">
                        <span>{{ getFormattedDate(operation.attributes.dateFrom) }}</span>
                        <span>{{ getFormattedTime(operation.attributes.dateFrom) }}</span>
                    </span>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    <span class="flex flex-col">
                        <span>{{ getFormattedDate(operation.attributes.dateTo) }}</span>
                        <span>{{ getFormattedTime(operation.attributes.dateTo) }}</span>
                    </span>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ operation.relationships.user.name }}
                </td>
                <td class="text-sm text-left border-b border-solid border-gray-150 leading-none py-6"
                    :class="getStatusColorClass(operation)"
                    >
                    {{ operation.attributes.statusLabel }}
                </td>
                <td class="text-sm text-left text-orange-400 border-b border-solid border-gray-150 leading-none py-6"></td>
                <td class="text-sm text-left border-b border-solid border-gray-150 leading-none py-6">
                    <button v-if="operation.attributes.status === 'completed'" class="text-red-400" @click="$emit('cancellation', operation)">
                        Отменить
                    </button>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
import DateTimeHelpersMixin from './DateTimeHelpersMixin'

export default {
    props: {
        operations: {
            type: Array,
            default: () => []
        }
    },
    computed: {
    },
    methods: {
        getStatusColorClass(operation) {
            return operation.attributes.status === 'completed' ? 'text-green-700' : 'text-red-400'
        }
    },
    mixins: [DateTimeHelpersMixin]
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
