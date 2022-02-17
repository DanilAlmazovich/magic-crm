<template>
    <app-layout-map content-width="w-2/3">
        <div class="py-4" id="create-orders">
            <div class="pt-4 flex justify-between mb-4">
                <h1 class="text-3xl font-semibold text-gray-900">Регистрация заказа</h1>
                <button @click="$modal.question().then(() => back())">Отмена</button>
            </div>
            <div v-if="form.hasErrors || unprocessableEntityError"
                 class="border border-red-400 px-3 py-4 text-red-400 rounded my-5">
                Проверьте введенные данные и попробуйте снова.
            </div>

            <order-type :phone="form.client.phone" />

            <form @input="form.clearErrors($event.target.id)" @submit.prevent="submitForm">
                <div class="flex items-end py-4 mb-1">
                    <div class="mr-4 w-56">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Данные клиента</h2>
                        <div class="relative" v-click-outside="hideClientInfo">
                            <the-mask mask="+###############" v-model="form.client.phone"
                                      placeholder="+" id="client.phone"
                                      @input="checkPhoneNumber"
                                      class="border text-gray-800 placeholder-gray-800 border-solid border-gray-200 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                                      :class="form.errors['client.phone'] ? ['border-red-600'] : []"
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
                    </div>
                    <div class="mr-4">
                        <a v-if="client" class="block mb-3 cursor-pointer" @click="$modal.open('customerCard', { client })">
                            <span class="text-lg text-yellow-400 border-b border-yellow-400">Заказ по счету:</span>
                            <span class="pl-1 text-lg text-yellow-400 border-b border-yellow-400">{{ client.attributes.ordersCount }}</span>
                        </a>
                        <div v-else class="mb-3">
                            <span class="text-lg text-yellow-400 border-b border-yellow-400">Заказ по счету:</span>
                            <span class="pl-1 text-lg text-yellow-400 border-b border-yellow-400">{{ orderNumber }}</span>
                        </div>
                        <input v-model="form.client.name" id="client.name"
                               :class="form.errors['client.name'] ? ['border-red-600'] : []"
                               class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                               type="text" placeholder="ФИ">
                    </div>
                </div>
                <div class="flex items-end py-4 mb-1">
                    <div class="mr-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Куда доставить</h2>
                        <div>
                            <div v-for="(address, index) in form.addresses" class="mb-8 last:mb-0">
                                <div class="flex items-center mb-5">
                                    <span class="mr-2">{{ index + 1 }}</span>
                                    <div class="flex mr-4">
                                        <button class="mr-1 disabled:text-gray-400 text-orange-400" @click.prevent="upIndexAddress(index)" :disabled="index < 1">
                                            <icon name="long-arrow" class="w-3 h-3 inline-block transform rotate-90 fill-current"/>
                                        </button>
                                        <button class="disabled:text-gray-400 text-orange-400" @click.prevent="downIndexAddress(index)" :disabled="form.addresses.length - 1 == index">
                                            <icon name="long-arrow" class="w-3 h-3 inline-block transform -rotate-90 fill-current"/>
                                        </button>
                                    </div>
                                    <div class="relative" v-click-outside="hideAddressDropdown">
                                        <input placeholder="Адрес"
                                               :id="`addresses.${index}.address`"
                                               :class="form.errors[`addresses.${index}.address`] ? ['border-red-600'] : []"
                                               class="border text-gray-800 placeholder-gray-800 border-solid border-gray-200 w-80 leading-tight py-3 rounded-md mr-5 focus:border-orange-400"
                                               v-model="address.address"
                                               type="text"
                                               @focus="openAddressDropdown(index)"
                                               @input="geocodeAddress(address, index)"
                                               autocomplete="off">
                                        <div v-if="addressDropdown === index"
                                             class="absolute z-20 left-0 right-0">
                                            <div class="rounded mr-5 bg-white max-h-60 overflow-y-auto py-1">
                                                <div v-for="orderAddress in orderAddresses"
                                                     class="cursor-pointer px-2 hover:bg-gray-100"
                                                     @click="selectAddress(orderAddress, index)">{{ orderAddress.address }}</div>
                                            </div>
                                        </div>
                                        <div v-if="geocodeAddressDropdown === index"
                                             class="absolute z-20 left-0 right-0">
                                            <div class="rounded mr-5 bg-white max-h-60 overflow-y-auto py-1">
                                                <div v-for="geocodeAddress in geocodeAddresses"
                                                     class="cursor-pointer px-2 hover:bg-gray-100"
                                                     @click="selectGeocodeAddress(address, geocodeAddress)">
                                                        <strong>{{ geocodeAddress.address }}</strong>
                                                        <span v-if="geocodeAddress.type !== 'building'"> - {{ geocodeAddress.short }}</span>
                                                        <span v-if="geocodeAddress.purpose">({{ geocodeAddress.purpose }})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        class="rounded-full border-red-400 border-2 border-solid flex items-center w-4 h-4 justify-center mr-4"
                                        :disabled="form.addresses.length === 1"
                                        @click.prevent="deleteAddress(index)">
                                        <icon name="close" class="w-2 h-2 fill-current text-red-400 inline-block"/>
                                    </button>
                                    <button
                                        class="border border-solid border-orange-400 p-3 rounded-md mr-6 flex items-center"
                                        @click.prevent="addAddress">
                                        <icon name="plus" class="w-4 h-4 block fill-current text-orange-400"/>
                                    </button>
                                    <input v-model="address.additionalInfo"
                                           class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md mr-4 focus:border-orange-400"
                                           type="text" placeholder="Доп инфо">
                                </div>
                                <input v-model="address.comment"
                                       class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-96 leading-tight py-3 rounded-md mr-4 ml-14 focus:border-orange-400"
                                       type="text" placeholder="Примечание">
                            </div>
                        </div>
                        <div class="my-4 w-36">
                            <checkbox v-model="form.withoutAddress" class="justify-between">
                                <span class="text-gray-900">Без адреса</span>
                            </checkbox>
                        </div>
                    </div>
                </div>
                <div class="py-4 mb-1">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Состав заказа</h2>
                    <div class="px-4">
                        <div v-for="(establishment, index) in establishments" class="mb-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <multiselect v-model="establishment.selectedBranch"
                                                 placeholder="Выберите филиал"
                                                 track-by="name"
                                                 label="name"
                                                 :allow-empty="false"
                                                 :options="establishment.branches"
                                                 @input="updateEstablishmentMenu(establishment)"
                                    >
                                    </multiselect>
                                </div>
                                <div class="flex items-center">
                                    <button class="flex items-center mr-5" @click.prevent="$modal.question().then(() => removePartner(index))">
                                        <span class="rounded-full border-red-400 border-2 border-solid
                                                flex items-center w-4 h-4 justify-center mb-[3px] mr-2">
                                            <icon name="close" class="w-2 h-2 fill-current text-red-400 inline-block"/>
                                        </span>
                                        <span class="text-red-400">Убрать заведение</span>
                                    </button>
                                    <button v-if="establishment.products.length"
                                            class="flex items-center mr-5"
                                            @click.prevent="chooseDiscounts(establishment)">
                                        <span class="text-orange-400">Применить акции</span>
                                    </button>
                                    <button class="flex items-center"
                                            @click.prevent="chooseMenuItems(establishment)">
                                        <icon name="plus" class="w-4 h-auto fill-current text-orange-400 mr-2"/>
                                        <span class="text-orange-400">Добавить позиции</span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex pt-3 pb-1">
                                <div class="w-1/2 odd:pr-4 even:pl-4 flex items-start"
                                     v-for="{} in establishment.products.length < 2 ? 1 : 2">
                                    <div class="w-6/12 flex">
                                        <div class="w-1/6"><span class="text-sm text-gray-400">№</span></div>
                                        <div class="w-5/6"><span class="text-sm text-gray-400">Наименование</span></div>
                                    </div>
                                    <div class="w-4/12 pr-6">
                                        <span class="text-sm text-gray-400">Кол-во</span>
                                    </div>
                                    <div class="w-2/12 pr-6">
                                        <span class="text-sm text-gray-400">Итого</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap">
                                <div class="w-1/2 py-4 odd:pr-4 even:pl-4"
                                     v-for="(product, i) in establishment.products">
                                    <div class="flex mb-2">
                                        <div class="flex w-6/12 items-center">
                                            <div class="w-1/6 leading-tight"><span class="text-sm">{{ i + 1 }}</span>
                                            </div>
                                            <div class="w-5/6 leading-tight"><span class="text-sm">
                                                {{ product.name }}</span>
                                            </div>
                                        </div>
                                        <div class="w-4/12 flex items-center justify-between">
                                            <counter v-model="product.quantity"
                                                     @input="quantityChanged($event, establishment)"
                                                     :disabled="product.present"></counter>
                                        </div>
                                        <div class="w-2/12 flex items-center justify-between">
                                            <div v-if="product.present">
                                                <icon name="gift" class="w-4 h-4 fill-current text-orange-400 inline-block"/>
                                            </div>
                                            <div v-else class="w-full flex items-center justify-between">
                                                <span class="pr-2">{{ productTotalPrice(product) }}</span>
                                                <button @click.prevent="$modal.question().then(() => deleteProduct(establishment, i))"
                                                        class="rounded-full border-red-400 border-2 border-solid flex items-center w-4 h-4 justify-center">
                                                    <icon name="close"
                                                          class="w-2 h-2 fill-current text-red-400 inline-block"/>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <div class="flex w-6/12 items-center">
                                            <div class="w-1/6"></div>
                                            <div class="w-5/6 leading-tight">
                                                <span class="text-sm text-gray-400">Кол-во контейнеров</span>
                                            </div>
                                        </div>
                                        <div class="w-4/12 flex items-center justify-between">
                                            <counter v-model="product.dishes_quantity"></counter>
                                        </div>
                                        <div class="w-2/12 flex items-center justify-between">
                                            <div class="w-full flex items-center justify-between">
                                                <span class="pr-2">{{ productTotalDishesPrice(product) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full flex items-center pt-2">
                                    <checkbox v-model="establishment.selectedBranch.isNeedCutlery"
                                              class="justify-between mb-2 mr-12">
                                        <span class="text-lg font-medium mr-12">Столовые приборы</span>
                                    </checkbox>
                                    <div v-if="establishment.selectedBranch.isNeedCutlery" class="mr-4">
                                        <input v-model="establishment.selectedBranch.numberOfPersons"
                                            class="border border-solid border-gray-150 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                                            type="number" placeholder="Кол-во персон">
                                    </div>
                                </div>
                                <div class="text-lg font-bold">Общий Итог: {{ allProductsTotalPrice(establishment.products) }} сом</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center mb-5">
                        <div class="bg-white flex items-center justify-between border border-solid mr-5
                                border-gray-200 rounded w-56 leading-tight rounded-md mr-4 text-gray-900 pl-2 py-0.5">
                            <multiselect v-model="selectedCategory"
                                @select="categorySelected"
                                @search-change="categorySearch"
                                :allow-empty="false"
                                placeholder="Выберите категории"
                                track-by="id"
                                label="name"
                                :options="categories">
                            </multiselect>
                        </div>
                        <div class="bg-white flex items-center justify-between border border-solid mr-5
                                border-gray-200 rounded w-56 leading-tight rounded-md mr-4 text-gray-900 pl-2 p-0.5">
                            <multiselect v-model="selectedEstablishment"
                                         placeholder="Заведения"
                                         track-by="id"
                                         label="name"
                                         :options="partners">
                            </multiselect>
                        </div>
                        <div class="flex justify-center py-4 text-orange-400">
                            <button class="flex items-center" @click.prevent="addPartner">
                                <icon class="w-3 h-3 fill-current mr-3" name="plus"/>
                                <span>Добавить заведение</span>
                            </button>
                        </div>
                    </div>
                    <p class="font-semibold text-red-600" v-if="form.errors.orderItems"
                       v-text="form.errors.orderItems"></p>
                </div>
                <div class="flex items-end py-4 mb-1">
                    <div class="mr-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Откуда забрать</h2>
                        <button type="button" class="px-4 py-3 mb-3 rounded-lg border-2 border-orange-400 flex items-center" @click.stop="pickupAddresses.push(newPickupAddress())" v-if="pickupAddresses.length < 1">
                            <span class="w-3 h-3 transform rotate-45 mr-3">
                                <icon name="cross" class="fill-current text-orange-400"></icon>
                            </span>
                            <span class="leading-none text-orange-400 text-base">Добавить заезд</span>
                        </button>
                        <div v-for="(address, index) in pickupAddresses" :key="index" class="mb-8 last:mb-0">
                            <div class="mb-8">
                                <div class="flex items-center mb-4">
                                    <span class="mr-2">{{ index + 1 }}</span>
                                    <div class="flex mr-4">
                                        <button class="mr-1 disabled:text-gray-400 text-orange-400" @click.prevent="upIndexPickupAddress(index)" :disabled="index < 1">
                                            <icon name="long-arrow" class="w-3 h-3 inline-block transform rotate-90 fill-current"/>
                                        </button>
                                        <button class="disabled:text-gray-400 text-orange-400" @click.prevent="downIndexPickupAddress(index)" :disabled="pickupAddresses.length - 1 === index">
                                            <icon name="long-arrow" class="w-3 h-3 inline-block transform -rotate-90 fill-current"/>
                                        </button>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="relative" v-click-outside="hideAddressDropdown">
                                            <input class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-80 leading-tight py-3 rounded-md mr-5 focus:border-orange-400"
                                                   v-model="address.address"
                                                   type="text"
                                                   placeholder="Адрес"
                                                   @input="geocodePickupAddress(address, index)">
                                            <div v-if="geocodePickupAddressDropdown === index"
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
                                        <button class="rounded-full border-red-400 border-2 border-solid flex items-center w-4 h-4 justify-center mr-4" @click="deletePickupAddress(index)">
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
                                <div class="flex items-start mb-4">
                                    <div class="w-104 mr-6 ml-14">
                                        <vue-editor class="w-full bg-white text-gray-800 placeholder-gray-800 leading-tight"
                                                    :id="`comment-${index}`"
                                                    :editorToolbar="commentToolbar"
                                                    v-model="address.comment"
                                                    placeholder="Примечание"/>
                                    </div>
                                    <div class="w-56">
                                        <input class="mb-4 w-full border border-solid border-gray-200 text-gray-800 placeholder-gray-800 leading-tight py-3 rounded-md focus:border-orange-400"
                                            type="text" v-model="address.additionalInfo" placeholder="Доп инфо">
                                        <div class="mb-4 w-full bg-white flex items-center justify-between border border-solid border-gray-200 rounded-md leading-tight pl-2">
                                            <multiselect placeholder="Тип адреса"
                                                         v-model="address.pickupAddressType"
                                                         class="border-3 border-solid border-transparent"
                                                         track-by="value"
                                                         label="label"
                                                         :options="pickupTypes">
                                            </multiselect>
                                        </div>
                                        <checkbox class="justify-between py-3" v-model="address.isLargeSize">
                                            <span class="font-medium mr-12">Габаритный груз</span>
                                        </checkbox>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-4 mb-2">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Способ оплаты</h2>
                    <div class="flex">
                        <div class="mr-4" v-for="(label, value) in paymentTypes">
                            <radio-button v-model="form.paymentType" :value="value" :label="label" @change="selectedPaymentType"/>
                        </div>
                    </div>
                </div>
                <div class="flex items-end pb-4 mb-2">
                    <div class="mr-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Сдача с</h2>
                        <input v-model="form.change"
                               class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 focus:border-orange-400 rounded-md"
                               type="number" placeholder="Ввод">
                    </div>
                </div>
                <div class="flex py-4 mb-1 hidden">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Курьер</h2>
                        <div
                            class="bg-white flex items-center justify-between border border-solid border-gray-200 rounded w-56 leading-tight rounded-md mr-4 pl-2 p-0.5">
                            <multiselect placeholder="Выбрать курьера"
                                         track-by="full_name"
                                         label="full_name"
                                         :value="couriers.find(courier => courier.id === form.courierId)"
                                         :options="couriers"
                                         @input="selectCourier">
                            </multiselect>
                        </div>
                    </div>
                </div>
                <div class="flex items-end py-4 mb-1">
                    <div class="mr-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Тип заказа</h2>
                        <div class="flex">
                            <div class="relative" v-for="(label, type) in orderDeliveryTypes">
                                <radio-button class="mr-4" @click="$refs.datepicker[0].togglePopover()"
                                              :class="type === 'pre-order' ? ['absolute', 'left-0', 'top-0', 'bg-white'] : []"
                                              @change="selectDeliveryType(type)"
                                              :value="type" :label="label"
                                              v-if="type === 'pre-order' ? !form.deliveryTime : true"
                                              v-model="form.deliveryType"/>
                                <div v-if="type === 'pre-order'">
                                    <v-date-picker v-model="form.deliveryTime" mode="dateTime" :minute-increment="5" is24hr ref="datepicker">
                                        <template v-slot="{ inputValue, togglePopover }">
                                            <div
                                                class="flex items-center border border-solid border-orange-400 w-44 focus:border-orange-400 mr-4 px-3 py-3 rounded-md">
                                                <input class="leading-tight w-full text-orange-400"
                                                       @click.prevent="togglePopover"
                                                       :value="inputValue"/>
                                                <icon name="edit" @click.prevent="togglePopover"
                                                      class="w-4 h-4 inline-block fill-current text-orange-400"></icon>
                                            </div>
                                        </template>
                                    </v-date-picker>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex py-4 mb-3">
                    <div class="mr-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Примечание</h2>
                        <textarea class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-126
                                    rounded-md h-24 p-3 flex items-start flex-wrap
                                    focus:border-orange-400"
                                  v-model="form.comment"
                        ></textarea>
                    </div>
                    <div class="w-56">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Быстрый ввод</h2>
                        <div class="flex flex-col">
                            <checkbox v-for="note in quickNotes"
                                      :value="note"
                                      :key="note"
                                      class="justify-between mb-2"
                                      @change="toggleQuickEntry($event, note)">
                                <span class="text-sm text-gray-900">{{ note }}</span>
                            </checkbox>
                        </div>
                    </div>
                </div>
                <div class="flex items-start py-4 mb-1">
                    <div class="mr-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Расстояние до клиента</h2>
                        <input type="number" disabled placeholder="Введите значение"
                               class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md focus:border-orange-400">
                    </div>
                    <div class="mr-4">
                        <checkbox v-model="form.manualCalculationMode" :checked="form.manualCalculationMode"
                                  class="justify-between mb-2 mr-12 items-start">
                            <span class="text-xl font-medium block mr-6">Ручной рассчет <br> стоимости</span>
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
                               v-model="form.courierCommission"
                               type="number"
                               min="0"
                               max="100"
                               class="w-56 text-gray-800 placeholder-gray-800 rounded-md border border-solid border-gray-200 leading-tight p-3"/>
                    </div>
                </div>
                <div class="py-4 flex items-center">
                    <div class="flex text-xl font-medium text-gray-900 mr-24">
                        <span class="mr-2">Итого: </span><span>{{ totalPrice }} сом</span>
                    </div>
                    <button v-if="hasAddressesCoordinates && !form.deliveryCost && !form.manualCalculationMode"
                            class="leading-tight py-3 bg-gray-500 text-white rounded-md px-7"
                            @click.prevent="calculateDeliveryPrice">
                        Рассчитать стоимость доставки
                    </button>
                    <button v-else type="submit"
                            class="w-80 leading-tight py-3 bg-orange-400 text-white rounded-md px-7"
                            :disabled="form.processing">
                        Сохранить заказ
                    </button>
                </div>
            </form>
        </div>
        <div slot="modals">
            <modal name="orderMenu" close-text confirmed>
                <template v-slot:title>
                    <div class="flex items-center" slot="title">
                        <span class="text-xl font-medium mr-36">Меню заведения</span>
                        <div class="flex mr-6">
                            <input type="text" v-model="menuSearch"
                                   class="bg-gray-100 w-56 px-3 rounded-l-md leading-tight border-gray-100 focus:border-gray-100 border border-solid"
                                   placeholder="Поиск блюд">
                            <button
                                class="bg-orange-400 text-white py-3 px-7 rounded-r-md leading-tight border border-solid border-orange-400">
                                Найти
                            </button>
                        </div>
                    </div>
                </template>
                <template v-slot="{data: {establishment}}">
                    <div class="pr-1">
                        <div class="py-2 mb-2 flex pt-4">
                            <div class="w-7/12 content-left border-r-2 border-gray-200 pr-5 max-h-96 overflow-y-scroll">
                                <div v-for="(item, i) in filteredMenuItems"
                                     class="w-full flex items-center pt-2 pb-3 border-b border-gray-200">
                                    <div class="w-2/12 flex items-start">
                                        <checkbox class="justify-between mb-2 mr-3"
                                                  v-model="item.selected"
                                                  :value="`value-${item.id}`"/>
                                        <span class="text-sm w-8">{{ i + 1 }}</span>
                                    </div>
                                    <div class="flex justify-between w-10/12">
                                        <div class="w-6/12 pr-2">
                                            <p class="text-sm leading-none">{{ item.name }}</p>
                                        </div>
                                        <div class="w-3/12 pr-2">
                                            <p class="text-sm leading-none">{{ item.partner_product_category.product_category.name }}</p>
                                        </div>
                                        <div class="w-3/12 flex justify-end">
                                            <p class="text-sm leading-none">{{ item.discount_price }} сом</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-5/12 content-left max-h-96 overflow-y-scroll pl-5 pr-4">
                                <div class="flex justify-end pb-3">
                                    <button class="text-sm text-orange-400"
                                            @click.prevent="clearSelectedProducts">Очистить выбор</button>
                                </div>
                                <div v-for="item in selectedProducts"
                                     class="w-full pt-2 pb-3 flex justify-between items-center border-b border-gray-200">
                                    <div class="w-7/12 flex">
                                        <div class="w-3/12">
                                            <checkbox v-model="item.selected"
                                                      :value="`value-${item.id}`"/>
                                        </div>
                                        <div class="w-9/12 pr-2">
                                            <p class="text-sm leading-none">{{ item.name }}</p>
                                        </div>
                                    </div>
                                    <counter v-model="item.quantity"></counter>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex py-1 justify-between items-center">
                        <div class="flex text-lg">
                            <span class="mr-2">Выбрано: </span>
                            <span>{{ selectedProducts.length }} позиций ({{ selectedProductsPrice }} сом)</span>
                        </div>
                        <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7"
                                @click.prevent="addProducts(establishment)">Готово</button>
                    </div>
                </template>
            </modal>
            <modal name="applyDiscounts" close-text>
                <template v-slot:title>
                    <div class="flex items-center" slot="title">
                        <span class="text-xl font-medium mr-36">Доступные акции</span>
                        <div class="flex mr-6">
                            <input type="text" v-model="discountSearch"
                                   class="bg-gray-100 w-56 px-3 rounded-l-md leading-tight border-gray-100 focus:border-gray-100 border border-solid"
                                   placeholder="Поиск акции">
                            <button
                                class="bg-orange-400 text-white py-3 px-7 rounded-r-md leading-tight border border-solid border-orange-400">
                                Найти
                            </button>
                        </div>
                    </div>
                </template>
                <template v-slot="{data: {establishment}}">
                    <div class="pr-1">
                        <div class="py-2 mb-2">
                            <div v-for="(item, i) in filteredDiscountItems" :key="item.id"
                                 class="flex items-center py-4 justify-between border-b border-solid border-gray-200">
                                <div class="w-1/2 flex">
                                    <span class="text-sm w-8">{{ i + 1 }}</span>
                                    <span class="text-sm">{{ item.title }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm">{{ item.type_label }}</span>
                                </div>
                                <div>
                                    <checkbox class="justify-between mb-2 mr-12"
                                              :class="{'opacity-50': item.applied}"
                                              :disabled="item.applied"
                                              v-model="item.selected"
                                              :value="`value-${item.id}`"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex py-1 justify-between items-center">
                        <div class="flex text-lg">
                            <span class="mr-2">Выбрано: </span>
                            <span>{{ selectedDiscounts.length }} акций</span>
                        </div>
                        <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7"
                                @click.prevent="addDiscounts(establishment)">
                            Добавить
                        </button>
                    </div>
                </template>
            </modal>
            <modal name="onlinePaymentMethods" close-text>
                <online-payment-methods
                    :order-cost="totalPrice"
                    @update-it-should-send-details="updateItShouldSendBankDetails"
                    @update-selected-payment-method="updateSelectedPaymentMethod">
                </online-payment-methods>
            </modal>
            <modal name="createOrderSuccessfully">
                <div class="py-4 mb-2 flex flex-col items-center px-10">
                    <icon name="green-check" class="mb-4"></icon>
                    <div class="text-center w-32">
                        <span class="font-medium">Заказ успешно добавлен</span>
                    </div>
                </div>
            </modal>
            <modal class="confirmation" name="question" close-text>
                <div class="flex flex-col items-center px-10">
                    <div class="w-12 h-12 mb-5">
                        <icon name="remove" class="h-auto fill-current mr-2"></icon>
                    </div>
                    <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                        <span class="leading-none">Вы уверены</span>
                        <span class="leading-none">что хотите выполнить это действие?</span>
                    </p>
                </div>
                <div class="flex justify-center items-center px-5">
                    <button class="text-orange-400 text-base mr-10" @click.prevent="$modal.confirm()">Да</button>
                    <button @click="$modal.close('question')" class="text-orange-400 text-base ml-10">Нет</button>
                </div>
            </modal>
        </div>
        <div slot="modals">
            <div class="modal">
                <customer-card :order-status-types="orderStatusTypes"/>
            </div>
        </div>
    </app-layout-map>
</template>

<script>
import Addresses from '@/Mixins/addresses'
import AppLayout from '@/Layouts/AppLayout'
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'
import JetDropdown from '@/Jetstream/Dropdown'
import {TheMask} from 'vue-the-mask'
import CatalogApi from '@/Apis/CatalogApi'
import axios from 'axios'
import OnlinePaymentMethods from '@/Components/OnlinePaymentMethods'
import {debounce} from 'lodash'
import OrderType from '@/Components/Orders/OrderType'
import CustomerCard from '@/Components/Modals/CustomerCard'
import { Inertia } from '@inertiajs/inertia'
import OrdersMixins from '@/Mixins/orders-mixins'
import ClickOutside from 'vue-click-outside'
import { VueEditor } from "vue2-editor";

export default {
    components: {
        AppLayout,
        JetDropdown,
        TheMask,
        OnlinePaymentMethods,
        VDatePicker,
        OrderType,
        CustomerCard,
        VueEditor,
    },
    directives: {
        ClickOutside
    },
    props: {
        orderNumber: Number,
        paymentTypes: {
            default: () => ({}),
            type: Object
        },
        pickupAddressTypes: {
            default: () => ({}),
            type: Object
        },
        deliveryTypes: Object,
        phone: String,
        orderStatusTypes: Object,
    },
    data() {
        return {
            commentToolbar: [
                [{ list: "ordered" }],
            ],
            unprocessableEntityError: false,
            orderDeliveryTypes: this.deliveryTypes,
            showClientInfo: false,
            addressDropdown: null,
            geocodeAddressDropdown: null,
            geocodeAddresses: [],
            geocodePickupAddressDropdown: null,
            geocodePickupAddresses: [],
            categories: [],
            selectedCategory: null,
            selectedEstablishment: null,
            value: null,
            countValue: 0,
            quickNotes: ['Без зелени', 'Без приборов', 'Острое'],
            selectedDeliveryTime: null,
            establishments: [],
            selectedMenuItems: [],
            selectedDiscountItems: [],
            menuSearch: '',
            discountSearch: '',
            pickupAddresses: [],
            partners: [],
            allPartners: [],
            couriers: [],
            isInBlackList: false,
            blacklistId: null,
            form: this.$inertia.form({
                client: {
                    name: null,
                    phone: this.phone,
                },
                comment: '',
                change: null,
                reportable: false,
                courierId: null,
                operatorId: this.$page.props.auth.user.id,
                isSoon: true,
                manualCalculationMode: false,
                courierCommission: null,
                sumToOffice: null,
                deliveryType: Object.keys(this.deliveryTypes)[0],
                deliveryTime: null,
                deliveryCost: null,
                paymentType: 'cash',
                withoutAddress: false,
                addresses: [],
                orderItems: [],
                itShouldSendBankDetails: false,
                selectedPaymentMethod: null,
                pickupAddresses: [],
            }),
            client: null,
            exitPreventer: () => {},
        }
    },

    computed: {
        hasAddressesCoordinates() {
            for(let i = 0; i < this.form.addresses.length; i++) {
                if(!this.form.addresses[i].latitude || !this.form.addresses[i].longitude) {
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
        orderAddresses() {
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
        availablePartners() {
            return this.partners.filter(partner => !this.establishments.find(establishment => establishment.id === partner.id))
        },
        filteredMenuItems() {
            return this.selectedMenuItems.filter(item => item.name.toLowerCase().includes(this.menuSearch))
        },
        filteredDiscountItems() {
            return this.selectedDiscountItems.filter(item => item.title.toLowerCase().includes(this.discountSearch))
        },
        selectedProducts() {
            return this.selectedMenuItems.filter(item => item.selected)
        },
        selectedProductsPrice() {
            return this.selectedProducts.reduce((total, product) => {
                return total + (product.present ? 0 : product.discount_price * product.quantity)
            }, 0)
        },
        selectedDiscounts() {
            return this.selectedDiscountItems.filter(item => item.selected)
        },
        courierEarnings() {
            return Math.round(this.form.deliveryCost * this.form.courierCommission)
        },
        totalPrice() {
            return this.establishments.reduce((total, current) => {
                return total + (current.products.reduce((total, current) => {
                    return total + (current.present ? 0 : (current.discount_price * current.quantity) + (current.dishes_price * current.dishes_quantity))
                }, 0))
            }, 0) + (this.form.deliveryCost ? parseInt(this.form.deliveryCost) : 0)
        },
        totalProductsPrice() {
            return this.establishments.reduce((total, current) => {
                return total + (current.products.reduce((total, current) => {
                    return total + (current.present ? 0 : (current.discount_price * current.quantity) + (current.dishes_price * current.dishes_quantity))
                }, 0))
            }, 0)
        },
        pickupTypes() {
            return Object.keys(this.pickupAddressTypes).map((key) => ({
                value: key,
                label: this.pickupAddressTypes[key],
            }))
        }
    },

    async created() {
        if (! this.form.addresses.length) {
            this.addAddress()
        }

        this.categories = await this.loadPartnersByCategoriesWithBranches()
        if (this.categories[0]) {
            this.partners =  this.categories[0].partners || []
            this.selectedCategory = this.categories[0]
        }

        await this.fetchActiveCouriers()
    },

    methods: {
        categorySearch(val) {
            this.categories.sort(function(a,b) {
                let bgnA = a.name.substr(0, val.length).toLowerCase();
                let bgnB = b.name.substr(0, val.length).toLowerCase();

                if (bgnA === val.toLowerCase()) {
                    if (bgnB !== val.toLowerCase()) return -1;
                } else if (bgnB === val.toLowerCase()) return 1;
                return a < b ? -1 : (a > b ? 1 : 0);
            });
        },
        allProductsTotalPrice(products) {
            return products.reduce((total, product) => {
                return total + this.productTotalPrice(product) + this.productTotalDishesPrice(product)
            }, 0)
        },
        productTotalPrice(product) {
            return product.discount_price * product.quantity
        },
        productTotalDishesPrice(product) {
            return product.dishes_price * product.dishes_quantity
        },
        hideClientInfo() {
            this.showClientInfo = false
        },
        hideAddressDropdown() {
            this.addressDropdown = null
            this.geocodeAddressDropdown = null
            this.geocodePickupAddressDropdown = null
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
                this.form.addresses[0].address = deliveryAddress.attributes.address
                this.form.addresses[0].latitude = deliveryAddress.attributes.latitude
                this.form.addresses[0].longitude = deliveryAddress.attributes.longitude
                this.form.addresses[0].comment = deliveryAddress.attributes.comment
                this.hideClientInfo()
            }
        },
        openAddressDropdown(index) {
            if(this.orderAddresses.length) {
                this.geocodeAddressDropdown = null
                this.addressDropdown = index
            }
        },
        selectAddress(address, index) {
            this.form.addresses[index].address = address.address
            this.form.addresses[index].latitude = address.latitude
            this.form.addresses[index].longitude = address.longitude
            this.addressDropdown = null
        },
        selectGeocodeAddress(address, geocodeAddress) {
            address.address = `${geocodeAddress.address} (${geocodeAddress.purpose})`

            if (geocodeAddress.type !== 'building') {
                address.address += ` - ${geocodeAddress.short}`
            }

            address.latitude = geocodeAddress.geocode.latitude
            address.longitude = geocodeAddress.geocode.longitude
            this.geocodeAddressDropdown = null
            this.geocodeAddresses = []
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
        quantityChanged(value, establishment) {
            this.clearDiscounts(establishment)
        },
        deleteAddress(index) {
            this.form.addresses.splice(index, 1)
        },
        addAddress(addressObject = {}, type = 'delivery') {
            if (addressObject.length) {
                this.form.addresses.push(addressObject)
            } else {
                this.form.addresses.push({
                    longitude: null,
                    latitude: null,
                    comment: null,
                    additionalInfo: null,
                    address: null,
                    type: type,
                })
            }
        },
        newPickupAddress(){
            return {
                longitude: null,
                latitude: null,
                comment: null,
                additionalInfo: null,
                address: null,
                type: 'pickup',
                isLargeSize: false,
                buyoutAmount: null,
                pickupAddressType: null,
            }
        },
        geocodeAddress: debounce(async function (address, index) {
            if(address.address) {
                this.addressDropdown = null

                const { data: addresses } = await axios.post('/maps/geocode', {
                    address: address.address
                })

                this.geocodeAddresses = addresses

                this.geocodeAddressDropdown = index
            }
        }, 500),
        geocodePickupAddress: debounce(async function (address, index) {
            if(address.address) {
                const { data: addresses } = await axios.post('/maps/geocode', {
                    address: address.address
                })

                this.geocodePickupAddresses = addresses;

                this.geocodePickupAddressDropdown = index
            }
        }, 500),
        selectEstablishment(option) {
            this.selectedEstablishment = option
        },
        async addPartner() {
            this.form.clearErrors('orderItems')

            const selectedBranch = {
                ...this.selectedEstablishment.branches[0],
                isNeedCutlery: false,
                numberOfPersons: null,
            }
            const menuItems = await CatalogApi.getCatalogForBranch(selectedBranch)

            const establishment = {
                ...this.selectedEstablishment,
                selectedBranch,
                menuItems,
                products: [],
                discounts: [],
            }
            this.establishments.push(establishment)

            this.updateDeliveryTypes()

            this.selectedEstablishment = null
        },
        updateDeliveryTypes() {
            if(this.establishments.find(establishment => establishment.selectedBranch.work_schedule == null)) {
                this.orderDeliveryTypes = {
                    'pre-order': 'В опр время'
                }
            }else {
                this.orderDeliveryTypes = this.deliveryTypes
            }
        },
        removePartner(index) {
            this.establishments.splice(index, 1)
        },
        async updateEstablishmentMenu(establishment) {
            establishment.selectedBranch = {
                ...establishment.selectedBranch,
                isNeedCutlery: false,
                numberOfPersons: null,
            }
            establishment.menuItems = await CatalogApi.getCatalogForBranch(establishment.selectedBranch)

            this.updateDeliveryTypes()
        },
        chooseMenuItems(establishment) {
            this.selectedMenuItems = establishment.menuItems.map(item => ({
                ...item,
                quantity: item.quantity || 1,
                dishes_quantity: item.quantity || 1,
                selected: item.selected || false,
                ...(establishment.products.find(product => product.id === item.id) || {})
            }))

            this.$modal.open('orderMenu', {establishment})
        },
        clearSelectedProducts() {
            this.selectedMenuItems = this.selectedMenuItems.map(item => ({
                ...item,
                selected: false,
            }))
        },
        async chooseDiscounts(establishment) {
            let discounts = await CatalogApi.getDiscountsForBranch(establishment)

            this.selectedDiscountItems = discounts.map(item => ({
                ...item,
                selected: item.applied || item.selected || false,
                ...(establishment.discounts.find(discount => discount.id === item.id) || {})
            }))

            this.$modal.open('applyDiscounts', {establishment})
        },
        addProducts(establishment) {
            this.clearDiscounts(establishment)
            establishment.products = this.selectedProducts.map(item => ({
                ...item,
                dishes_quantity: item.quantity
            }))

            this.$modal.close('orderMenu')
            this.selectedMenuItems = []
        },
        addDiscounts(establishment) {
            this.clearDiscounts(establishment)
            establishment.discounts = this.selectedDiscounts

            establishment.discounts.forEach(discount => {
                establishment.products = [...establishment.products,...discount.present_products]
            })

            this.$modal.close('applyDiscounts')
            this.selectedDiscountItems = []
        },
        clearDiscounts(establishment) {
            establishment.products = establishment.products.filter(product => !product.present)
            establishment.discounts = []
        },
        deleteProduct(establishment, productIndex) {
            establishment.products.splice(productIndex, 1)
            this.clearDiscounts(establishment)
        },
        selectCourier(courier) {
            this.form.courierId = courier.id
            this.updateCommissions(courier)
        },
        updateCommissions(courier) {
            if(courier && !this.form.manualCalculationMode) {
                this.form.sumToOffice = this.getSumToOffice(courier)
                this.form.courierCommission = this.getCourierCommission(courier)
            }
        },
        getSumToOffice(courier) {
            if(courier.calculation_type === 'salary') {
                return this.form.deliveryCost
            }
            if(courier.calculation_type === 'dynamic' && courier.dynamic_commission_calculation) {
                return this.getDynamicCommissionCalculationSumToOffice(courier.dynamic_commission_calculation)
            }
            return null;
        },
        getDynamicCommissionCalculationSumToOffice(dynamicCommissionCalculation) {
            let percentage = dynamicCommissionCalculation.sum_percentages_to_office.find(
                percentage => percentage.from <= this.form.deliveryCost && percentage.to >= this.form.deliveryCost
            )
            let sumToOffice = Math.round(this.form.deliveryCost * percentage.value / 100)

            return Math.min(dynamicCommissionCalculation.max_sum_to_office, sumToOffice)
        },
        getCourierCommission(courier) {
            if(courier.calculation_type === 'salary') {
                return courier.constant_courier_payment.base_cost_per_order
            }
            if(courier.calculation_type === 'dynamic' && courier.dynamic_commission_calculation) {
                return this.getDynamicCommissionCalculationCommission(courier.dynamic_commission_calculation)
            }
            return null;
        },
        getDynamicCommissionCalculationCommission(dynamicCommissionCalculation) {
            let surcharge = dynamicCommissionCalculation.surcharges.find(
                surcharge => surcharge.from <= this.form.deliveryCost && surcharge.to >= this.form.deliveryCost
            )

            return surcharge.sum + this.form.deliveryCost - this.form.sumToOffice
        },
        formatProductsForSubmit() {
            let products = []

            this.establishments.forEach(establishment => {
                establishment.products.forEach(product => products.push({
                    branchId: establishment.selectedBranch.crm_id,
                    name: product.name,
                    price: product.discount_price,
                    quantity: product.quantity,
                    externalProductId: product.id,
                    categoryType: product.partner_product_category.product_category.type,
                    dishesPrice: product.dishes_price,
                    present: product.present,
                    externalDiscountId: product.discount_id || null,
                    dishesQuantity: product.dishes_quantity
                }))
            })

            return products
        },
        formatBranchAddressesForSubmit() {
            let branchAddresses = []

            this.establishments.forEach(establishment => branchAddresses.push({
                longitude: establishment.selectedBranch.longitude,
                latitude: establishment.selectedBranch.latitude,
                comment: null,
                additionalInfo: null,
                address: establishment.selectedBranch.name,
                type: 'pickup',
                isNeedCutlery: establishment.selectedBranch.isNeedCutlery,
                numberOfPersons: establishment.selectedBranch.numberOfPersons,
                branchId: establishment.selectedBranch.crm_id,
            }))

            return branchAddresses
        },
        async calculateDeliveryPrice() {
            const branches = this.establishments
                .map(({ selectedBranch }) => selectedBranch.crm_id)

            if(branches.length) {
                try {
                    const { data: { cost } } = await axios.post('/api/maps/delivery-cost', {
                        branches,
                        addresses: this.form.addresses,
                        pickupAddresses: this.pickupAddresses,
                    })
                    this.unprocessableEntityError = false
                    this.form.deliveryCost = cost
                } catch(err) {
                    this.unprocessableEntityError = true
                }
            } else {
                try {
                    const { data: { cost } } = await axios.post('/api/maps/personal-delivery-cost', {
                        pickup_addresses: this.pickupAddresses.map(address => ({
                            longitude: address.longitude,
                            latitude: address.latitude,
                            buyout: address.buyoutAmount,
                            is_large_size: address.isLargeSize,
                            type: address.pickupAddressType?.value,
                        })),
                        delivery_addresses: this.form.addresses.map(address => ({
                            longitude: address.longitude,
                            latitude: address.latitude,
                        })),
                    })
                    this.unprocessableEntityError = false
                    this.form.deliveryCost = cost
                } catch(err) {
                    this.unprocessableEntityError = true
                }
            }

            this.updateCommissions(this.couriers.find(
                courier => courier.id === this.form.courierId
            ))
        },
        submitForm() {
            this.form.orderItems = this.formatProductsForSubmit()

            this.form.pickupAddresses = this.pickupAddresses.map((address) => {
                return {
                    ...address,
                    address: address.address ?? address.pickupAddressType['label'],
                    pickupAddressType: address.pickupAddressType['value']
                }
            })
            this.form.pickupAddresses = this.form.pickupAddresses.concat(this.formatBranchAddressesForSubmit())

            this.form.transform((data) => ({
                ...data,
                client: {
                    name: this.form.client.name,
                    phone: this.form.client.phone
                },
                amount: this.totalProductsPrice,
                deliveryTime: this.form.deliveryTime ? this.moment(this.form.deliveryTime).format('YYYY-MM-DD HH:mm') : '',
            }))

            this.exitPreventer()

            this.form.post('/orders', {
                onSuccess: () => {
                    this.form.reset()
                    this.$modal.open('createOrderSuccessfully')
                }
            })
        },
        toggleQuickEntry(checked, value) {
            if (!checked) {
                let comment = this.form.comment.replace(', ' + value, '')
                comment = comment.replace(value + ', ', '')
                this.form.comment = comment.replace(value, '')

                return
            }

            if (this.form.comment) {
                value = ', ' + value
            }

            this.form.comment += value
        },
        upIndexAddress(index) {
            this.form.addresses[index] = this.form.addresses.splice(index - 1, 1, this.form.addresses[index])[0]
        },
        downIndexAddress(index) {
            this.form.addresses[index] = this.form.addresses.splice(index + 1, 1, this.form.addresses[index])[0]
        },
        upIndexPickupAddress(index) {
            this.pickupAddresses[index] = this.pickupAddresses.splice(index - 1, 1, this.pickupAddresses[index])[0]
        },
        downIndexPickupAddress(index) {
            this.pickupAddresses[index] = this.pickupAddresses.splice(index + 1, 1, this.pickupAddresses[index])[0]
        },
        deletePickupAddress(index) {
            this.pickupAddresses.splice(index, 1)
        },
        categorySelected(option) {
            this.selectedCategory = option
            this.partners = option.partners
        },
        selectedPaymentType() {
            if (this.form.paymentType === 'online') {
                this.$modal.open('onlinePaymentMethods')
            }
        },
        checkPhoneNumber: debounce(async function (phone) {
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
        }, 2000),
        selectDeliveryType(type) {
            if (type === 'pre-order') {
                this.form.deliveryTime = Date.now()
                return this.$refs.datepicker[0].togglePopover()
            }

            this.form.deliveryTime = null
        },
        async fetchActiveCouriers() {
            await axios.get('/couriers/get/active').then(({ data }) => {
                this.couriers = data
            })
        },
        updateItShouldSendBankDetails(val) {
            this.form.itShouldSendBankDetails = val
        },
        updateSelectedPaymentMethod(val) {
            this.form.selectedPaymentMethod = val
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
                this.form.addresses = []
            } else if(!this.form.addresses.length) {
                this.addAddress()
            }
        },
        establishments: {
            deep: true,
            handler() {
                if (!this.form.manualCalculationMode) {
                    this.form.deliveryCost = null
                }
            },
        },
        'form.addresses': {
            deep: true,
            handler() {
                if (!this.form.manualCalculationMode) {
                    this.form.deliveryCost = null
                }
            },
        },
        pickupAddresses: {
            deep: true,
            handler() {
                if (!this.form.manualCalculationMode) {
                    this.form.deliveryCost = null
                }
            },
        },
    },
    mixins: [Addresses, OrdersMixins]
}
</script>

<style>

#create-orders .multiselect .multiselect__tags {
    padding-left: 0;
}

#create-orders .multiselect--active .multiselect__input, .multiselect__single {
    padding-left: 0;
}

#create-orders .multiselect__input {
    color: #211C1C;
}

#create-orders .multiselect__placeholder {
    color: #211C1C;
}

.order-menu {
    align-items: flex-end;
}
.order-menu .modal-block {
    margin-bottom: 0;
    border-radius: 0.75rem 0.75rem 0 0;
}
.content-left::-webkit-scrollbar {
    width: 4px;
    background: #E0E0E0;
}
.content-left::-webkit-scrollbar-thumb {
    background-color: #BFBFBF;
}
#create-orders .vc-select select {
    background-image: none;
}
.vc-popover-content-wrapper {
    z-index: 20 !important;
}
</style>
