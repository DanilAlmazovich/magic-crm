<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Дата</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Партнер</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Филиал</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Продукт</th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>Кол-во</span>
                        <span>продукта</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>Стоимость</span>
                        <span>продукта</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1">
                    <span class="flex flex-col">
                        <span>Итоговая сумма</span>
                        <span>по продукту</span>
                    </span>
                </th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr class="cursor-pointer hover:bg-gray-100">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2">1</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    <span class="flex flex-col">
                        <span>14.02.21</span>
                        <span>14:00</span>
                    </span>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">Фаиза</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">Фаиза Южный</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">Картошка</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">х20</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">50 сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">1000 сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6"></td>
            </tr>
        </table>
    </div>
</template>
<style scoped>
.list:nth-child(1) {
    width: 3%;
}
.list {
    width: 10%;
}
</style>