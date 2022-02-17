<template>
    <app-layout-map content-width="w-2/3">
        <div class="py-4" id="personal-create-orders">
            <div class="pt-4 flex justify-between mb-4">
                <h1 class="text-3xl font-semibold text-gray-900">Регистрация заказа</h1>
                <button @click.prevent="back()">Отмена</button>
            </div>
            <div class="border border-red-400 px-3 py-4 text-red-400 rounded my-5" v-if="form.hasErrors">
                Проверьте введенные данные и попробуйте снова.
            </div>

            <order-type :phone="form.client.phone" />

            <div class="flex items-end py-4 mb-1">
                <div class="mr-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Данные клиента</h2>
                    <div class="flex">
                        <div class="relative mr-4" v-click-outside="hideClientInfo">
                            <the-mask mask="+###############" v-model="form.client.phone"
                                      placeholder="+"
                                      @input="checkPhoneNumber(form.client.phone)"
                                      class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 rounded w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                            />
                            <button v-if="clientHasOrders" type="button"
                                    @click="showClientInfo = true"
                                    class="absolute top-3.5 right-3 rounded-full border-1 text-orange-400 border-orange-400">
                                <icon name="info" class="w-4"></icon>
                            </button>
                            <div v-if="showClientInfo" class="absolute right-3 top-14 w-5 h-5 transform rotate-45 bg-white shadow-lg"></div>
                            <div v-if="showClientInfo" class="absolute w-240 left-0 top-16 border border-solid border-gray-200 rounded-md bg-white shadow-lg z-10">
                                <div class="flex text-sm items-start justify-between">
                                    <table class="w-full m-2">
                                        <tr class="text-gray-300">
                                            <td class="px-1 pb-2">Заведение</td>
                                            <td class="px-1 pb-2">Откуда</td>
                                            <td class="px-1 pb-2">Куда</td>
                                            <td class="px-1 pb-2">Оператор</td>
                                            <td class="px-1 pb-2">Тип заказа</td>
                                            <td class="px-1 pb-2">Дата</td>
                                            <td class="px-1 pb-2">Примечание</td>
                                        </tr>
                                        <tr v-for="lastOrder in lastOrders"
                                            class="cursor-pointer font-bold align-baseline leading-none">
                                            <td class="p-1" @click="openOrder(lastOrder)">{{ partnerName(lastOrder) }}</td>
                                            <td class="p-1" @click="openOrder(lastOrder)">{{ pickupAddress(lastOrder) }}</td>
                                            <td class="p-1" @click="setDeliveryAddress(lastOrder)">{{ deliveryAddress(lastOrder) }}</td>
                                            <td class="p-1" @click="openOrder(lastOrder)">{{ lastOrder.operator ? lastOrder.operator.name : '-' }}</td>
                                            <td class="p-1" @click="openOrder(lastOrder)">{{ lastOrder.type.label }}</td>
                                            <td class="p-1" @click="openOrder(lastOrder)">{{ moment(lastOrder.createdAt).format('DD.MM.YY HH:mm') }}</td>
                                            <td class="p-1" @click="setDeliveryAddress(lastOrder)">{{ addressComment(lastOrder) }}</td>
                                        </tr>
                                    </table>
                                    <button type="button"
                                            @click="showClientInfo = false"
                                            class="mr-3 mt-3">
                                        <icon name="close" class="w-4 h-4 fill-current text-gray-500 inline-block"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <a v-if="isInBlackList" :href="`/blacklist?id=${blacklistId}`" target="_blank" class="bottom-0 left-0 mt-2 underline cursor-pointer leading-none text-red-400 text-sm">Данный номер в черном списке</a>
                        <input
                            class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md focus:border-orange-400 mr-4"
                            type="text" placeholder="ФИ" v-model="form.client.name">
                        <input
                            class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                            type="text" :placeholder="`Сумма выкупа: ${buyoutAmount}`" disabled>
                    </div>
                </div>
            </div>
            <div class="flex items-end py-4 mb-1">
                <div class="mr-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Тип заказа</h2>
                    <div class="flex">
                        <div class="relative" v-for="(label, type) in deliveryTypes">
                            <radio-button width="w-44" class="mr-4"
                                          :class="type === 'pre-order' ? ['absolute', 'left-0', 'top-0', 'bg-white'] : []"
                                          @change="selectDeliveryType(type)"
                                          :value="type" :label="label"
                                          v-if="type === 'pre-order' ? !form.deliveryTime : true"
                                          v-model="form.deliveryType"/>
                            <div v-if="type === 'pre-order'">
                                <v-date-picker v-model="form.deliveryTime" mode="dateTime" :minute-increment="5" is24hr ref="datepicker">
                                    <template v-slot="{ inputValue, togglePopover }">
                                        <div @click.prevent="togglePopover"
                                            class="flex items-center border border-solid border-orange-400 w-44 focus:border-orange-400 mr-4 px-3 py-3 rounded-md">
                                            <input class="leading-tight w-full text-orange-400"
                                                   :value="inputValue"/>
                                            <icon name="edit"
                                                  class="w-4 h-4 inline-block fill-current text-orange-400 cursor-pointer"></icon>
                                        </div>
                                    </template>
                                </v-date-picker>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-end py-4 mb-1">
                <div class="mr-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Откуда забрать</h2>
                    <div v-for="(address, i) in pickupAddresses">
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <span class="mr-2">{{ i + 1 }}</span>
                                <div class="flex mr-4">
                                    <button class="mr-1 disabled:text-gray-400 text-orange-400" @click.prevent="upIndexPickupAddress(i)" :disabled="i < 1">
                                        <icon name="long-arrow" class="w-3 h-3 inline-block transform rotate-90 fill-current"/>
                                    </button>
                                    <button class="disabled:text-gray-400 text-orange-400" @click.prevent="downIndexPickupAddress(i)" :disabled="pickupAddresses.length -1 == i">
                                        <icon name="long-arrow" class="w-3 h-3 inline-block transform -rotate-90 fill-current"/>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <div class="relative" v-click-outside="hidePickupAddressDropdown">
                                        <input class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-80 leading-tight py-3 rounded-md mr-5 focus:border-orange-400"
                                               v-model="address.address"
                                               type="text"
                                               placeholder="Адрес"
                                               @focus="openPickupAddressDropdown(i)"
                                               @input="geocodePickupAddress(address, i)"
                                               autocomplete="off">
                                        <div v-if="pickupAddressDropdown === i"
                                             class="absolute z-20 left-0 right-0">
                                            <div class="rounded mr-5 bg-white max-h-60 overflow-y-auto py-1">
                                                <div v-for="orderPickupAddress in orderPickupAddresses"
                                                     class="cursor-pointer px-2 hover:bg-gray-100"
                                                     @click="selectPickupAddress(orderPickupAddress, i)">{{ orderPickupAddress.address }}</div>
                                            </div>
                                        </div>
                                        <div v-if="geocodePickupAddressDropdown === i"
                                             class="absolute z-20 left-0 right-0">
                                            <div class="rounded mr-5 bg-white max-h-60 overflow-y-auto py-1">
                                                <div v-for="geocodePickupAddress in geocodePickupAddresses"
                                                     class="cursor-pointer px-2 hover:bg-gray-100"
                                                     @click="selectGeocodePickupAddress(address, geocodePickupAddress)">
                                                    <strong>{{ geocodePickupAddress.address }}</strong>
                                                    <span v-if="geocodePickupAddress.type !== 'building'"> - {{ geocodePickupAddress.short }}</span>
                                                    <span v-if="geocodePickupAddress.purpose">({{ geocodePickupAddress.purpose }})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="rounded-full border-red-400 border-2 border-solid flex items-center w-4 h-4 justify-center mr-4"
                                            :disabled="pickupAddresses.length <= 1"
                                            @click.prevent="deletePickupAddress(i)">
                                        <icon name="close" class="w-2 h-2 fill-current text-red-400 inline-block"/>
                                    </button>
                                    <button class="border border-solid border-orange-400 p-3 rounded-md mr-6 flex items-center"
                                            @click.prevent="pickupAddresses.push(newPickupAddress())">
                                        <icon name="plus" class="w-4 h-4 block fill-current text-orange-400"/>
                                    </button>
                                    <input class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md mr-4 focus:border-orange-400"
                                            type="number" v-model="address.buyoutAmount" placeholder="Сумма выкупа">
                                </div>
                            </div>
                            <div class="flex mb-4">
                                <input
                                        class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-104 leading-tight py-3 rounded-md mr-6 ml-14 focus:border-orange-400"
                                        type="text" v-model="address.comment" placeholder="Примечание">
                                <input
                                        class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                                        type="text" v-model="address.additionalInfo" placeholder="Доп инфо">
                            </div>
                            <div class="flex items-center pl-14 justify-between">
                                <div class="bg-white flex items-center justify-between border border-solid border-gray-200 rounded-md w-56 leading-tight rounded-md mr-4 pl-2 p-0.5" v-if="i > 0">
                                    <multiselect placeholder="Тип адреса"
                                                 v-model="address.pickupAddressType"
                                                 class="border-3 border-solid border-transparent"
                                                 track-by="label"
                                                 label="label"
                                                 :options="pickupAddressTypes">
                                    </multiselect>
                                </div>
                                <checkbox class="justify-between" v-model="address.isLargeSize">
                                    <span class="text-xl font-medium mr-12">Габаритный груз</span>
                                </checkbox>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-end py-4 mb-1">
                <div class="mr-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Куда доставить</h2>
                    <div v-for="(address, i) in deliveryAddresses">
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <span class="w-5">{{ i + 1 }}</span>
                                <div class="flex mr-4">
                                    <button class="mr-1 disabled:text-gray-400 text-orange-400" @click.prevent="upIndexDeliveryAddress(i)" :disabled="i < 1">
                                        <icon name="long-arrow" class="w-3 h-3 inline-block transform rotate-90 fill-current"/>
                                    </button>
                                    <button class="disabled:text-gray-400 text-orange-400" @click.prevent="downIndexDeliveryAddress(i)" :disabled="deliveryAddresses.length -1 == i">
                                        <icon name="long-arrow" class="w-3 h-3 inline-block transform -rotate-90 fill-current"/>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <div class="relative" v-click-outside="hideDeliveryAddressDropdown">
                                        <input class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-80 leading-tight py-3 rounded-md mr-5 focus:border-orange-400"
                                               v-model="address.address"
                                               type="text"
                                               placeholder="Адрес"
                                               @focus="openDeliveryAddressDropdown(i)"
                                               @input="geocodeDeliveryAddress(address, i)"
                                               autocomplete="off">
                                        <div v-if="deliveryAddressDropdown === i"
                                             class="absolute z-20 left-0 right-0">
                                            <div class="rounded mr-5 bg-white max-h-60 overflow-y-auto py-1">
                                                <div v-for="orderDeliveryAddress in orderDeliveryAddresses"
                                                     class="cursor-pointer px-2 hover:bg-gray-100"
                                                     @click="selectDeliveryAddress(orderDeliveryAddress, i)">{{ orderDeliveryAddress.address }}</div>
                                            </div>
                                        </div>
                                        <div v-if="geocodeDeliveryAddressDropdown === i"
                                             class="absolute z-20 left-0 right-0">
                                            <div class="rounded mr-5 bg-white max-h-60 overflow-y-auto py-1">
                                                <div v-for="geocodeDeliveryAddress in geocodeDeliveryAddresses"
                                                     class="cursor-pointer px-2 hover:bg-gray-100"
                                                     @click="selectGeocodeDeliveryAddress(address, geocodeDeliveryAddress)">
                                                    <strong>{{ geocodeDeliveryAddress.address }}</strong>
                                                    <span v-if="geocodeDeliveryAddress.type !== 'building'"> - {{ geocodeDeliveryAddress.short }}</span>
                                                    <span v-if="geocodeDeliveryAddress.purpose">({{ geocodeDeliveryAddress.purpose }})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                            class="rounded-full border-red-400 border-2 border-solid flex items-center w-4 h-4 justify-center mr-4"
                                            :disabled="deliveryAddresses.length <= 1"
                                            @click.prevent="deleteDeliveryAddress(i)">
                                        <icon name="close" class="w-2 h-2 fill-current text-red-400 inline-block"/>
                                    </button>
                                    <button
                                            class="border border-solid border-orange-400 p-3 rounded-md mr-6 flex items-center"
                                            @click.prevent="deliveryAddresses.push(newDeliveryAddress())">
                                        <icon name="plus" class="w-4 h-4 block fill-current text-orange-400"/>
                                    </button>
                                    <input
                                            class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                                            type="text" v-model="address.additionalInfo" placeholder="Доп инфо">
                                </div>
                            </div>
                            <div class="flex mb-4">
                                <input
                                    class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-104 leading-tight py-3 rounded-md mr-6 ml-14 focus:border-orange-400"
                                    type="text" v-model="address.comment" placeholder="Примечание">
                            </div>
                        </div>
                    </div>
                    <div class="my-4 w-36">
                        <checkbox v-model="form.withoutAddress" class="justify-between">
                            <span class="text-gray-900">Без адреса</span>
                        </checkbox>
                    </div>
                </div>
            </div>
            <div class="pb-4 mb-2">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Способ оплаты заказа</h2>
                <div class="flex">
                    <div class="mr-4" v-for="(label, type) in paymentTypes">
                        <radio-button v-model="form.paymentType" :value="type" :label="label" />
                    </div>
                </div>
            </div>
            <div class="flex items-end pb-4 mb-2">
                <div class="mr-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Сдача с</h2>
                    <input v-model="form.change"
                        class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 focus:border-orange-400 rounded-md"
                        type="text" placeholder="Ввод">
                </div>
            </div>
