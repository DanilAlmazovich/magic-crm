<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">ФИО</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Тип</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Логин</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr v-for="(operator, index) in operators" :key="operator.id">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2 list">{{ index + 1 }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">{{ operator.name }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">{{ roles[operator.role] }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">{{ operator.email }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    <div
                         class="flex justify-center items-center">
                        <button class="w-5 h-5 mr-3" @click="$emit('edit', operator)">
                            <icon name="editing" class="h-auto fill-current text-orange-400 mr-2"></icon>
                        </button>
                        <button class="w-5 h-5" @click="$emit('delete', operator)">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>
<script>
export default {
    props: ['roles', 'operators']
}
</script>

<style scoped>
.list {
    width: 13%;
}
.list:nth-child(1) {
    width: 4%;
}
</style>
