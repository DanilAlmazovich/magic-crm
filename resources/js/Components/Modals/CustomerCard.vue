<template>
    <modal class="clients" name="customerCard" close-text>
        <template v-slot="{ data: { client } }">
            <div class="-mt-6">
                <div class="flex items-center mb-5">
                    <h2 class="text-xl text-gray-900 font-medium mr-16">Карта клиента</h2>
                </div>
                <div class="flex pb-6">
                    <div class="w-4/12 mr-2 flex flex-col">
                        <label class="text-gray-900 text-xl font-medium mb-3">Данные клиента</label>
                        <input type="text"
                               :value="client.attributes.name"
                               placeholder="ФИ"
                               :disabled="true"
                               class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                                        focus:border-gray-150 focus:outline-none text-gray-900">
                    </div>
                    <div class="w-4/12 mr-2 flex flex-col pt-6">
                        <div class="mb-4"></div>
                        <div class="relative">
                            <the-mask class="px-3 py-2 w-full focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                      :value="client.attributes.phone"
                                      :disabled="true"
                                      mask="+###############"
                                      placeholder="Номер телефона"/>
                            <div class="flex items-center justify-center h-full w-10 absolute top-0 right-0">
                                <icon name="phone" class="w-4 h-4 block fill-current text-orange-400"/>
                            </div>
                        </div>
                    </div>
                </div>
                <template v-if="client.attributes.phones.length">
                    <h4 class="text-gray-900 text-xl font-medium mb-3">Доп номера</h4>
                    <div class="flex pb-8">
                        <div class="w-4/12 mr-2 flex flex-col" v-for="phone in client.attributes.phones">
                            <div class="relative">
                                <the-mask class="px-3 py-2 w-full focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                          :value="phone"
                                          :disabled="true"
                                          mask="+###############"
                                          placeholder="Номер телефона"/>
                                <div class="flex items-center justify-center h-full w-10 absolute top-0 right-0">
                                    <icon name="phone" class="w-4 h-4 block fill-current text-orange-400"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-if="client.relationships.orders.length">
                    <div class="flex items-center mb-5">
                        <h2 class="text-lg text-gray-900 font-medium">Список заказов</h2>
                    </div>
                    <div class="pb-8">
                        <table class="w-full">
                            <tr>
                                <th class="title"></th>
                                <th class="text-base text-left text-gray-200 pr-1">Номер</th>
                                <th class="text-base text-left text-gray-200 pr-1">Дата</th>
                                <th class="text-base text-left text-gray-200 pr-1">Статус</th>
                                <!--                                            <th class="text-base text-left text-gray-200 pr-1">Оценка клиента</th>-->
                                <th class="text-base text-left text-gray-200 pr-1"></th>
                            </tr>
                            <tr v-for="(order, index) in client.relationships.orders">
                                <td class="border-l-2 border-orange-400"></td>
                                <td class="text-lg text-left text-gray-900 leading-none pl-2 py-3 border-b border-gray-150">{{ index + 1 }}</td>
                                <td class="text-lg text-left text-gray-900 border-b border-gray-150 leading-none py-3">
                                    {{ moment(order.createdAt).format('HH:mm DD.MM.YYYY') }}
                                </td>
                                <td class="text-lg text-left text-green-700 border-b border-gray-150 leading-none py-3">
                                    {{ orderStatusTypes[order.status] }}
                                </td>
                                <!--                                            <td class="text-base text-left text-gray-900 border-b border-gray-150 leading-none py-3">-->
                                <!--                                                <icon name="star" class="dropdown-icon w-4 h-4 h-auto inline-block fill-current text-orange-400"></icon>-->
                                <!--                                                <span class="text-base text-gray-900 font-medium">4,9</span>-->
                                <!--                                            </td>-->
                                <td class="text-base text-orange-400 border-b border-gray-150 leading-none py-3">
                                    <div class="flex justify-end">
                                        <inertia-link :href="`orders/${order.id}`">Подробнее</inertia-link>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </template>
            </div>
        </template>
    </modal>
</template>
<script>
export default {
    props: {
        orderStatusTypes: Object,
    }
}
</script>
