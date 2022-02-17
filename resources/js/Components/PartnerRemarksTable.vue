<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1 list">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Счет</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Дата</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Курьер</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Филиал</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Замечание</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Статус</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list"></th>
                <th class="text-sm text-left text-gray-200 list pr-1 list"></th>
                <th class="text-sm text-left text-gray-200 list pr-1 list"></th>
            </tr>
            <tr class="cursor-pointer hover:bg-gray-100">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-2 pl-2">1</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-2 pr-2">Штраф</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-2 pr-2">
                    14.02.21
                    14:00
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-2 pr-2">
                    Евгений Онегин
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-2 pr-2">
                    <span class="flex flex-col">
                        <span>Фаиза</span>
                        <span>Южный</span>
                    </span>    
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-2 pr-2">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-2 pr-2">Новое</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-2 pr-2"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-2 pr-2">
                    <button class="text-sm text-yellow-400">Штраф</button>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-2 pr-2">
                    <button class="text-sm text-yellow-400" @click="$modal.open('remarks')">Замечание</button>
                </td>
            </tr>
        </table>
    </div>
</template>

<style scoped>
.list {
    width: 10%;
}
.list:nth-child(1) {
    width: 3%;
}
.list:nth-child(6) {
    width: 25%;
}
.list:nth-child(8) {
    width: 4%;
}
.list:nth-child(9) {
    width: 4%;
}
.list:nth-child(10) {
    width: 4%;
}
</style>