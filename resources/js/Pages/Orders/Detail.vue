<template>
    <app-layout-map content-width="w-2/3">
        <div class="orders-detail">
            <div class="flex items-start justify-between py-7">
                <div class="flex">
                    <div class="mr-5">
                        <div class="text-lg mr-7">
                            <span>Курьер: </span>
                            <template>
                                <button v-if="! courier && ! $page.props.auth.user.isOrderOperator"
                                        class="text-orange-400"
                                        @click="assignCourier">
                                    Назначить курьера
                                </button>
                                <span v-else>{{ courier }}</span>
                            </template>
                        </div>
                        <div class="text-lg mr-7">
                            <span>Телефон: </span>
                            <span>{{ phone || '-' }}</span>
                        </div>
                        <div class="text-lg mr-7">
                            <span>Оператор: </span>
                            <span>{{ operator || '-' }}</span>
                        </div>
                        <div class="text-lg mr-7">
                            <span>{{ sources[order.data.attributes.source] || '-' }}</span>
                        </div>
                        <div class="text-lg mr-7">
                            <span>Оплата {{ paymentTypes[order.data.attributes.paymentType] }}</span>
                            <span v-if="order.data.relationships.paymentMethod"> ({{ order.data.relationships.paymentMethod.attributes.title }})</span>
                        </div>
                    </div>
                    <div v-if="order.data.relationships.comments.length" class="text-lg mr-7">
                        <button v-if="!showPartnerComments"
                                class="text-orange-400"
                                @click="showPartnerComments = true">
                            Посмотреть комментарий
                        </button>
                        <button v-else class="text-orange-400"
                                @click="showPartnerComments = false">
                            Скрыть комментарий
                        </button>
                    </div>
                </div>
                <button class="flex items-center" @click="back()">
                    <icon name="close" class="w-3 h-3 fill-current text-red-400 mr-2"/>
                    <span class="text-red-400">Закрыть</span>
                </button>
            </div>
            <div v-if="showPartnerComments" class="py-4">
                <h2 class="text-gray-900 text-xl font-medium mr-8 mb-2">Отзыв заведения</h2>
                <div v-for="comment in order.data.relationships.comments" :key="comment.id" class="flex mb-1">
                    <p>{{ comment.attributes.comment }}</p>
                </div>
            </div>
            <div v-if="order.data.relationships.reviews.length" class="py-4">
                <h2 class="text-gray-900 text-xl font-medium mr-8 mb-2">Отзыв клиента</h2>
                <div v-for="review in order.data.relationships.reviews" :key="review.id" class="flex mb-1">
                    <div class="mr-4 pt-[.125rem]">
                        <icon v-for="n in 5" :key="n" name="star"
                              class="w-4 h-4 h-auto inline-block mr-1 fill-current"
                              :class="[n <= parseInt(review.attributes.star) ? 'text-orange-400' : 'text-gray-300']">
                        </icon>
                    </div>
                    <p>{{ review.attributes.text }}</p>
                </div>
            </div>
            <div class="flex justify-between py-4 items-center">
                <h1 class="text-4xl font-bold text-gray-900" v-text="order.data.id"></h1>
                <div class="status flex items-center font-bold bg-white justify-between rounded min-w-56 leading-tight rounded-md mr-4 pl-2">
                    <multiselect v-model="selectedStatus"
                                 placeholder="Выбрать статус"
                                 class="status-multiselect pointer-events-none"
                                 track-by="label"
                                 :show-labels="false"
                                 label="label"
                                 :options="orderStatuses">
                        <template slot="singleLabel" slot-scope="props">
                            <span class="option__desc">
                                <span class="option__title" :style="`color: ${props.option.color}`">{{ props.option.label }}</span>
                            </span>
                        </template>
                        <template slot="option" slot-scope="props">
                            <div class="option__desc"><span class="option__title" :style="`color: ${props.option.color}`">{{ props.option.label }}</span></div>
                        </template>
                    </multiselect>
                </div>
            </div>
            <div class="pt-1 pb-6 border-b border-solid border-gray-200 mb-4 flex items-center">
                <ul class="flex mr-2">
                    <li class="list-disc list text-gray-400 mr-6">{{ itemsCount }} позиций</li>
