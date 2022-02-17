<template>
    <app-layout :name="name">
        <div class="transactions">
            <div class="flex items-center justify-between pt-10 mb-4">
                <div class="pt-2">
                    <h1 class="text-3xl font-semibold text-gray-900">Отчет по продуктам партнеров</h1>
                </div>
                <button class="text-base text-orange-400 flex items-center">
                    <icon name="excel" class="w-5 mr-3 block fill-current text-gray-900"/>
                    Экспорт в MS Excel
                </button>
            </div>
            <div class="flex justify-between">
                <div class="flex">
                    <div class="flex items-center text-base mr-6">
                        <span class="mr-3">Дата с</span>
                        <div class="flex items-center">
                            <v-date-picker v-model="firstDate" mode="date" is-expanded  ref="datepicker" :masks="masks">
                                <template v-slot="{ inputValue, togglePopover }">
                                    <div class="flex items-center">
                                        <span class="font-medium mr-3"
                                            @click="togglePopover">{{ inputValue }}</span>
                                        <button class="flex items-center" @click="togglePopover">
                                            <icon name="calendar" class="dropdown-icon w-4 inline-block fill-current text-orange-400"></icon>
                                        </button>
                                    </div>
                                </template>
                            </v-date-picker>
                        </div>
                    </div>
                    <div class="flex items-center text-base mr-6">
                        <span class="mr-3">Дата по</span>
                        <div class="flex items-center">
                            <v-date-picker v-model="secondDate" mode="date" is-expanded  ref="datepicker" :masks="masks">
                                <template v-slot="{ inputValue, togglePopover }">
                                    <div class="flex items-center">
                                        <span class="font-medium mr-3"
                                            @click="togglePopover">{{ inputValue }}</span>
                                        <button class="flex items-center" @click="togglePopover">
                                            <icon name="calendar" class="dropdown-icon w-4 inline-block fill-current text-orange-400"></icon>
                                        </button>
                                    </div>
                                </template>
                            </v-date-picker>
                        </div>
                    </div>
                    <div class="flex items-end mb-5 mr-5">
                        <span class="mr-2 text-gray-900">Филиал заведения:</span>
                        <div>
                            <jet-dropdown>
                                <div slot="trigger" class="border-b border-gray-900">
                                    <input type="text"
                                        placeholder="Поиск"
                                        class="border-none placeholder-gray-200 text-gray-900">
                                </div>
                                <div slot="content" class="px-3 py-3">
                                    <div class="flex flex-col">
                                        <div v-for="establishment in establishments">
                                            <checkbox :value="establishment.name"
                                                    :key="establishment.name"
                                                    class="mb-2 justify-end">
                                                <span class="ml-4 text-sm text-gray-900">{{ establishment.name }}</span>
                                            </checkbox>
                                            <div class="pl-4" v-for="branch in establishment.branches">
                                                <checkbox :value="branch.name"
                                                        :key="branch.name"
                                                        class="mb-2 justify-end">
                                                    <span class="ml-4 text-sm text-gray-900">{{ branch.name }}</span>
                                                </checkbox>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-8">
                                        <button class="w-full px-5 py-2 rounded-lg border-orange-400 border-2 text-orange-400 text-base">Применить</button>
                                    </div>
                                </div>
                            </jet-dropdown>
                        </div>
                    </div>
                <div class="flex items-center font-bold justify-between rounded min-w-56 leading-tight rounded-md mr-4 pl-2">
                    <multiselect v-model="selectedStatus"
                                 placeholder="Выбрать статус"
                                 track-by="label"
                                 :show-labels="false"
                                 label="label"
                                 :options="orderStatuses">
                        <template slot="singleLabel">
                            <span class="option__desc">
                                <span class="option__title" :style="`color: ${ statusColors }`">{{ selectedStatus.label}}</span>
                            </span>
                        </template>
                        <template slot="option" slot-scope="props">
                            <div class="option__desc"><span class="option__title" :style="`color: ${props.option.color}`">{{ props.option.label }}</span></div>
                        </template>
                    </multiselect>
                </div>  
                </div>
                <button class="text-orange-400 text-base">Сбросить фильтр</button>    
            </div>
        <product-report-table/>
        <div class="flex justify-end">
            <div class="flex w-auto">
                <button class="w-12 h-12 flex items-center justify-center border border-gray-200 rounded-tl-lg rounded-bl-lg">
                    <icon name="arrow" class="w-4 block fill-current text-gray-900 transform -rotate-90"/>
                </button>
                <button class="pagination active hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200">1</button>
                <button class="pagination hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200">2</button>
                <button class="pagination hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200">3</button>
                <button class="pagination hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200">4</button>
                <button class="pagination hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200">5</button>
                <button class="pagination hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200 border-l-none rounded-tr-lg rounded-br-lg">
                    <icon name="arrow" class="w-4 block fill-current text-gray-900 transform rotate-90"/>
                </button>
            </div>
        </div>        
        </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'
import ProductReportTable from  '@/Components/Billing/ProductReportTable'
import JetDropdown from '@/Jetstream/Dropdown'
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'
import statusColors from '../../utils/statusColors.js'

export default {
    data() {
        return {
            name: 'billing',
            statusColors: statusColors,
            selectedStatus: '',
            statuses: {
                    all: 'Все',
                    'new': 'Новый',
                    'accepted': 'Принят',
                    'transferred-to-cafe': 'Передан в кафе',
                    'is-preparing': 'Заказ готовится',
                    'is-ready': 'Заказ готов',
                    'courier-assigned': 'Курьер назначен',
                    'courier-accepted': 'Курьер принял',
                    'in-way-to-client': 'В пути клиенту',
                    'delivered': 'Доставлено',
                    'canceled-by-client' : 'Отменен клиентом',
                    'canceled-by-establishment': 'Отменен заведением',
                    'canceled-by-operator': 'Отменен оператором',
                    'courier-took': 'Курьер забрал товар',
                    'courier-completed-order': 'Курьер завершил заказ',
            },
            firstDate: null,
            secondDate: null,
            masks: {
                input: 'DD.MM',
            },
            establishments: [
                {
                    name: 'Фаиза',
                    branches: [
                        {name: 'Южный'},
                        {name: 'Чуйский'},
                        {name: 'Asia Mall'},
                    ]
                },
            ],
            couriers: [
                {
                    name: 'Евгений Онегин'
                },
                {
                    name: 'Евгений Онегин'
                },
                {
                    name: 'Евгений Онегин'
                },
                {
                    name: 'Евгений Онегин'
                },
                {
                    name: 'Евгений Онегин'
                },
                {
                    name: 'Евгений Онегин'
                },
                {
                    name: 'Евгений Онегин'
                },
            ]
        }
    },
    components: {
        AppLayout,
        ProductReportTable,
        JetDropdown,
        VDatePicker,
    },
    computed: {
        orderStatuses() {
            return Object.keys(this.statuses).map(value => ({
                value,
                label: this.statuses[value],
                color: this.statusColors[value]
            }))
        },
    },
}
</script>