<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1 list">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Дата</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Номер счета</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Владелец</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Пополнение на</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Система</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Статус</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Действия</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list"></th>
            </tr>
            <tr class="cursor-pointer hover:bg-gray-100">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2">1</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3">
                    <span class="flex flex-col">
                        <span>14.02.21</span>
                        <span>14:00</span>
                    </span> 
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3">0000 0000</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3">
                    <span class="flex flex-col">
                        <span>Фаиза</span>
                        <span>Южный</span>
                    </span>     
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3">500 сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3">Optima24</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3">
                    <span class="flex flex-col">  
                        <span class="text-sm text-yellow-400">Поступило</span>
                        <span class="text-sm">Оператор Иванов Д</span>
                    </span>
                </td>
                <td class="text-sm text-left text-yellow-400 border-b border-solid border-gray-150 leading-none py-3">
                    <button @click="$modal.open('allowReplenishment')">Разрешить</button>
                </td>
                <td class="text-sm text-left text-red-400 border-b border-solid border-gray-150 leading-none py-3">
                    <button @click="$modal.open('rejectReplenishment')">Отклонить</button>
                </td>
            </tr>
        </table>
    </div>
</template>

<style scoped>
.list:nth-child(1) {
    width: 3%;
}
.list {
    width: 12%;
}
</style>