<!--                    TODO: Add from_site attribute to database-->
<!--                    <li class="list-disc list text-gray-400 mr-6">Заказ с сайта</li>-->
                </ul>
                <button class="text-orange-400"
                        @click="$refs.refStatusesListModal.open(order.data.id)">
                    Открыть историю статусов
                </button>
            </div>
            <div class="py-4 flex justify-between items-end">
                <div>
                    <div v-if="canceledStatus">
                        <div class="flex justify-between pb-2">
                            <h2 class="text-gray-900 text-xl font-medium">Причина отмены</h2>
                        </div>
                        <div class="py-2 px-6 flex items-center justify-between">
                            <div class="flex pr-10">
                                <span class="text-lg text-gray-900 font-bold pl-3 py-1 border-l-2 border-solid border-orange-400 mr-8">
                                    {{ canceledStatus.attributes.comment }}
                                </span>
                                <div class="flex flex-wrap">
                                    <div class="w-8 h-8 mr-2 cursor-pointer"
                                         v-for="image in canceledStatus.relationships.media"
                                         v-viewer="{movable: false, navbar: false, toolbar: false}">
                                        <img class="w-full h-full object-cover" :src="image.url" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <button v-if="order.data.attributes.requestedToCancel"
                            class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7"
                            @click="$modal.open('cancelCustomerOrder')">Отменить заказ у клиента</button>
                </div>
            </div>
            <div class="py-4">
                <div class="flex justify-between">
                    <div class="flex">
                        <h2 class="text-gray-900 text-xl font-medium mr-8">Данные клиента</h2>
                        <button class="flex items-center" @click="updateClient">
                            <icon name="edit" class="w-4 h-auto fill-current text-orange-400 mr-2"/>
                            <span class="text-orange-400 font-bold">Изменить</span>
                        </button>
                    </div>

                    <a class="text-base cursor-pointer text-red-400 font-bold" @click="$modal.open('addToBlacklist')">Занести клиента в черный список</a>
                </div>
                <div class="flex py-2 px-6 mb-4">
                    <div class="flex flex-col mr-8">
                        <span class="text-sm text-gray-200 pl-3 mb-3">Фамилия Имя</span>
                        <span class="text-lg text-gray-900 font-bold pl-3 py-1 border-l-2 border-solid border-orange-400">
                            {{ order.data.relationships.client.attributes.name }}
                        </span>
                    </div>
                    <div class="flex flex-col mr-8">
                        <span class="text-sm text-gray-200 pl-3 mb-3">Номер телефона</span>
                        <span class="text-lg text-gray-900 font-bold pl-3 py-1">
                        {{ phone }}
                        </span>
                    </div>

                </div>
                <div class="flex mb-6">
                    <h2 class="text-gray-900 text-xl font-medium mr-8">Адреса</h2>
                    <button class="flex items-center items-center"
                            @click="createOrUpdateOrderAddress({attributes: {type: 'delivery'}})">
                        <icon name="plus" class="mr-2 w-4 h-4 block fill-current text-orange-400"/>
                        <span class="text-orange-400 font-bold">Добавить адрес доставки</span>
                    </button>
                    <button class="flex items-center items-center ml-8"
                            @click="createOrUpdateOrderAddress({attributes: {type: 'pickup'}})">
                        <icon name="plus" class="mr-2 w-4 h-4 block fill-current text-orange-400"/>
                        <span class="text-orange-400 font-bold">Откуда забрать</span>
                    </button>
                </div>
                <div class="flex mb-8" v-for="(address, index) in orderAddresses">
                    <span class="mr-2 pt-10">{{ index + 1 }}</span>
                    <div class="flex mr-4 items-start pt-10">
                        <button class="mr-1 disabled:text-gray-400 text-orange-400" @click.prevent="upIndexAddress(index)" :disabled="index < 1">
                            <icon name="long-arrow" class="w-3 h-3 inline-block transform rotate-90 fill-current"/>
                        </button>
                        <button class="disabled:text-gray-400 text-orange-400" @click.prevent="downIndexAddress(index)" :disabled="order.data.relationships.addresses.length - 1 == index">
                            <icon name="long-arrow" class="w-3 h-3 inline-block transform -rotate-90 fill-current"/>
                        </button>
                    </div>
                    <div class="flex w-full">
                        <div class="flex flex-col pr-10 w-4/12">
                            <span class="text-sm text-gray-200 pl-3 mb-3">{{ address.attributes.type === 'delivery' ? 'Куда доставить' : 'Откуда забрать' }}</span>
                            <span class="text-lg text-gray-900 font-bold pl-3 py-1" v-text="address.attributes.address"></span>
                        </div>
                        <div class="flex flex-col pr-10 w-4/12" v-if="address.attributes.buyoutAmount">
                            <span class="text-sm text-gray-200 mb-3">Сумма выкупа</span>
                            <teaser v-if="address.attributes.buyoutAmount" :maxLength="50" :text="`${address.attributes.buyoutAmount} сом`"></teaser>
                            <span v-else class="text-base text-gray-900 mr-1"></span>
                        </div>
                        <div class="flex flex-col pr-10 w-4/12">
                            <span class="text-sm text-gray-200 mb-3">
                                Доп инфо
                            </span>
                            <teaser v-if="address.attributes.additionalInfo" :maxLength="50" :text="address.attributes.additionalInfo"></teaser>
                            <span v-else class="text-base text-gray-900 mr-1"></span>
                        </div>
                        <div v-if="address.attributes.comment" class="flex flex-col w-4/12">
                            <span class="text-sm text-gray-200 mb-3">
                                Примечание
                            </span>
                            <div v-if="address.attributes.comment" v-html="address.attributes.comment"></div>
                            <span v-else class="text-base text-gray-900 mr-1"></span>
                        </div>
                        <div v-if="address.attributes.isLargeSize" class="flex flex-col pr-10 w-4/12">
                            <span class="text-sm text-gray-200 mb-3">Груз</span>
                            <teaser v-if="address.attributes.isLargeSize" :text="address.attributes.isLargeSize ? `Габаритный груз` : ''"></teaser>
                            <span v-else class="text-base text-gray-900 mr-1"></span>
                        </div>
                        <div v-if="address.attributes.pickupAddressType" class="flex flex-col pr-10 w-4/12">
                            <span class="text-sm text-gray-200 mb-3">Тип магазина</span>
                            <teaser v-if="address.attributes.pickupAddressType" :text="pickupAddressTypes[address.attributes.pickupAddressType]"></teaser>
                            <span v-else class="text-base text-gray-900 mr-1"></span>
                        </div>
                        <button class="flex items-center" @click="createOrUpdateOrderAddress(address)">
                            <icon name="edit" class="w-4 h-auto fill-current text-orange-400 mr-2"></icon>
                            <span class="text-orange-400 font-bold">Изменить</span>
                        </button>
                        <button class="flex items-center ml-1" @click="$modal.question().then(() => deleteOrderAddress(index))">
                            <!--<icon name="trash" class="w-4 h-auto fill-current text-orange-400 mr-2"></icon>-->
                            <span class="text-orange-400 font-bold">Удалить</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="py-3">
                <h2 class="text-gray-900 text-xl font-medium">Состав заказа</h2>
                <div class="pl-6 pt-6 mb-4">
                    <div v-for="(establishment, index) in establishments" class="mb-4">
                        <div class="flex mb-3">
                            <div class="flex items-center">
                                <div class="mr-10 min-w-64 bg-white">
                                    <multiselect v-model="establishment.selectedBranch"
                                                 placeholder="Выберите филиал"
                                                 track-by="name"
                                                 label="name"
                                                 :options="getBranches(establishment.id)"
                                                 :id="establishment.id"
                                                 @input="updateEstablishmentMenu(establishment)"
                                                 @select="onSelectBranch">
                                    </multiselect>
                                </div>
