<template>
    <app-layout-map content-width="w-10/12">
        <div id="courier-detail" class="pr-16 pb-10">
            <div class="pt-16 flex mb-12">
                <div class="w-4/12 pr-6">
                    <div class="border-2 border-gray-300 h-full rounded-lg px-5 py-5 flex flex-col">
                        <h2 class="text-base font-medium text-gray-400 mb-3">Данные курьера</h2>
                        <span class="text-xl font-semibold text-gray-800 mb-3">{{ courier.full_name }}</span>
                        <div class="flex items-center mb-3">
                            <span class="text-sm font-medium text-gray-400 mr-3">Статус:</span>
                            <div class="w-40">
                                <multiselect v-model="selectedStatus"
                                             placeholder="Статус"
                                             track-by="value"
                                             label="label"
                                             :options="sortStatus">
                                </multiselect>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-400 mr-3">Телефон:</span>
                            <div class="flex text-lg font-medium  items-center">
                                <span class="text-gray-900">{{ courier.phone }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-4/12 px-3 ">
                    <div class="border-2 border-gray-300 h-full rounded-lg px-5 py-5 flex flex-col">
                        <h2 class="text-base font-medium text-gray-400 mb-3">{{ transports[courier.type] }}</h2>
                        <span class="text-xl font-semibold text-gray-800 mb-3">
                            {{ courier.type !== 'car' ?  courier.type === 'scooter' ? 'На скутере' : 'Пешком' : 'На машине'  }}
                        </span>
                        <div class="flex items-center mb-3">
                            <h3 class="text-sm font-medium text-gray-400 mr-3">Авто:</h3>
                            <div class="flex items-center">
                                <span class="text-lg font-medium text-gray-900">{{ courier.transport && courier.transport.model ? courier.transport.model : '-' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <h3 class="text-sm font-medium text-gray-400 mr-3">Гос номер:</h3>
                            <div class="flex items-center">
                                <span class="text-lg font-medium text-gray-900">{{ courier.transport && courier.transport.license_plate ? courier.transport.license_plate : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-4/12 pl-6">
                    <div class="border-2 border-gray-300 h-full rounded-lg px-5 py-5 flex flex-col">
                        <h2 class="text-base font-medium text-gray-400 mb-3">Статус взноса</h2>
                        <span class="text-xl font-semibold text-green-700 mb-3">{{ courier.is_contributed ? 'Оплачен' : 'Не оплачен' }}</span>
                        <div class="flex items-center mb-3">
                            <h3 class="text-sm font-medium text-gray-400 mr-3">Срок:</h3>
                            <div class="flex items-center">
                                <span class="text-lg font-medium text-gray-900">{{ courier.transport && courier.transport.year_issue ? courier.transport.year_issue : '-' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <h3 class="text-sm font-medium text-gray-400 mr-3">Гос номер:</h3>
                            <div class="flex items-center">
                                <span class="text-lg font-medium text-gray-900">{{ courier.transport && courier.transport.license_plate ? courier.transport.license_plate : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-between pb-8 border-b-2 border-gray-300 mb-10">
                <div class="flex">
                    <button class="flex items-center text-orange-400 text-base mr-10"
                            @click="$modal.open('editCourierData')">
                        <icon name="editing" class="w-5 h-5 h-auto inline-block fill-current text-orange-400 mr-3"></icon>
                        Редактирование данных
                    </button>
                    <button v-if="courier.is_blocked" class="flex items-center text-green-400 text-base"
                            @click.prevent="activateCourier">
                        <icon name="check" class="w-5 h-5 h-auto inline-block fill-current text-green-400 mr-3"></icon>
                        Активировать курьера
                    </button>
                    <button v-else class="flex items-center text-red-400 text-base"
                            @click="$modal.open('blockingReason')">
                        <icon name="block" class="w-5 h-5 h-auto inline-block fill-current text-red-400 mr-3"></icon>
                        Заблокировать курьера
                    </button>
                </div>
                <button class="text-white text-base px-10 py-4 rounded-lg bg-orange-400" @click="$modal.open('assignCourier')">Назначить заказ</button>
            </div>
            <div class="flex justify-between mb-10">
                <div class="flex flex-col pr-10">
                    <span class="text-base text-gray-200 mb-2">График работы</span>
                    <span class="text-base text-gray-900 font-medium">{{ getCourierWeekDays() }} с {{ getFirstWorkHour() }}</span>
                </div>
                <div class="flex flex-col pr-10">
                    <span class="text-base text-gray-200 mb-2">Спец одежда</span>
                    <span class="text-base text-gray-900 font-medium">{{ courier.is_set_outfit_kit ? 'Выдана' : 'Не выдана' }}</span>
                </div>
                <div class="flex flex-col pr-10">
                    <span class="text-base text-gray-200 mb-2">Всего заказов</span>
                    <span class="text-base text-gray-900 font-medium">{{ courier.orders.length }}</span>
                </div>
                <div class="flex flex-col pr-10">
                    <span class="text-base text-gray-200 mb-2">Отмененные заказы</span>
                    <span class="text-base text-gray-900 font-medium">{{ canceledOrdersQuantity }}</span>
                </div>
<!--                <div class="flex flex-col pr-10">-->
<!--                    <span class="text-base text-gray-200 mb-2">Нерабочие часы</span>-->
<!--                    <span class="text-base text-gray-900 font-medium">7 ч</span>-->
<!--                </div>-->
<!--                <div class="flex flex-col">-->
<!--                    <span class="text-base text-gray-200 mb-2">Рейтинг</span>-->
<!--                    <div>-->
<!--                        <icon name="star" class="dropdown-icon w-4 h-4 h-auto inline-block fill-current text-orange-400"></icon>-->
<!--                        <span class="text-base text-gray-900 font-medium">4,9</span>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
            <div v-if="!orderDetailed" class="courier-details">
                <h2 class="text-4xl text-gray-900 font-semibold mb-5">История заказов</h2>
                <div class="pl-10 mb-6">
                    <div class="flex justify-between mb-5">
                        <div class="flex items-center text-base text-gray-900 relative">
                            <span class="mr-2">Период времени:</span>
                            <v-date-picker v-model="firstDate" mode="date" is-range ref="datepicker" :masks="masks">
                                <template v-slot="{ inputValue, togglePopover }">
                                    <div class="flex items-center">
                                        <span class="font-medium mr-3">{{ inputValue.start }} - {{ inputValue.end }}</span>
                                        <button class="flex items-center" @click="togglePopover">
                                            <icon name="calendar" class="dropdown-icon w-4 inline-block fill-current text-orange-400"></icon>
                                        </button>
                                    </div>
                                </template>
                            </v-date-picker>
                            <!-- <button @click="openCalendar">
                                <icon name="calendar" class="dropdown-icon w-4 h-4 h-auto inline-block fill-current text-orange-400"></icon>
                            </button>
                            <div class="calendar absolute bottom-0 right-0" :class="{'hidden': !this.isOpenCalendar}">
                                <date-picker v-model="calendarOptions" is-range ></date-picker>
                            </div> -->
                        </div>
                        <div class="flex items-center mb-3">
                            <span class="mr-2 text-gray-900">Сортировка:</span>
                            <div class="w-56">
                                <multiselect v-model="selectedOrderStatus"
                                             placeholder="По статусу"
                                             track-by="label"
                                             label="label"
                                             :options="orderStatus">
                                </multiselect>
                            </div>
                        </div>
                    </div>
                    <div>
                        <table class="w-full">
                            <tr>
                                <th class="title"></th>
                                <th class="text-base text-left text-gray-200 pr-1">Номер</th>
                                <th class="text-base text-left text-gray-200 pr-1">Дата</th>
                                <th class="text-base text-left text-gray-200 pr-1">Статус</th>
                                <th class="text-base text-left text-gray-200 pr-1">Оценка клиента</th>
                                <th class="text-base text-left text-gray-200 pr-1"></th>
                            </tr>
                            <order-history v-for="order in orders" :key="order.id"
                                           @open="openOrderHistory(order)"
                                           :order="order" :status="order.status ? orderStatuses[order.status] : null"/>
                        </table>
                    </div>
                </div>

                <courier-comments :comments="courier.comments"/>

                <div class="hidden">
                    <form>
                        <h2 class="text-4xl text-gray-900 font-semibold mb-5">Комментарий курьеру</h2>
                        <textarea class="w-full h-32 border-2 border-gray-200 placeholder-gray-300 focus:ring-transparent focus:border-gray-200 text-gray-900 rounded-lg px-5 py-5 mb-5"
                                  placeholder="Ввод" cols="30" rows="10">
                        </textarea>
                        <div class="flex justify-end">
                            <button type="button" class="px-6 py-2 bg-orange-400 text-base text-white rounded-lg">Отправить</button>
                        </div>
                    </form>
                </div>
            </div>
            <div v-if="orderDetailed">
                <div class="flex items-center justify-between mb-6">
                    <div v-if="orderDetailed.status" class="flex text-4xl font-semibold">
                        <span class="text-gray-900 mr-2">1 -</span>
                        <span class="text-green-700">{{ orderStatuses[orderDetailed.status] }}</span>
                    </div>
                    <div>
                        <button class="text-orange-400 text-base flex items-center" @click="closeOrderHistory()">
                            <icon name="long-arrow" class="w-4 h-auto inline-block fill-current text-orange-400 mr-2"></icon>
                            <span>Назад</span>
                        </button>
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
                                    <td class="text-lg text-left text-gray-900 leading-none py-4">{{ orderDetailed.client.name }}</td>
                                    <td class="text-lg text-left text-gray-900 leading-none py-4">{{ orderDetailed.client.phone }}</td>
                                    <td class="text-lg text-left text-gray-900 leading-none py-4">
                                        {{ orderDetailed.addresses.length ? orderDetailed.addresses[0].address : '-' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <button class="hidden py-5 px-5 rounded-lg bg-orange-400 text-white text-base flex">
                            <icon name="chat" class="w-6 h-auto inline-block fill-current text-white mr-2"></icon>
                            Чат с клиентом
                        </button>
                    </div>
                </div>
                <div v-if="orderDetailed.reviews.length">
                    <div class="mb-5">
                        <span class="text-gray-900 text-xl font-medium">Отзыв клиента</span>
                    </div>
                    <div class="pl-12 flex justify-between items-center">
                        <div class=" border-l-2 border-orange-400">
                            <p v-for="review in orderDetailed.reviews"
                               class="text-lg text-gray-900 leading-none pl-3 py-3">{{ review.text }}</p>
                        </div>
                        <div>
                            <icon name="star" class="dropdown-icon w-4 h-4 h-auto inline-block fill-current text-orange-400"></icon>
                            <span class="text-base text-gray-900 font-medium">{{ parseFloat(orderDetailed.reviews_avg_star).toFixed(2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div slot="modals" id="courier-detail-modal" class="modal">
            <modal class="assign-courier-modal"  name="assignCourier" close-text>
                <div slot="title" class="flex items-center">
                    <button class="text-orange-400 flex items-center mr-6"><icon class="mr-2" name="plus-file"/><span>Назначить новый заказ</span></button>
                </div>
                <assign-courier></assign-courier>
            </modal>
            <modal class="blocking-reason" name="blockingReason" close-text>
                <div class="-mt-6">
                    <h2 class="text-xl text-gray-900 font-medium mb-5">Заблокировать курьера</h2>
                    <form @submit.prevent="blockCourier">
                        <div class="flex flex-col mb-5">
                            <label for="blocking-reason" class="text-lg font-medium text-gray-900 mb-3">Причина блокировки</label>
                            <textarea id="blocking-reason"
                                      v-model="blockForm.des"
                                      cols="30" rows="10"
                                      placeholder="Ввод"
                                      class="bottom-2 border-gray-150 rounded-lg px-4 py-3 h-20 focus:ring-0 focus:border-gray-150 focus:outline-none placeholder-gray-150 text-gray-900">
                        </textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="text-white text-base px-10 py-2 rounded-lg bg-orange-400">Заблокировать</button>
                        </div>
                    </form>
                </div>
            </modal>
            <modal class="confirmation" name="successfulBlocking" close-text>
                <div class="flex flex-col items-center px-10">
                    <div class="w-12 h-12 mb-5">
                        <icon name="success" class="h-auto text-green-700 fill-current mr-2"></icon>
                    </div>
                    <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                        <span class="leading-none">Курьер успешно</span>
                        <span class="leading-none">заблокирован</span>
                    </p>
                </div>
            </modal>
        </div>
        <div class="courier-modals" slot="modals">
            <div class="modal">
                <modal name="editCourierData" close-text>
                    <CourierEditForm :courier="courier"
                                     :constant-courier-payments="constantCourierPayments"
                                     :dynamic-calculation-types="dynamicCalculationTypes"
                                     :calculation-types="calculationTypes"
                                     :transports="transports">
                    </CourierEditForm>
                </modal>
            </div>
        </div>
    </app-layout-map>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'
import OrderDescription from '@/Components/Couriers/OrderDescription'
import OrderHistory from '@/Components/Couriers/OrderHistory'
import CourierEditForm from '@/Components/Couriers/CourierEditForm'
import CourierComments from '@/Components/Couriers/CourierComments'
import AssignCourier from '@/Components/AssignCourier'
import {isNumber} from 'lodash'

export default {
    components: {
        AppLayout,
        VDatePicker,
        OrderDescription,
        OrderHistory,
        CourierEditForm,
        AssignCourier,
        CourierComments
    },
    props: {
        courier: {
            default: () => ({}),
            type: Object,
        },
        orderStatuses: Object,
        transports: {
            default: () => ({}),
            type: Object,
        },
        calculationTypes: {
            default: () => ({}),
            type: Object,
        },
        dynamicCalculationTypes: Array,
        constantCourierPayments: Array,
        weekDays: Array,
    },
    data() {
        return {
            orderDetailed: null,
            masks: {
                input: 'DD.MM',
            },
            firstDate: null,
            secondDate: null,
            isOpenCalendar: false,
            calendarOptions: {
                start: new Date(),
                end: new Date()
            },
            selectedOrderStatus: null,
            orderStatus : [],
            selectedDate: '',
            selectedStatus: null,
            sortStatus: [
                {
                    label: 'Активен',
                    value: true,
                },
                {
                    label: 'Не активен',
                    value: false,
                },
            ],
            blockForm: this.$inertia.form({
                des: '',
            })
        }
    },
    computed: {
        canceledOrdersQuantity() {
            return this.courier.orders.filter(order => order.status && order.status === 'canceled-by-courier').length
        },
        orders() {
            return this.courier.orders.filter(order => {
                if(this.selectedOrderStatus) {
                    return order.status === this.selectedOrderStatus.value
                }
                return true
            }).filter(order => {
                if(this.firstDate) {
                    return Date.parse(this.firstDate.start) < Date.parse(order.created_at)
                        && Date.parse(order.created_at) < Date.parse(this.firstDate.end)
                }
                return true
            })
        }
    },
    methods: {
        setOrderStatus() {
            for (const [key, value] of Object.entries(this.orderStatuses)) {
                this.orderStatus.push({
                    label: value,
                    value: key
                });
            }
        },
        activateCourier() {
            this.$inertia.post(`/couriers/${this.courier.id}/activate`)
        },
        blockCourier() {
            this.blockForm.post(`/couriers/${this.courier.id}/block`, {
                onSuccess: () => {
                    this.blockForm.reset()
                    this.$modal.close('blockingReason')
                    this.$modal.open('successfulBlocking')
                }
            })
        },
        openCalendar() {
            this.isOpenCalendar = !this.isOpenCalendar
        },
        openOrderHistory(order) {
            this.orderDetailed = order
        },
        closeOrderHistory() {
            this.orderDetailed = null
        },
        getCourierWeekDays() {
            let weekDays = []
            this.courier.work_schedule?.work_hours.map((hour) => {
                if (isNumber(hour.day_of_week)) weekDays.push(this.weekDays[hour.day_of_week])
                if (isNumber(hour.next_day_of_week)) weekDays.push(this.weekDays[hour.next_day_of_week])
            })
            return [...new Set(weekDays)].join(', ')
        },
        getFirstWorkHour() {
            const workHour = this.courier.work_schedule?.work_hours[0]
            return workHour?.id ? `${workHour.opening} - ${workHour.closing}` : null
        }
    },
    created() {
        this.selectedStatus = this.sortStatus.find(status => status.value == this.courier.is_active)
        this.setOrderStatus()
    }
}
</script>
<style>
    #courier-detail-modal .assign-courier-modal .modal-block {
        width: 80%;
    }
    #courier-detail .calendar {
        top: 40px;
        left: 200px;
    }
    #courier-detail .title:nth-child(1) {
        width: 10px;
    }
    .courier-modals .modal .modal-container .modal-block {
        width: 55%;
    }
    .courier-modals .modal .modal-container .modal-block {
        max-height: 90%;
        overflow-y: scroll;
    }
    .courier-modals .modal .modal-container .modal-block::-webkit-scrollbar {
        width: 5px;
        background-color: #f9f9fd;
    }
    .courier-modals .modal .modal-container .modal-block::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background-color: #BFBFBF;
    }
</style>
