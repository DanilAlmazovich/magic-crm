<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Дата</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Курьер</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Сумма</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Оператор</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Причина</th>
            </tr>
            <tr class="cursor-pointer hover:bg-gray-100">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2 list">1</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    14.02.21
                    14:00
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    Евгений Онегин
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">1500 сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    Алиса Андреевна
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</td>
            </tr>
        </table>
    </div>
</template>

<style scoped>
.list:nth-child(1) {
    width: 3%;
}
</style>