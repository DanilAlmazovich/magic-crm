<template>
    <app-layout-map content-width="w-10/12">
        <div class="fines">
            <div class="flex items-center justify-between pt-10 mb-4">
                <div class="pt-2">
                    <h1 class="text-3xl font-semibold text-gray-900">Штрафы</h1>
                </div>
                <div class="flex items-center">
                    <div class="flex">
                        <a href="/fines" class="text-base text-gray-200 flex items-center px-5 border-r-2 border-gray-200">Штрафы</a>
                        <a href="#" class="text-base text-yellow-400 flex items-center px-5">Замечание партнеров(12)</a>
                    </div>
                </div>
            </div>
             <div class="flex justify-between">
                <div class="flex">
                    <div class="flex items-end mb-5 mr-5">
                        <span class="mr-2 text-gray-900">Оператор:</span>
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
                                                <span class="text-sm text-gray-900">{{ establishment.name }}</span>
                                            </checkbox>
                                            <div class="pl-4" v-for="branch in establishment.branches">
                                                <checkbox :value="branch.name"
                                                        :key="branch.name"
                                                        class="mb-2 justify-end">
                                                    <span class="text-sm text-gray-900">{{ branch.name }}</span>
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
                    <div class="flex items-end mb-5 mr-8">
                        <span class="mr-2 text-gray-900">Курьер:</span>
                        <div>
                            <jet-dropdown>
                                <div slot="trigger" class="border-b border-gray-900">
                                    <input type="text"
                                        placeholder="Поиск"
                                        class="border-none placeholder-gray-200 text-gray-900">
                                </div>
                                <div slot="content" class="px-3 py-3">
                                    <div class="flex flex-col">
                                        <div v-for="courier in couriers">
                                            <checkbox :value="courier.name"
                                                    :key="courier.name"
                                                    class="mb-2 justify-end">
                                                <span class="text-sm text-gray-900">{{ courier.name }}</span>
                                            </checkbox>
                                        </div>
                                    </div>
                                    <div class="pt-8">
                                        <button class="w-full px-5 py-2 rounded-lg border-orange-400 border-2 text-orange-400 text-base">Применить</button>
                                    </div>
                                </div>
                            </jet-dropdown>
                        </div>
                    </div>
                    <div class="flex items-center text-base">
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
                    </div>
                </div>
            </div>
            <partner-remarks-table/>
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
        <div slot="modals">
            <div class="modal">
                <modal class="confirmation" name="confirmationAddingRemarks" close-text>
                    <div class="flex flex-col items-center px-10">
                        <div class="w-12 h-12 mb-5">
                            <icon name="success" class="h-auto fill-current text-green-700 mr-2"></icon>
                        </div>
                        <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                            <span class="leading-none">Хотите добавить</span>
                            <span class="leading-none">замечание?</span>
                        </p>
                    </div>
                    <div class="flex justify-between items-center px-5">
                        <button class="text-orange-400 text-base">Да</button>
                        <button class="text-orange-400 text-base" @click="$modal.close('confirmationAddingRemarks')">Нет</button>
                    </div>
                </modal>
                <modal class="credit" name="remarks" close-text>
                    <form class="-mt-6">
                        <h2 class="text-xl text-gray-900 font-medium mb-5">Информация о замечании</h2>
                        <div class="flex mb-6">
                            <div class="w-1/3 pr-4 flex flex-col">
                                <div class="flex flex-col w-full">
                                    <label class="text-gray-900 text-xl font-medium mb-3">Дата</label>
                                    <input type="number"
                                        placeholder="Ввод"
                                        class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                                </div>
                            </div>
                            <div class="w-1/3 px-2 flex flex-col">
                                <div class="flex flex-col w-full">
                                    <label class="text-gray-900 text-xl font-medium mb-3">Филиал</label>
                                    <input type="tetxt"
                                        placeholder="Ввод"
                                        class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                                </div>
                            </div>
                            <div class="w-1/3 pl-4 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Курьер</label>
                                <input type="text"
                                        placeholder="Ввод"
                                        class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                        </div>
                        <div class="flex justify-between mb-6">
                            <div class="w-2/4 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Замечание от филиала</label>
                                <input type="text"
                                    placeholder="Ввод"
                                    class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                        </div>
                        <div class="flex justify-between mb-6">
                            <div class="w-4/12 pr-4 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Сумма штрафа</label>
                                <input type="text"
                                        placeholder="Ввод"
                                        class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                            <div class="w-8/12 pl-4 flex flex-col">
                                <label class="text-gray-900 text-xl font-medium mb-3">Причина</label>
                                <input type="text"
                                    placeholder="Ввод"
                                    class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" class="leading-tight py-3 bg-yellow-400 text-white rounded-md px-8" @click="$modal.close('remarks'), $modal.open('confirmationAddingRemarks')">
                                Добавить
                            </button>
                        </div>
                    </form>
                </modal>
            </div>
        </div>
    </app-layout-map>
</template>
<script>
import AppLayoutMap from '../Layouts/AppLayoutMap'
import FinesTable from  '@/Components/FinesTable'
import PartnerRemarksTable from  '@/Components/PartnerRemarksTable'
import JetDropdown from '@/Jetstream/Dropdown'
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'

export default {
    data() {
        return {
            firstDate: null,
            secondDate: null,
            creditTerm: null,
            masks: {
                input: 'DD.MM',
            },
            fines: true,
            selectedAccountNumber: '',
            accountNumber: [
                {
                    label: '6452 5462',
                },
                {
                    label: '6458 0248',
                },
                {
                    label: '4589 2465',
                },
            ],
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
        AppLayoutMap,
        FinesTable,
        PartnerRemarksTable,
        JetDropdown,
        VDatePicker
    },
}
</script>
<style>
    .fines .pagination {
        border-left: none;
    }
    .fines .active {
        border-top: none;
        border-bottom: none;
        background: #FFBE17;
    }
    .fines .checkbox {
        flex-direction: row-reverse;
    }
    .fines .checkbox span:nth-child(1) {
        margin-left: 15px;
    }
    .fines .content:before {
        right: calc(50% - 0.75rem);
    }
    .modal .modal-container .modal-block {
        width: 50%;
    }
    .modal .confirmation .modal-block {
        width: auto;
    }
</style>