<!--                                <div class="flex text-lg mr-12">-->
<!--                                    <span>Заказ по счету: </span>-->
<!--                                    <span>10</span>-->
<!--                                </div>-->

<!--                                <button class="text-orange-400" @click="$modal.open('ordersApplyPromotion')">Применить акцию</button>-->
                            </div>
                            <div class="flex">
                                <button v-if="establishment.products.length"
                                        class="flex items-center mr-5"
                                        @click.prevent="chooseDiscounts(establishment)">
                                    <span class="text-orange-400">Акции</span>
                                </button>
                                <button class="flex items-center mr-5" @click="chooseMenuItems(establishment)">
                                    <icon name="edit" class="w-4 h-auto fill-current text-orange-400 mr-2"></icon>
                                    <span class="text-orange-400 font-bold">Изменить состав</span>
                                </button>
                                <button class="flex items-center" @click="deleteEstablishment(index)">
<!--                                    <icon name="edit" class="w-4 h-auto fill-current text-orange-400 mr-2"></icon>-->
                                    <span class="text-red-400 font-bold">Удалить заведение</span>
                                </button>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex w-full">
                                <span class="text-sm text-gray-200 w-1/12 pr-1">№</span>
                                <span class="text-sm text-gray-200 w-4/12 pr-1">Наименование</span>
                                <span class="text-sm text-gray-200 w-1/12 pr-1">Кол-во</span>
                                <span class="text-sm text-gray-200 w-1/12 pr-1">Кол-во кон.</span>
                                <span class="text-sm text-gray-200 w-2/12 pr-1">Цена за п/шт</span>
                                <span class="text-sm text-gray-200 w-2/12 pr-1">Цена за кон.</span>
                                <span class="text-sm text-gray-200 w-1/12 pr-1">Итого</span>
                            </div>
                        </div>
                        <div class="py-3">
                            <div class="w-full border-b border-gray-200 menu-list"
                                 v-for="(product, index) in establishment.products">
                                <div class="flex py-3">
                                    <span class="w-1/12 text-gray-900 text-lg leading-tight">{{ index + 1 }}</span>
                                    <span class="w-4/12 pr-1 text-gray-900 text-lg leading-tight">{{ product.name }}</span>
                                    <span class="w-1/12 pr-1 text-gray-900 text-lg leading-tight">{{ product.quantity }}</span>
                                    <span class="w-1/12 pr-1 text-gray-900 text-lg leading-tight">{{ product.dishesQuantity }}</span>
                                    <span v-if="product.present" class="w-5/12 pr-1"><icon name="gift" class="w-4 h-4 fill-current text-orange-400 inline-block"/></span>
                                    <span v-if="!product.present" class="w-2/12 pr-1 text-gray-900 text-lg leading-tight">{{ productPrice(product) }}</span>
                                    <span v-if="!product.present" class="w-2/12 pr-1 text-gray-900 text-lg leading-tight">{{ product.dishesPrice }}</span>
                                    <span v-if="!product.present" class="w-1/12 pr-1 text-gray-900 text-lg leading-tight">{{ productPrice(product) * product.quantity }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-lg font-medium">Количество столовых приборов: {{ cutleryQuantity(establishment.selectedBranch.id) }}</div>
                    </div>
                    <button class="flex items-center items-center"
                            @click="addBranch">
                        <icon name="plus" class="mr-2 w-4 h-4 block fill-current text-orange-400"/>
                        <span class="text-orange-400 font-bold">Добавить заведение</span>
                    </button>
                </div>
                <div class="flex justify-start font-medium text-lg">
                    <span class="mr-1">Итого:</span>
                    <span>{{ totalPrice }} сом</span>
                </div>
            </div>
            <div class="py-4 flex mb-2">
                <div class="mr-4">
                    <h2 class="text-gray-900 text-xl font-medium mb-5">Тип заказа</h2>
                    <div class="px-6">
                        <span class="border-l-2 border-solid border-orange-400 py-1 pl-3 text-lg font-medium text-gray-900">
                            <a href="#" @click.prevent="openOrderEditDialog">
                                {{ deliveryTypes[order.data.attributes.deliveryType || 'asap'] }}
                                <span v-if="order.data.attributes.deliveryType === 'pre-order'">
                                    ({{ moment(order.data.attributes.deliveryTime).format('HH:mm') }})
                                </span>
                            </a>
<!--                            TODO: deliveryTime-->
<!--                            к 01.02.21 в 15:00-->
                        </span>
                    </div>
                </div>
                <div class="mr-10">
                    <h2 class="text-gray-900 text-xl font-medium mb-5">Фотоотчет</h2>
                    <div class="px-6">
                        <span class="border-l-2 border-solid border-orange-400 py-1 pl-3 text-lg font-medium text-gray-900">Нет</span>
                    </div>
                </div>
                <div class="mr-4">
                    <h2 class="text-gray-900 text-xl font-medium mb-5">
                        <a href="#" @click.prevent="openOrderEditDialog">Сдача с</a>
                    </h2>
                    <div class="px-6">
                        <span class="border-l-2 border-solid border-orange-400 py-1 pl-3 text-lg font-medium text-gray-900">
                            {{ order.data.attributes.change || '-' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="py-4 flex mb-2 flex-wrap">
                <div class="mr-4 mb-2">
                    <checkbox value="manualCalculationMode"
                              v-model="form.manualCalculationMode"
                              :checked="form.manualCalculationMode"
                              class="justify-between mb-2 mr-12 items-start">
                        <span class="text-xl font-medium block mr-6">Ручной рассчет стоимости</span>
                    </checkbox>
                </div>
                <div class="flex">
                    <div class="mr-4">
                        <h2 class="text-xl font-medium text-gray-900 mb-3">Сумма доставки</h2>
                        <input v-if="form.manualCalculationMode"
                               class="border border-solid border-gray-150 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                               type="number"
                               required
                               v-model="form.deliveryCost">
                        <input v-else
                               class="border border-solid border-gray-150 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                               type="text"
                               :value="`${form.deliveryCost} сом`"
                               disabled>
                    </div>
                    <div class="mr-4">
                        <h2 class="text-xl font-medium text-gray-900 mb-3">Сумма в офис</h2>
                        <input v-if="form.manualCalculationMode"
                               class="border border-solid border-gray-150 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                               type="number"
                               required
                               v-model="form.sumToOffice">
                        <input v-else
                               class="border border-solid border-gray-150 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                               type="text"
                               :value="`${form.sumToOffice} сом`"
                               disabled>
                    </div>
                    <div class="mr-4">
                        <h2 class="text-xl font-medium text-gray-900 mb-3">Комиссия курьера</h2>
                        <input v-if="form.manualCalculationMode"
                               class="border border-solid border-gray-150 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                               type="number"
                               required
                               v-model="form.courierCommission">
                        <input v-else
                               class="border border-solid border-gray-150 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                               type="text"
                               :value="`${courierCommission} сом`"
                               disabled>
                    </div>
                </div>
            </div>
            <div class="py-4 mb-2">
                <h2 class="text-gray-900 text-xl font-medium mb-5">
                    <a href="#" @click.prevent="openOrderEditDialog">Комментарий от клиента</a>
                </h2>
                <div class="px-6">
                    <span class="border-l-2 border-solid border-orange-400 py-1 pl-3 text-lg font-medium text-gray-900">
                        {{ order.data.attributes.comment }}
                    </span>
                </div>
            </div>
            <div class="flex justify-start py-4 mb-6">
                <button v-if="! courier && ! $page.props.auth.user.isOrderOperator"
                        @click="assignCourier"
                        class="py-3 px-7 border border-solid border-yellow-400 bg-yellow-400 rounded-lg text-white mr-8">
                    Назначить курьера
                </button>
                <button class="py-3 px-7 border border-solid border-gray-400 rounded-lg text-gray-400 mr-8"
                        @click="update">
                    Обновить заказ
                </button>
                <button v-if="order.data.attributes.requestedToCancel" class="py-3 px-7 border border-solid border-red-400 bg-red-400 rounded-lg text-white mr-8"
                        @click="$modal.open('cancelRequestByCourier')">
                    Принять отмену заказа
                </button>
                <button class="py-3 px-7 border border-solid border-red-400 bg-red-400 rounded-lg text-white mr-8"
                        @click="$modal.open('orderCancel', {orderId: order.data.id})" :disabled="isCanceled">
                    {{ !isCanceled ? 'Отменить заказ' : 'Заказ отменен'}}
                </button>
<!--                <button class="py-3 px-7 border border-solid border-orange-400 bg-orange-400 rounded-lg text-white"-->
<!--                        @click="$modal.open('orderSuccessfullyAccepted')">-->
<!--                    Принять заказ-->
<!--                </button>-->
            </div>
        </div>
        <div slot="modals">
            <modal name="ordersFoodMenu" confirmed close-text>
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
                            <div class="w-7/12 content-left border-r-2 border-gray-150 pr-5 max-h-96 overflow-y-scroll">
                                <div v-for="(item, i) in filteredMenuItems"
                                     class="w-full flex items-center pt-2 pb-3 border-b border-gray-150">
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
                                     class="w-full pt-2 pb-3 flex justify-between items-center border-b border-gray-150">
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
                            <span>{{ selectedProducts.length }} позиций</span>
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
                                 class="flex items-center py-4 justify-between border-b border-solid border-gray-150">
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
            <modal name="orderSuccessfullyAccepted">
                <div class="py-4 mb-2 flex flex-col items-center px-10">
                    <icon name="green-check" class="mb-4"></icon>
                    <div class="text-center w-32">
                        <span class="font-medium">Заказ успешно принят</span>
                    </div>
                </div>
            </modal>
            <modal name="ordersApplyPromotion" close-text>
                <div class="flex items-center" slot="title">
                    <span class="text-xl font-medium mr-36">Применить акцию</span>
                </div>
                <div class="pt-5">
                    <checkbox v-for="stock in promotions" :value="stock.label" :key="stock.label" v-model="stock.value"
                              :class="{'border-orange-400': stock.value}"
                              class="justify-between mb-4 mr-12 items-start border-gray-200 border rounded-lg border-solid py-3 px-3 w-full">
                        <span class="block mr-6" :class="{'text-orange-400': stock.value}">{{ stock.label }}</span>
                    </checkbox>
                </div>
                <div class="flex justify-end">
                    <button class="py-3 px-7 border border-solid border-orange-400 bg-orange-400 rounded-lg text-white" @click="$modal.open('orderSuccessfullyAccepted')">Применить</button>
                </div>
            </modal>
            <modal name="cancelCustomerOrder" close-text>
                <div slot="title">
                    <span class="text-xl font-medium mr-36">Отменить заказ у клиента</span>
                </div>
                <div class="pb-4 pt-8">
                    <span class="text-lg">Причина отмены</span>
                    <div class="py-2 mb-6">
                        <textarea class="w-[42rem] rounded-md focus:border-orange-400 border-gray-200"></textarea>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex flex-wrap">
                            <div class="w-8 h-8 mr-2 cursor-pointer" v-viewer="{movable: false, navbar: false, toolbar: false}" v-for="n in 5">
                                <img class="w-full h-full object-cover" src="/images/picture-icon.png" alt="">
                            </div>
                        </div>
                        <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7" @click="$modal.close('cancelCustomerOrder')">Отменить</button>
                    </div>
                </div>
            </modal>

            <modal v-if="order.data.attributes.cancelRequestByCourier"
                   name="cancelRequestByCourier" close-text>
                <div slot="title">
                    <span class="text-xl font-medium mr-36">Принять отмену заказа</span>
                </div>
                <div class="pb-4 pt-8">
                    <span class="text-lg">Причина отмены</span>
                    <div class="py-2 mb-6">
                        {{ order.data.attributes.cancelRequestByCourier.status.reason }}
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex flex-wrap">
                            <div class="w-8 h-8 mr-2 cursor-pointer"
                                 v-viewer="{movable: false, navbar: false, toolbar: false}"
                                 v-for="image in order.data.attributes.cancelRequestByCourier.images">
                                <img class="w-full h-full object-cover" :src="`/storage/${image}`" alt="">
                            </div>
                        </div>
                        <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7"
                                @click="acceptOrderCancel">Принять отмену</button>
                    </div>
                </div>
            </modal>

            <cancel-order-modal @canceled="updateStatus" />

            <div id="blacklist">
                <div slot="modals">
                    <div class="modal">
                        <modal class="clients" name="addToBlacklist" close-text>
                            <blacklist-form :client="order.data.relationships.client.attributes"></blacklist-form>
                        </modal>
                        <modal name="userAddedSuccessfullyIntoBlacklist" class="successfull">
                            <div class="py-4 mb-2 flex flex-col items-center px-10">
                                <icon name="green-check" class="mb-4"></icon>
                                <div class="text-center w-48">
                                    <span class="font-medium">Пользователь успешно добавлен</span>
                                </div>
                            </div>
                        </modal>
                    </div>
                </div>
            </div>
            <modal name="orderAssignCourier" close-text>
                <active-couriers-table :order-id="order.data.id"></active-couriers-table>
            </modal>

            <client-data-dialog ref="refClientDataDialog" :phones="phones"/>
            <order-address-data-dialog ref="refOrderAddressDialog"
                                       :pickup-address-types="pickupTypes"/>
            <order-edit-dialog :delivery-types="deliveryTypes" ref="refOrderEditDialog" />
            <statuses-list-modal ref="refStatusesListModal" />
            <add-branch-dialog ref="branchDialog" />
            <confirm-remove-products-dialog ref="confirmRemoveProductDialog" />
            <confirm-non-working-establishments-dialog ref="confirmNonWorkingEstablishmentsDialog" />
        </div>
    </app-layout-map>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import VHeader from '@/Components/Header'
    import JetDropdown from '@/Jetstream/Dropdown'
    import CatalogApi from '@/Apis/CatalogApi'
    import statusColors from '../../utils/statusColors.js'
    import Teaser from '@/Components/Teaser'
    import BlacklistForm from '@/Components/Clients/BlacklistForm'
    import CancelOrderModal from '@/Components/Modals/CancelOrderModal'
    import ActiveCouriersTable from '@/Components/Couriers/ActiveCouriersTable'
    import { directive as viewer } from "v-viewer"
    import ClientDataDialog from '../../Components/Clients/ClientDataDialog';
    import OrderAddressDataDialog from '../../Components/Orders/OrderAddressDialog';
    import OrderEditDialog from '../../Components/Orders/OrderEditDialog';
    import StatusesListModal from "../../Components/Orders/StatusesListModal";
    import AddBranchDialog from "../../Components/Orders/AddBranchDialog";
    import {cloneDeep} from "lodash";
    import ConfirmRemoveProductsDialog from "../../Components/Orders/ConfirmRemoveProductsDialog";
    import ConfirmNonWorkingEstablishmentsDialog from "../../Components/Orders/ConfirmNonWorkingEstablishmentsDialog";

    export default {
        directives: {
            viewer: viewer({
                debug: true,
            }),
        },
        components: {
            ConfirmNonWorkingEstablishmentsDialog,
            ConfirmRemoveProductsDialog,
            AddBranchDialog,
            StatusesListModal,
            OrderAddressDataDialog,
            OrderEditDialog,
            ClientDataDialog,
            AppLayout,
            VHeader,
            JetDropdown,
            Teaser,
            BlacklistForm,
            CancelOrderModal,
            ActiveCouriersTable,
        },
        props: {
            phones: Array,
            order: Object,
            partners: Array,
            paymentTypes: Object,
            statuses: Object,
            deliveryTypesForStandardOrder: Object,
            deliveryTypesForPersonalCourier: Object,
            pickupAddressTypes: {
                type: Object,
                default: () => ([]),
            },
            sources: Object,
            requestedToCancelObject: Object,
        },
        data() {
            return {
                showPartnerComments: false,
                selectedStatus: '',
                statusColors: statusColors,
                establishments: [],
                selectedMenuItems: [],
                selectedDiscountItems: [],
                menuSearch: '',
                discountSearch: '',
                promotions: [
                    {
                        label: 'Бесплатная доставка',
                        value: false
                    },
                    {
                        label: 'Скидка 15%',
                        value: false
                    }

                ],
                menuItems: null,
                countValue: 0,
                oldBranch: null,
                form: this.$inertia.form({
                    change: 0,
                    comment: '',
                    deliveryTime: null,
                    deliveryType: null,
                    orderItems: [],
                    addresses: [],

                    client: {
                        name: this.order.data.relationships.client.attributes.name,
                        phone: this.order.data.attributes.clientPhone
                    },
                    manualCalculationMode: false,
                    deliveryCost: this.order.data.attributes?.deliveryCost,
                    courierCommission: this.order.data.attributes?.courierCommission,
                    sumToOffice: this.order.data.attributes?.sumToOffice,
                })
            }
        },
        created() {
            this.updateStatus()

            this.establishments = this.order.data.relationships.branches.map(branch => ({
                id: branch.relationships.partner.id,
                selectedBranch: {
                    id: branch.id,
                    name: branch.attributes.name,
                },
                menuItems: [],
                products: branch.relationships.orderItems.map(product => ({
                    id: product.id,
                    externalId: product.attributes.externalProductId,
                    name: product.attributes.name,
                    price: product.attributes.price,
                    quantity: product.attributes.quantity,
                    categoryType: product.attributes.category_type,
                    present: product.attributes.present,
                    externalDiscountId: product.attributes.externalDiscountId,
                    selected: !product.attributes.present,
                    dishesPrice: product.attributes.dishesPrice,
                    dishesQuantity: product.attributes.dishesQuantity,
                })),
                discounts: [],
            }))
        },
        computed: {
            orderAddresses() {
                return this.order.data.relationships.addresses.filter(address => !address.attributes.branchId)
            },
            deliveryTypes() {
                return this.order.data.attributes.type === 'standard'
                    ? this.deliveryTypesForStandardOrder
                    : this.deliveryTypesForPersonalCourier
            },
            canceledStatus() {
                return this.order.data.relationships.statuses.find(status => status.attributes.name === 'canceled-by-courier')
            },
            phone() {
                return '+' + this.currentClientPhone
            },
            currentClientPhone ()
            {
                return this.order.data.attributes.clientPhone ||
                    this.order.data.relationships.client.attributes.phone
            },
            address() {
                const address = this.order.data.relationships.addresses[0]

                return address ? address.attributes.address : null
            },
            itemsCount() {
                return this.order.data.relationships.branches
                    .reduce((carry, branch) => carry + branch.relationships.orderItems.length, 0)
            },
            totalPrice() {
                return this.establishments.reduce((total, current) => {
                    return total + (current.products.reduce((total, current) => {
                        return total + (current.present ? 0 : current.price * current.quantity) + (current.dishesPrice * current.dishesQuantity)
                    }, 0))
                }, 0)
            },
            orderStatuses() {
                return Object.keys(this.statuses).map(value => ({
                    value,
                    label: this.statuses[value],
                    color: this.statusColors[value]
                }))
            },
            courierCommission() {
                const order = this.order.data.attributes

                return +order.courierCommission
            },
            courier() {
                const courier = this.order.data.relationships.courier

                return courier ? courier.attributes.fullName : null
            },
            operator() {
                const operator = this.order.data.relationships.operator

                return operator ? operator.attributes.name : null
            },
            isCanceled() {
                return this.order.data.attributes.status === 'canceled-by-operator'
            },
            filteredMenuItems() {
                return this.selectedMenuItems.filter(item => item.name.toLowerCase().includes(this.menuSearch))
            },
            filteredDiscountItems() {
                return this.selectedDiscountItems.filter(item => item.title.toLowerCase().includes(this.discountSearch))
            },
            selectedProducts() {
                return this.selectedMenuItems.filter(item => item.selected && item.quantity > 0)
            },
            selectedDiscounts() {
                return this.selectedDiscountItems.filter(item => item.selected)
            },
            pickupTypes() {
                return Object.keys(this.pickupAddressTypes).map(value => ({
                    value,
                    label: this.pickupAddressTypes[value]
                }))
            }
        },
        methods: {
            cutleryQuantity(branchId) {
                let address = this.order.data.relationships.addresses.find(address => address.attributes.branchId === branchId)
                return address ? address.attributes.numberOfPersons : 0
            },
            onSelectBranch(data, id) {
                const establishment = this.establishments.find(establishment => establishment.id === id)
                this.oldBranch = cloneDeep(establishment.selectedBranch)
            },
            addBranch() {
                this.$refs.branchDialog.open().then((partner) => {
                    const establishment = {
                        id: partner.crm_id,
                        selectedBranch: {
                            id: partner.branches[0].crm_id,
                            name: partner.branches[0].name
                        },
                        menuItems: [],
                        discounts: [],
                        products: []
                    }
                    this.establishments.push(establishment)

                    this.calculateDeliveryPrice()
                })
            },
            deleteEstablishment(index) {
                this.$modal.question().then(() => {
                    this.establishments.splice(index, 1)
                    this.calculateDeliveryPrice()
                })
            },
            openOrderEditDialog() {
                this.$refs.refOrderEditDialog.open({
                    change: this.order.data.attributes.change,
                    comment: this.order.data.attributes.comment,
                    deliveryTime: this.order.data.attributes.deliveryTime,
                    deliveryType: this.order.data.attributes.deliveryType
                }).then(data => {
                    this.order.data.attributes.change = data.change
                    this.order.data.attributes.comment = data.comment
                    this.order.data.attributes.deliveryTime = data.deliveryTime
                    this.order.data.attributes.deliveryType = data.deliveryType
                })
            },
            async calculateDeliveryPrice() {
                const relationships = this.order.data.relationships
                const {data: {cost}} = await axios.post('/api/maps/delivery-cost', {
                    branches: this.establishments.map(establishment => establishment.selectedBranch.id),
                    addresses: relationships
                        .addresses
                        .filter(address => address.attributes.type === 'delivery')
                        .map(address => address.attributes),
                    pickupAddresses: relationships
                        .addresses
                        .filter(address => address.attributes.type === 'pickup')
                        .map(address => address.attributes)
                })
                this.form.deliveryCost = cost

            },
            updateClient() {
                this.$refs.refClientDataDialog.open({
                    name: this.order.data.relationships.client.attributes.name,
                    phone: this.currentClientPhone,
                })
                    .then((data) => {
                        this.order.data.attributes.clientPhone = data.phone
                        this.order.data.relationships.client.attributes.name = data.name
                    })
            },
            deleteOrderAddress(index) {
                this.order.data.relationships.addresses.splice(index, 1)
                if (! this.order.data.attributes.manualCalculationMode) {
                    this.calculateDeliveryPrice()
                }
            },
            createOrUpdateOrderAddress(address = {attributes: {}}) {
                this.$refs.refOrderAddressDialog.open(address)
                    .then((address) => {
                        const addresses = this.order.data.relationships.addresses
                        if (address.id) {
                            const index = addresses.findIndex(addr => addr.id === address.id)
                            addresses.splice(index, 1, address)
                        } else {
                            addresses.push(address)
                        }
                        if (! this.order.data.attributes.manualCalculationMode) {
                            this.calculateDeliveryPrice()
                        }
                    })
            },
            productPrice(product) {
                return product.hasOwnProperty('discount_price') ? product.discount_price : product.price
            },
            selectStatus(status) {
                this.selectedStatus = status
            },
            updateStatus() {
                this.selectedStatus = this.orderStatuses.find(
                    status => status.value === this.order.data.attributes.status
                )
            },
            selectOption(index, option) {
                this.menuItems[index].selectedOption = option
            },
            getBranches(partnerId) {
                const partner = this.partners.find(
                    partner => partner.id === partnerId
                )

                return partner?.branches
            },
            async updateEstablishmentMenu(establishment) {
                const menuItems = await CatalogApi.getCatalogForBranch(establishment.selectedBranch)

                const missingItems = establishment.products.filter(product => {
                    return ! this.someProduct(menuItems, product)
                })

                if (missingItems.length) {
                    try {
                        await this.$refs.confirmRemoveProductDialog.open({
                            branch: establishment.selectedBranch.name,
                            items: missingItems
                        })
                        establishment.products = establishment.products.filter(product => {
                            return this.someProduct(menuItems, product)
                        })
                    } catch (e){
                        establishment.selectedBranch = cloneDeep(this.oldBranch)
                        return
                    }
                }

                establishment.menuItems = menuItems

                await this.calculateDeliveryPrice()
            },
            someProduct(items, product) {
                return items.some(item => {
                    return product.externalId === undefined
                        ? item.id === product.id
                        : item.id === product.externalId
                })
            },
            async chooseMenuItems(establishment) {
                await this.updateEstablishmentMenu(establishment)

                this.selectedMenuItems = establishment.menuItems.map(item => {
                    return ({
                        ...item,
                        quantity: item.quantity || 1,
                        selected: item.selected || false,
                        ...(establishment.products.find(product => product.externalId === item.id) || {}),
                        present: false,
                    })
                })

                this.$modal.open('ordersFoodMenu', {establishment})
            },
            clearSelectedProducts() {
                this.selectedMenuItems = this.selectedMenuItems.map(item => ({
                    ...item,
                    selected: false,
                }))
            },
            async chooseDiscounts(establishment) {
                let discounts = await CatalogApi.getDiscountsForBranch(establishment)

                establishment.discounts = discounts.filter((discount) => {
                    return establishment.products.map(product => product.externalDiscountId || product.discount_id).includes(discount.id)
                })

                this.selectedDiscountItems = discounts.map(item => ({
                    ...item,
                    ...(establishment.discounts.find(discount => discount.id === item.id) || {}),
                    selected: item.selected || establishment.discounts.map(discount => discount.id).includes(item.id),
                }))

                this.$modal.open('applyDiscounts', {establishment})
            },
            addProducts(establishment) {
                this.clearDiscounts(establishment)
                establishment.products = this.selectedProducts.map(item => ({
                    ...item,
                    dishes_quantity: item.quantity,
                    dishesPrice: item.dishes_price,
                    dishesQuantity: item.quantity,
                }))

                this.$modal.close('ordersFoodMenu')
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
            formatProductsForSubmit() {
                let products = []

                this.establishments.forEach(establishment => {
                    establishment.products.forEach(product => products.push({
                        orderItemId: product.id,
                        branchId: establishment.selectedBranch.id,
                        name: product.name,
                        price: product.discount_price ?? product.price,
                        quantity: product.quantity,
                        externalProductId: product.externalId ?? product.id,
                        categoryType: product.partner_product_category?.product_category?.type ?? product.categoryType,
                        dishesPrice: product.dishesPrice ?? product.dishes_price,
                        dishesQuantity: product.dishesQuantity ?? product.dishes_quantity,
                        present: product.present,
                        externalDiscountId: product.externalDiscountId ?? product.discount_id
                    }))
                })

                return products
            },
            formatAddressesForSubmit() {
                return this.order.data.relationships.addresses.map(address => {
                    const data = {
                        address: address.attributes.address,
                        latitude: address.attributes.latitude,
                        longitude: address.attributes.longitude,
                        type: address.attributes.type,

                        additionalInfo: address.attributes.additionalInfo,
                        comment: address.attributes.comment
                    }

                    if (address.attributes.type === 'pickup') {
                        data.pickupAddressType = address.attributes.pickupAddressType
                        data.buyoutAmount = address.attributes.buyoutAmount
                    }

                    if (address.id) {
                        data.addressId = address.id
                    }

                    return data
                })
            },
            update() {
                this.$modal.question().then(async () => {
                    const isPersonal = this.order.data.attributes.type === 'courier'
                    if (! isPersonal) {
                        this.form.orderItems = this.formatProductsForSubmit()
                    }
                    this.form.addresses = this.formatAddressesForSubmit()
                    this.form.change = this.order.data.attributes.change
                    this.form.comment = this.order.data.attributes.comment
                    this.form.deliveryTime = this.order.data.attributes.deliveryTime
                    this.form.deliveryType = this.order.data.attributes.deliveryType
                    this.form.client = {
                        name: this.order.data.relationships.client.attributes.name,
                        phone: this.currentClientPhone
                    }
                    try {
                        await this.checkingTheOpeningHoursOfEstablishments()
                    } catch (e) {
                        return
                    }

                    this.form.put(`/orders/${isPersonal ? 'personal/' : ''}${this.order.data.id}`)
                })
            },
            async checkingTheOpeningHoursOfEstablishments() {
                const branchIds = this.establishments.map(
                  establishment => establishment.selectedBranch.id
                )
                const { data } = await axios.post('/partners/check-schedule', {
                    branch_ids: branchIds,
                    date: this.order.data.attributes.deliveryType === 'asap'
                        ? (new Date).toISOString()
                        : this.order.data.attributes.deliveryTime
                })
                if (data.check === 0) {
                    try{
                        await this.$refs.confirmNonWorkingEstablishmentsDialog.open(data.branches)
                    } catch (e) {
                        throw e
                    }
                }
            },
            upIndexAddress(index) {
                this.order.data.relationships.addresses[index] = this.order.data.relationships.addresses.splice(index - 1, 1, this.order.data.relationships.addresses[index])[0]
            },
            downIndexAddress(index) {
                this.order.data.relationships.addresses[index] = this.order.data.relationships.addresses.splice(index + 1, 1, this.order.data.relationships.addresses[index])[0]
            },
            assignCourier() {
                this.$modal.open('orderAssignCourier')
            },
            async acceptOrderCancel() {
                await this.$inertia.post(`/orders/${this.order.data.id}/accept-canceling`)
                    .then(() => {
                        this.$modal.close('cancelRequestByCourier')
                    })
            }
        }
    }
</script>

<style>
    .history-table:last-child {
        border: none;
    }
    .orders-detail .menu-list:last-child {
        border-bottom: hidden;
    }
    .orders-detail .list:first-child {
        list-style: none;
    }
    .orders-detail .multiselect .multiselect__tags {
        padding-left: 0;
    }
    .orders-detail .multiselect--active .multiselect__input, .multiselect__single {
        padding-left: 0;
    }
    .orders-detail .status .multiselect .multiselect_select {
        display: none;
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
    .multiselect .multiselect__content-wrapper .multiselect__content {
        max-height: 140px;
        overflow-y: scroll;
    }
    .multiselect .multiselect__content-wrapper .multiselect__content::-webkit-scrollbar {
        width: 5px;
        background: #D0D0D0;
    }
    .multiselect .multiselect__content-wrapper .multiselect__content::-webkit-scrollbar-thumb {
        background: #E4E4E4;
    }
    #blacklist .modal .modal-block{
        width: 42%;
    }
    #blacklist .modal .successfull .modal-block{
        border-radius: 0.75rem;
        width: auto;
    }
</style>
