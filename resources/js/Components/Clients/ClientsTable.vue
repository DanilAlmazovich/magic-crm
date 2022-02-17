<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1 list">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">ФИ</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Номер телефона</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Адреса клиента</th>
                <th class="text-sm text-left text-gray-200 list pr-1 list">Кол-во заказов</th>
<!--                <th class="text-sm text-left text-gray-200 list pr-1 list">Статус</th>-->
                <th class="text-sm text-left text-gray-200 list pr-1 list"></th>
                <th class="text-sm text-left text-gray-200 list pr-1 list"></th>
                <th class="text-sm text-left text-gray-200 list pr-1 list"></th>
            </tr>
            <tr class="cursor-pointer hover:bg-gray-100" v-for="(client, index) in clients.data">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2 ">{{ index + 1 }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">{{ client.attributes.name }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">{{ client.attributes.phone }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">
                    <span class="flex flex-col">
                        <template v-if="orderAddresses(client).length">
                            <span v-for="address in orderAddresses(client)">{{ address.attributes.address }};</span>
                        </template>
                        <template v-else>-</template>
                    </span>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">{{ client.attributes.ordersCount }}</td>
<!--                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6">В черном спике</td>-->
                <td class="text-sm text-left text-orange-400 border-b border-solid border-gray-150 leading-none py-6"></td>
                <td class="text-sm text-left text-orange-400 border-b border-solid border-gray-150 leading-none py-6"
                        @click="$modal.open('customerCard', { client })">
                    Посмотреть карту клиента
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    <div class="flex justify-center items-center">
<!--                        <button class="w-5 h-5 mr-3" @click="$modal.open('editingPayment')">-->
<!--                            <icon name="editing" class="h-auto fill-current text-orange-400 mr-2"></icon>-->
<!--                        </button>-->
<!--                        <button class="w-5 h-5">-->
<!--                            <icon name="remove" class="h-auto fill-current mr-2"></icon>-->
<!--                        </button>-->
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            clients: Object,
        },
        methods: {
            orderAddresses(client) {
                let addresses = []
                client.relationships.orders.forEach(order => {
                    order.orderAddresses.forEach(orderAddress => {
                        if(orderAddress.attributes.type === 'delivery') {
                            addresses.push(orderAddress)
                        }
                    })
                })
                return addresses
            },
            getAddress(data) {
                let { name, address } = data.attributes

                if (name) {
                    name += ': '
                }

                return `${name}${address}`
            }
        }
    }
</script>

<style scoped>
.list {
    width: 12%;
}
.list:nth-child(8) {
    width: 20%;
}
.list:nth-child(1) {
    width: 4%;
}
</style>
