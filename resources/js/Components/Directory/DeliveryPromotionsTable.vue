<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Наименование</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Процент снижения</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 pl-2 list">1</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">Стандартный</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">30%</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">
                    <div class="flex justify-end items-center">
                        <button class="mr-3 text-orange-400 flex items-center" @click="$modal.open('editShare')">
                            <icon name="editing" class="w-5 h-5 fill-current text-orange-400 mr-2"></icon>
                            <span>Редактировать</span>
                        </button>
                        <button class="w-5 h-5">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>
<style scoped>
.list {
    width: 20%;
}
.list:nth-child(1) {
    width: 4%;
}
</style>