<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">Оператор</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Дата</th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col leading-none">
                        <span>Кол-во</span>
                        <span>заказов</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col leading-none">
                        <span>Ставка</span>
                        <span>за день</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col leading-none">
                        <span>Процент</span>
                        <span>с заказов</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col leading-none">
                        <span>Сумма</span>
                        <span>доставки</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col leading-none">
                        <span>Сумма</span>
                        <span>в офис</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col leading-none">
                        <span>Сумма</span>
                        <span>авансов</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col leading-none">
                        <span>Сумма</span>
                        <span>штрафов</span>
                    </span>
                </th>
<!--                <th class="text-sm text-left text-gray-200 list pr-1"></th>-->
<!--                <th class="text-sm text-left text-gray-200 list pr-1"></th>-->
<!--                <th class="text-sm text-left text-gray-200 list pr-1"></th>-->
            </tr>
            <template v-for="(data, key) in operators">
                <tr v-for="(operatorData, index) in data.data"
                    :key="`summary-report-operator-${key}-index-${index}`"
                    class="cursor-pointer hover:bg-gray-100">
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2">
                        {{ operatorData.name }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2">
                        {{ operatorData.date }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ operatorData.orders_count }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ operatorData.rate_per_day }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ operatorData.percentage_of_orders }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ operatorData.delivery_cost }} сом
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ operatorData.sum_to_office }} сом
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ operatorData.amount_of_advances }}
                    </td>
                    <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ operatorData.amount_of_fines }}
                    </td>
                    <!--                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">0</td>-->
                    <!--                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>-->
                    <!--                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>-->
                    <!--                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>-->

                </tr>
                <tr>
                    <td class="font-bold text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"
                        colspan="2">
                        Итого
                    </td>
                    <td class="font-bold text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"
                        colspan="3">
                        {{ data.totals.orders_count }}
                    </td>
                    <td class="font-bold text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ data.totals.delivery_cost }}
                    </td>
                    <td class="font-bold text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                        {{ data.totals.sum_to_office }}
                    </td>
                    <td class="font-bold text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    </td>
                    <td class="font-bold text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    </td>
                </tr>
            </template>
            <tr>
                <td class="font-bold text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"
                    colspan="2">
                    Всего:
                </td>
                <td class="font-bold text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"
                    colspan="3">
                    {{ totalOrdersCount }}
                </td>
                <td class="font-bold text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ totalDeliveryCost }}
                </td>
                <td class="font-bold text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    {{ totalSumToOffice }}
                </td>
            </tr>
        </table>
    </div>
</template>

<script>

export default {
    props: {
        operators: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        totalOrdersCount() {
            return this.totals.ordersCount
        },
        totalDeliveryCost() {
            return this.totals.deliveryCost
        },
        totalSumToOffice() {
            return this.totals.sumToOffice
        },
        totals() {
            return this.operators.reduce((carry, operator) => {
                return {
                    deliveryCost: (carry.deliveryCost || 0) + operator.totals.delivery_cost,
                    ordersCount: (carry.ordersCount || 0) + operator.totals.orders_count,
                    sumToOffice: (carry.sumToOffice || 0) + operator.totals.sum_to_office
                }
            }, {})
        }
    }
}
</script>

<style scoped>
.list {
    width: 10%;
}
</style>
