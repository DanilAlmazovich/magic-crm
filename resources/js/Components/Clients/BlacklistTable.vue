<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Клиент</th>
<!--                <th class="text-sm text-left text-gray-200 list pr-1">Адрес</th>-->
                <th class="text-sm text-left text-gray-200 list pr-1">Причина блокировки</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1">Оператор</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Дата</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr class="cursor-pointer hover:bg-gray-100" v-if="blacklists.data.length > 0" v-for="(item, idx) in blacklists.data" :key="idx">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2 list">1</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    <span class="flex flex-col">
                        <span>{{ item.name }}</span>
                        <span>{{ item.phone }}</span>
                    </span>
                </td>
<!--                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">-->
<!--                    <span class="flex flex-col">-->
<!--                        <span>-</span>-->
<!--                    </span>-->
<!--                </td>-->
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">{{ item.reason }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list"></td>
                <td class="text-sm text-left text-пкфн-900 border-b border-solid border-gray-150 leading-none py-6 list">{{ roles[item.creator.role] }}</td>
                <td class="text-sm text-left text-пкфн-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    <span class="flex flex-col">
                        <span>{{ moment(item.created_at).format('Y-m-d') }}</span>
                        <span>{{ moment(item.created_at).format('HH:m') }}</span>
                    </span>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    <div class="flex justify-center items-center">
                        <button class="w-5 h-5" @click="destroy(item.id)">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
import moment from 'moment'

export default {
    name: 'BlacklistTable',

    props: {
        blacklists: {
            type: Object,
            default: () => ({})
        },
        roles: {
            type: Object,
            default: () => ({})
        }
    },
    methods: {
        destroy(id) {
            this.$inertia.delete(`/blacklist/${id}`)
        }
    }
}
</script>

<style scoped>
.list {
    width: 12%;
}
.list:nth-child(1) {
    width: 4%;
}
.list:nth-child(5) {
    width: 40%;
}
</style>
