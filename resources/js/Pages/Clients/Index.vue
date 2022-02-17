<template>
    <app-layout :name="name" content-width="w-10/12">
        <div id="clients">
            <div class="flex justify-end items-center -mt-16">
                <button type="button" class="pl-4 py-3 rounded-lg border-none flex items-center" @click="$modal.open('addCourier')">
                    <span class="w-3 h-3 transform rotate-45 mr-3">
                        <icon name="cross" class="fill-current text-orange-400"></icon>
                    </span>
                    <span class="leading-none text-orange-400 text-base">Зарегистрировать нового клиента</span>
                </button>
            </div>
            <div class="flex justify-between pt-16 mb-4">
                <div class="pt-2">
                    <h1 class="text-3xl font-semibold text-gray-900">Клиенты</h1>
                </div>
                <div class="flex items-center">
                    <div class="flex pl-1 mr-8">
                        <input type="text" v-model="query"
                               class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100"
                               placeholder="Поиск">
                        <button class="bg-orange-400 text-white py-3 px-7 rounded-r-md" @click="filter">Найти</button>
                    </div>
                    <inertia-link class="text-base text-orange-400" :href="route('clients')">Сбросить поиск</inertia-link>
                </div>
            </div>
            <clients-table :clients="clients" />
            <div class="py-6">
                <pagination v-if="clients.meta.last_page > 1" :meta="clients.meta" />
            </div>
            <div slot="modals">
                <div class="modal">
                    <modal class="clients" name="editingPayment" close-text>
                        <div class="-mt-6">
                            <h2 class="text-xl text-gray-900 font-medium mb-5">Редактировать данные клиента</h2>
                            <div class="flex pb-4">
                                <div class="w-1/4 pr-2 flex flex-col">
                                    <label for="full-name"
                                           class="text-gray-900 text-xl font-medium mb-3">Данные клиента</label>
                                    <input id="full-name" type="text"
                                           placeholder="ФИ"
                                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                                </div>
                                <div class="w-1/4 px-1 flex flex-col pt-6">
                                    <div class="mb-4"></div>
                                    <the-mask class="px-3 py-2 focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                              mask="+###############"
                                              placeholder="Номер телефона"/>
                                </div>
                            </div>
                            <div class="flex pb-4">
                                <div class="w-1/4 pr-2 flex flex-col mr-2">
                                    <label class="text-gray-900 text-xl font-medium mb-3">Доп номера</label>
                                    <the-mask class="px-3 py-2 focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                              mask="+###############"
                                              placeholder="Номер телефона"/>
                                </div>
                                <div class="flex items-end mr-6">
                                    <button
                                        class="border border-solid border-orange-400 p-3 rounded-md flex items-center">
                                        <icon name="plus" class="w-4 h-4 block fill-current text-orange-400"/>
                                    </button>
                                </div>
                                <div class="w-1/4 px-1 flex flex-col pt-6 mr-2">
                                    <div class="mb-4"></div>
                                    <the-mask class="px-3 py-2 focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                              mask="+###############"
                                              placeholder="Номер телефона"/>
                                </div>
                                <div class="flex items-end">
                                    <button
                                        class="border border-solid border-red-400 p-3 rounded-md flex items-center">
                                        <icon name="close" class="w-4 h-4 block fill-current text-red-400"/>
                                    </button>
                                </div>
                            </div>
                            <div class="flex pb-3 relative">
                                <div class="flex w-1/2">
                                    <div class="w-4/12 pr-2 flex flex-col justify-end">
                                        <label class="text-gray-900 text-xl font-medium mb-2 absolute top-0 left-0">Адреса клиента</label>
                                        <input type="text"
                                            placeholder="Тэг"
                                            class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                            focus:border-gray-150 focus:outline-none text-gray-900">
                                    </div>
                                    <div class="w-6/12 flex items-end pr-2">
                                        <div class="flex flex-col w-full relative">
                                            <input class="pl-3 pr-12 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                                type="text"
                                                name="address"
                                                placeholder="Ввод"
                                            >
                                            <div class="w-12 h-full absolute top-0 right-0 flex justify-center items-center">
                                                <icon name="map" class="h-5 w-5 fill-current text-red-400"></icon>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-end mr-5">
                                        <button
                                            class="border border-solid border-orange-400 p-3 rounded-md flex items-center">
                                            <icon name="plus" class="w-4 h-4 block fill-current text-orange-400"/>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex w-1/2">
                                    <div class="w-4/12 flex flex-col justify-end pt-6 pr-2">
                                        <div class="mb-4"></div>
                                        <input type="text"
                                            placeholder="Работа"
                                            class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                            focus:border-gray-150 focus:outline-none text-gray-900">
                                    </div>
                                    <div class="w-6/12 flex items-end pr-2">
                                        <div class="flex flex-col w-full relative">
                                            <input class="pl-3 pr-12 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                                type="text"
                                                name="address"
                                                placeholder="Ввод"
                                            >
                                            <div class="w-12 h-full absolute top-0 right-0 flex justify-center items-center">
                                                <icon name="map" class="h-5 w-5 fill-current text-red-400"></icon>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-end">
                                        <button
                                            class="border border-solid border-red-400 p-3 rounded-md flex items-center">
                                            <icon name="close" class="w-4 h-4 block fill-current text-red-400"/>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7">
                                    Сохранить
                                </button>
                            </div>
                        </div>
                    </modal>

                    <customer-card :order-status-types="orderStatusTypes"/>

                    <modal class="clients" name="order" close-text>
                        <div class="-mt-6">
                            <div class="py-3">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex text-4xl font-semibold">
                                        <span class="text-gray-900 mr-2">1 -</span>
                                        <span class="text-green-700">Доставлен</span>
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <div class="mb-5">
                                        <span class="text-gray-900 text-xl font-medium">Данные клиента</span>
                                    </div>
                                    <div class="pl-12 flex justify-between items-center">
                                        <div class="w-9/12">
                                            <table class="w-full">
                                                <tr>
                                                    <th class="title"></th>
                                                    <th class="text-sm text-left text-gray-200 pb-2 title pr-1">Фамилия Имя</th>
                                                    <th class="text-sm text-left text-gray-200 pb-2 title pr-1">Номер телефона</th>
                                                    <th class="text-sm text-left text-gray-200 pb-2 title pr-1">Куда доставить</th>
                                                </tr>
                                                <tr>
                                                    <td class="border-l-2 border-orange-400"></td>
                                                    <td class="text-lg text-left text-gray-900 leading-none py-4">Алиса Андреева</td>
                                                    <td class="text-lg text-left text-gray-900 leading-none py-4">+996 500 000 000</td>
                                                    <td class="text-lg text-left text-gray-900 leading-none py-4">Чуй 156</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-6">
                                    <div class="mb-4">
                                        <span class="text-gray-900 text-xl font-medium">Состав заказа</span>
                                    </div>
                                    <div class="order pl-12 flex justify-between items-center">
                                        <table class="w-full">
                                            <tr>
                                                <th class="title"></th>
                                                <th class="text-sm text-left text-gray-200 title pb-2 pr-1">Наименование</th>
                                                <th class="text-sm text-left text-gray-200 title pb-2 pr-1">Порция/Шт</th>
                                                <th class="text-sm text-left text-gray-200 title pb-2 pr-1">Кол-во</th>
                                                <th class="text-sm text-left text-gray-200 title pb-2 pr-1">Цена за 1 порцию/1 шт</th>
                                                <th class="text-sm text-left text-gray-200 title pb-2 pr-1">Заведение</th>
                                                <th class="text-sm text-left text-gray-200 title pb-2 pr-1">Итого</th>
                                            </tr>
                                            <tr>
                                                <td class="border-l-2 border-orange-400"></td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">Борщ</td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">1 порция</td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">1</td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">140 сом</td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">Фаиза</td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">140 сом</td>
                                            </tr>
                                            <tr>
                                                <td class="border-l-2 border-orange-400"></td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">Борщ</td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">1 порция</td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">1</td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">140 сом</td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">Фаиза</td>
                                                <td class="text-lg text-left text-gray-900 border-b-2 border-gray-150 leading-none py-4">140 сом</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div>
                                    <div class="mb-5">
                                        <span class="text-gray-900 text-xl font-medium">Отзыв клиента</span>
                                    </div>
                                    <div class="pl-12 flex justify-between items-center">
                                        <div class=" border-l-2 border-orange-400">
                                            <p class="text-lg text-gray-900 leading-none pl-3 py-3">Доставили оперативно, ошибок никаких не было</p>
                                        </div>
                                        <div>
                                            <icon name="star" class="dropdown-icon w-4 h-4 h-auto inline-block fill-current text-orange-400"></icon>
                                            <span class="text-base text-gray-900 font-medium">4,9</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </modal>
                    <modal class="card-search" name="search" close-text>
                        <div class="-mt-6">
                            <h2 class="text-xl text-gray-900 font-medium mb-5 mr-16">Карта клиента</h2>
                            <div class="flex pl-1 mb-5 mr-8">
                                <input type="text"
                                       class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100"
                                       placeholder="Поиск по ФИ/номеру">
                                <button class="bg-orange-400 text-white py-3 px-7 rounded-r-md">Найти</button>
                            </div>
                            <div>
                                <div class="py-3 border-b-2 border-gray-150 flex justify-between" v-for="n in 10">
                                    <div>
                                        <span class="text-base text-black mr-3">{{ n }}</span>
                                        <span class="text-base text-gray-150 mr-8">Алиса Андреева</span>
                                        <span class="text-base text-gray-150 mr-8">0500 000 000</span>
                                    </div>
                                    <checkbox class="justify-between mb-2">
                                    </checkbox>
                                </div>
                            </div>
                            <div class="pt-5 flex justify-end">
                                <button class="bg-orange-400 rounded-xl text-white py-3 px-10">Выбрать</button>
                            </div>
                        </div>
                    </modal>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'
import ClientsTable from '@/Components/Clients/ClientsTable'
import CustomerCard from '@/Components/Modals/CustomerCard'

export default {
    data() {
        return {
            name: 'clients',
            query: this.search,
        }
    },
    props: {
        clients: Object,
        orderStatusTypes: Object,
        search: String,
    },
    components: {
        AppLayout,
        ClientsTable,
        CustomerCard
    },
    methods: {
        filter() {
            this.$inertia.reload({
                only: ['clients'],
                data: {
                    q: this.query,
                },
            })
        },
    },
}
</script>