<!--            <div class="pb-4 mb-2">-->
<!--                <h2 class="text-xl font-medium text-gray-900 mb-3">Способ оплаты доставки</h2>-->
<!--                <div class="flex">-->
<!--                    <div class="mr-4" v-for="(label, type) in paymentTypes">-->
<!--                        <radio-button v-model="form.deliveryPaymentType" :value="type" :label="label" />-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="flex py-4 mb-3">
                <div class="mr-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Примечание</h2>
                    <textarea v-model="form.comment"
                        class="border border-solid border-gray-200 w-126 rounded-md h-24 p-3 flex items-start flex-wrap focus:border-orange-400"></textarea>
                </div>
                <div class="w-56">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Курьер</h2>
                    <div
                        class="bg-white flex items-center justify-between border border-solid border-gray-200 rounded w-56 leading-tight rounded-md mr-4 pl-2 p-0.5">
                        <multiselect placeholder="Выбрать курьера"
                                     track-by="full_name"
                                     label="full_name"
                                     :value="couriers.find(courier => courier.id === form.courierId)"
                                     :options="couriers"
                                     @input="selectCourier"
                        >
                        </multiselect>
                    </div>
                </div>
            </div>
            <div class="flex items-start py-4 mb-1">
                <div class="mr-4">
                    <checkbox class="justify-between mb-2 mr-12 items-start" v-model="form.reportable">
                        <span class="text-xl font-medium block mr-6 w-44">Фотоотчет</span>
                    </checkbox>
                    <p class="text-gray-400 w-56">Фотоотчет от курьера после доставки товара</p>
                </div>
                <div class="mr-4">
                    <checkbox v-model="form.manualCalculationMode" :checked="form.manualCalculationMode"
                              class="justify-between mb-2 mr-12 items-start">
                        <span class="text-xl font-medium block mr-6 w-44">Ручной рассчет <br> стоимости</span>
                    </checkbox>
                </div>
            </div>
            <div class="flex py-4 mb-2">
                <div class="mr-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Сумма доставки</h2>
                    <input type="number"
                           :disabled="! form.manualCalculationMode"
                           v-model="form.deliveryCost"
                           :required="form.manualCalculationMode"
                           placeholder="Сумма доставки"
                           class="w-56 text-gray-800 placeholder-gray-800 rounded-md border border-solid border-gray-200 leading-tight p-3"/>
                </div>
                <div class="mr-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Сумма в офис</h2>
                    <input v-model="form.sumToOffice"
                           :disabled="! form.manualCalculationMode"
                           type="number"
                           :required="form.manualCalculationMode"
                           placeholder="Сумма в офис"
                           class="w-56 text-gray-800 placeholder-gray-800 rounded-md border border-solid border-gray-200 leading-tight p-3"/>
                </div>
                <div class="mr-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Комиссия курьера</h2>
                    <input
                        :disabled="! form.manualCalculationMode"
                        :required="form.manualCalculationMode"
                        placeholder="Коммиссия курьера"
                        min="0"
                        max="100"
                        v-model="form.courierCommission"
                        type="number"
                        class="w-56 text-gray-800 placeholder-gray-800 rounded-md border border-solid border-gray-200 leading-tight p-3"/>
                </div>
            </div>
            <div class="py-4 flex items-center">
                <button class="leading-tight py-3 bg-gray-500 text-white rounded-md px-7 mr-3"
                        @click.prevent="calculateDeliveryCost"
                        v-if="hasDeliveryAddressesCoordinates && hasPickupAddressesCoordinates && deliveryCostShouldBeCalculated">
                    Рассчитать стоимость доставки
                </button>
                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7"
                    v-else @click="submit">
                    Сохранить заказ
                </button>
            </div>
        </div>
    </app-layout-map>
