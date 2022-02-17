<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Дата</th>
                <th class="text-sm text-left text-gray-200 list pr-1">№ заказа</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Кол-во заказов</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Филиал</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Курьер</th>
                <th class="text-sm text-left text-gray-200 list pr-1">S заказа</th>
                <th class="text-sm text-left text-gray-200 list pr-1">S доставки</th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>S комиссии</span>
                        <span>заведения</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>S процента</span>
                        <span>заведения</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">S в офис</th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>Доплата</span>
                        <span>курьеру</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>Комиссия</span>
                        <span>курьера</span>
                    </span>
                </th>
            </tr>
            <tr class="cursor-pointer hover:bg-gray-100">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2 list">1</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    14.02.21
                    14:00
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">21</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    Алиса
                    Андреева
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    <span class="flex flex-col">
                        <span>Фаиза</span>
                        <span>Южный</span>
                    </span>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    Евгений
                    Онегин
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">150 сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">150 сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">150 сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">150 сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">150 сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">150 сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">150 сом</td>
            </tr>
        </table>
    </div>
</template>

<style scoped>
.list:nth-child(1) {
    width: 3%;
}
</style>