</template>

<script>
import Addresses from '../../Mixins/addresses'
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'
import axios from 'axios'
import OrderType from '@/Components/Orders/OrderType'
import {Inertia} from "@inertiajs/inertia"
import {debounce} from 'lodash'
import ClickOutside from 'vue-click-outside'

export default {
    components: {
        VDatePicker,
        OrderType,
    },
    directives: {
        ClickOutside
    },
    props: {
        deliveryTypes: Object,
        paymentTypes: Object,
        couriers: Array,
        pickupAddressTypes: {
            type: Array,
            default: () => [],
        },
        phone: String,
    },

    data() {
        return {
            showClientInfo: false,
            pickupAddressDropdown: null,
            geocodePickupAddressDropdown: null,
            geocodePickupAddresses: [],
            deliveryAddressDropdown: null,
            geocodeDeliveryAddressDropdown: null,
            geocodeDeliveryAddresses: [],
            value: null,
            orderType: null,
            form: this.$inertia.form({
                client: {
                    name: null,
                    phone: this.phone,
                },
                deliveryType: Object.keys(this.deliveryTypes)[0],
                deliveryTime: null,
                comment: '',
                withoutAddress: false,
                addresses: [],
                change: null,
                reportable: false,
                courierId: null,
                operatorId: this.$page.props.auth.user.id,
                isSoon: true,
                manualCalculationMode: false,
                courierCommission: null,
                sumToOffice: null,
                deliveryCost: null,
                paymentType: Object.keys(this.paymentTypes)[0],
                deliveryPaymentType: Object.keys(this.paymentTypes)[0],
            }),
            pickupAddresses: [this.newPickupAddress()],
            deliveryAddresses: [this.newDeliveryAddress()],
            isInBlackList: false,
            blacklistId: null,
            client: null,
            exitPreventer: () => {},
        }
    },

    computed: {
        hasDeliveryAddressesCoordinates() {
            for(let i = 0; i < this.deliveryAddresses.length; i++) {
                if(!this.deliveryAddresses[i].latitude || !this.deliveryAddresses[i].longitude) {
                    return false
                }
            }
            return true
        },
        hasPickupAddressesCoordinates() {
            for(let i = 0; i < this.pickupAddresses.length; i++) {
                if(!this.pickupAddresses[i].latitude || !this.pickupAddresses[i].longitude) {
                    return false
                }
            }
            return true
        },
        lastOrders() {
            return this.client.relationships.orders.slice(0, 3)
        },
        clientHasOrders() {
            return this.client ? this.client.relationships.orders.length : false
        },
        orderPickupAddresses() {
            let addresses = []
            if(this.client) {
                this.client.relationships.orders.forEach(order => {
                    order.orderAddresses.forEach(orderAddress => {
                        if(orderAddress.attributes.type === 'pickup' && !addresses.map(item => item.address).includes(orderAddress.attributes.address)) {
                            addresses.push({
                                address: orderAddress.attributes.address,
                                latitude: orderAddress.attributes.latitude,
                                longitude: orderAddress.attributes.longitude,
                            })
                        }
                    })
                })
            }

            return addresses.reduce((x, y) => x.includes(y) ? x : [...x, y], [])
        },
        orderDeliveryAddresses() {
            let addresses = []
            if(this.client) {
                this.client.relationships.orders.forEach(order => {
                    order.orderAddresses.forEach(orderAddress => {
                        if(orderAddress.attributes.type === 'delivery' && !addresses.map(item => item.address).includes(orderAddress.attributes.address)) {
                            addresses.push({
                                address: orderAddress.attributes.address,
                                latitude: orderAddress.attributes.latitude,
                                longitude: orderAddress.attributes.longitude,
                            })
                        }
                    })
                })
            }

            return addresses.reduce((x, y) => x.includes(y) ? x : [...x, y], [])
        },
        buyoutAmount() {
            return this.pickupAddresses.reduce((carry, address) => carry + +address.buyoutAmount, 0)
        },
        courierEarnings() {
            return Math.round(this.form.deliveryCost * this.form.courierCommission)
        },
        deliveryCostShouldBeCalculated() {
            return !this.form.manualCalculationMode && !this.form.deliveryCost && !this.form.withoutAddress
        },
        pickupTypes() {
            return Object.keys(this.pickupAddressTypes).map(value => ({
                value,
                label: this.pickupAddressTypes[value]
            }))
        }
    },

    methods: {
        hideClientInfo() {
            this.showClientInfo = false
        },
        hidePickupAddressDropdown() {
            this.pickupAddressDropdown = null
            this.geocodePickupAddressDropdown = null
        },
        hideDeliveryAddressDropdown() {
            this.deliveryAddressDropdown = null
            this.geocodeDeliveryAddressDropdown = null
        },
        openOrder(order) {
            window.open(`/orders/${order.id}`, '_blank');
        },
        partnerName(order) {
            if(order.type.value === 'standard' && order.orderBranches.length) {
                return order.orderBranches[0].relationships.branch.partner.name
            }
            return '-'
        },
        pickupAddress(order) {
            if(order.type.value === 'courier') {
                return order.orderAddresses.find(address => address.attributes.type === 'pickup').attributes.address
            }
            if(order.type.value === 'standard' && order.orderBranches.length) {
                return order.orderBranches[0].relationships.branch.address
            }
        },
        deliveryAddress(order) {
            let deliveryAddress = order.orderAddresses.find(address => address.attributes.type === 'delivery')
            return deliveryAddress ? deliveryAddress.attributes.address : ''
        },
        addressComment(order) {
            let deliveryAddress = order.orderAddresses.find(address => address.attributes.type === 'delivery')
            return deliveryAddress ? deliveryAddress.attributes.comment : ''
        },
        setDeliveryAddress(order) {
            let deliveryAddress = order.orderAddresses.find(address => address.attributes.type === 'delivery')
            if(deliveryAddress) {
                this.deliveryAddresses[0].address = deliveryAddress.attributes.address
                this.deliveryAddresses[0].latitude = deliveryAddress.attributes.latitude
                this.deliveryAddresses[0].longitude = deliveryAddress.attributes.longitude
                this.deliveryAddresses[0].comment = deliveryAddress.attributes.comment
                this.hideClientInfo()
            }
        },
        openPickupAddressDropdown(index) {
            if(this.orderPickupAddresses.length) {
                this.geocodePickupAddressDropdown = null
                this.pickupAddressDropdown = index
            }
        },
        selectPickupAddress(address, index) {
            this.pickupAddresses[index].address = address.address
            this.pickupAddresses[index].latitude = address.latitude
            this.pickupAddresses[index].longitude = address.longitude
            this.pickupAddressDropdown = null
        },
        selectGeocodePickupAddress(address, geocodeAddress) {
            address.address = `${geocodeAddress.address} (${geocodeAddress.purpose})`

            if (geocodeAddress.type !== 'building') {
                address.address += ` - ${geocodeAddress.short}`
            }

            address.latitude = geocodeAddress.geocode.latitude
            address.longitude = geocodeAddress.geocode.longitude
            this.geocodePickupAddressDropdown = null
            this.geocodePickupAddresses = []
        },
        openDeliveryAddressDropdown(index) {
            if(this.orderDeliveryAddresses.length) {
                this.geocodeDeliveryAddressDropdown = null
                this.deliveryAddressDropdown = index
            }
        },
        selectDeliveryAddress(address, index) {
            this.deliveryAddresses[index].address = address.address
            this.deliveryAddresses[index].latitude = address.latitude
            this.deliveryAddresses[index].longitude = address.longitude
            this.deliveryAddressDropdown = null
        },
        selectGeocodeDeliveryAddress(address, geocodeAddress) {
            address.address = `${geocodeAddress.address} (${geocodeAddress.purpose})`

            if (geocodeAddress.type !== 'building') {
                address.address += ` - ${geocodeAddress.short}`
            }

            address.latitude = geocodeAddress.geocode.latitude
            address.longitude = geocodeAddress.geocode.longitude
            this.geocodeDeliveryAddressDropdown = null
            this.geocodeDeliveryAddresses = []
        },
        newPickupAddress() {
            return {
                address: '',
                longitude: null,
                latitude: null,
                buyoutAmount: '',
                comment: '',
                additionalInfo: '',
                isLargeSize: false,
                type: 'pickup',
                pickupAddressType: null,
            }
        },
        newDeliveryAddress() {
            return {
                address: '',
                longitude: null,
                latitude: null,
                comment: '',
                additionalInfo: '',
                isLargeSize: false,
                type: 'delivery',
            }
        },
        deletePickupAddress(index) {
            this.pickupAddresses.splice(index, 1)
        },
        deleteDeliveryAddress(index) {
            this.deliveryAddresses.splice(index, 1)
        },
        geocodePickupAddress: debounce(async function (address, index) {
            if(address.address) {
                this.pickupAddressDropdown = null

                const { data: addresses } = await axios.post('/maps/geocode', {
                    address: address.address
                })

                this.geocodePickupAddresses = addresses

                this.geocodePickupAddressDropdown = index
            }
        }, 500),
        geocodeDeliveryAddress: debounce(async function (address, index) {
            if(address.address) {
                this.deliveryAddressDropdown = null

                const { data: addresses } = await axios.post('/maps/geocode', {
                    address: address.address
                })

                this.geocodeDeliveryAddresses = addresses
                this.geocodeDeliveryAddressDropdown = index
            }
        }, 500),
        selectDeliveryType(type) {
            if (type === 'pre-order') {
                this.form.deliveryTime = Date.now()
                return this.$refs.datepicker[0].togglePopover()
            }

            this.form.deliveryTime = null
        },
        selectCourier(courier) {
            this.form.courierId = courier.id
            this.updateCommissions(courier)
        },
        updateCommissions(courier) {
            this.form.courierCommission = courier.commission
            this.form.sumToOffice = Math.round(this.form.deliveryCost - this.form.deliveryCost * courier.commission)
        },
        submit() {
            this.form.addresses = [
                ...this.pickupAddresses.map(address => ({
                    ...address,
                    pickupAddressType: address.pickupAddressType?.value,
                })),
                ...this.deliveryAddresses,
            ]

            this.form.transform((data) => ({
                ...data,
                client: {
                    name: this.form.client.name,
                    phone: this.form.client.phone,
                },
                deliveryTime: this.form.deliveryTime ? this.moment(this.form.deliveryTime).format('YYYY-MM-DD HH:mm') : '',
            }))

            this.exitPreventer()

            this.form.post('/orders/personal')
        },
        upIndexPickupAddress(index) {
            this.pickupAddresses[index] = this.pickupAddresses.splice(index - 1, 1, this.pickupAddresses[index])[0]
        },
        downIndexPickupAddress(index) {
            this.pickupAddresses[index] = this.pickupAddresses.splice(index + 1, 1, this.pickupAddresses[index])[0]
        },
        upIndexDeliveryAddress(index) {
            this.deliveryAddresses[index] = this.deliveryAddresses.splice(index - 1, 1, this.deliveryAddresses[index])[0]
        },
        downIndexDeliveryAddress(index) {
            this.deliveryAddresses[index] = this.deliveryAddresses.splice(index + 1, 1, this.deliveryAddresses[index])[0]
        },
        async calculateDeliveryCost() {
            const { data: { cost } } = await axios.post('/api/maps/personal-delivery-cost', {
                pickup_addresses: this.pickupAddresses.map(address => ({
                    longitude: address.longitude,
                    latitude: address.latitude,
                    buyout: address.buyoutAmount,
                    is_large_size: address.isLargeSize,
                    type: address.pickupAddressType?.value,
                })),
                delivery_addresses: this.deliveryAddresses.map(address => ({
                    longitude: address.longitude,
                    latitude: address.latitude,
                })),
            })

            this.form.deliveryCost = cost

            this.updateCommissions(this.couriers.find(
                courier => courier.id === this.form.courierId
            ))
        },
        async checkPhoneNumber(phone) {
            this.isInBlackList = false
            this.showClientInfo = false
            this.client = null
            await axios.get('/clients/check/number', {
                params: {phone}
            }).then(({data}) => {
                switch (data.state) {
                    case 'blacklisted':
                        this.isInBlackList = true
                        this.blacklistId = data.blacklistId
                        break
                    case 'exists':
                        this.client = data.client
                        if(this.clientHasOrders) {
                            this.showClientInfo = true
                        }
                        break
                }
            })
        }
    },
    mounted() {
        this.exitPreventer = Inertia.on('before', e => {
            if (!confirm('Вы уверены, что хотите покинуть создание заказа?')) {
                return e.preventDefault()
            }

            this.exitPreventer()
        })
    },
    watch: {
        'form.withoutAddress'(value) {
            if(value) {
                this.deliveryAddresses = []
            } else if(!this.deliveryAddresses.length) {
                this.deliveryAddresses = [this.newDeliveryAddress()]
            }
        },
        pickupAddresses: {
            deep: true,
            handler() {
                if (!this.form.manualCalculationMode) {
                    this.form.deliveryCost = null
                }
            },
        },
        deliveryAddresses: {
            deep: true,
            handler() {
                if (!this.form.manualCalculationMode) {
                    this.form.deliveryCost = null
                }
            },
        },
    },
    mixins: [Addresses]
}
</script>

<style>

#personal-create-orders .multiselect__input {
    color: #211C1C;
}

#personal-create-orders .multiselect__placeholder {
    color: #211C1C;
}
#personal-create-orders .multiselect .multiselect__tags {
    padding-left: 0;
}

#personal-create-orders .multiselect--active .multiselect__input, .multiselect__single {
    padding-left: 0;
}

#personal-create-orders .vc-select select {
    background-image: none;
}
.vc-popover-content-wrapper {
    z-index: 20 !important;
}
</style>
