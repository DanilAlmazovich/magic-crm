<template>
    <app-layout :disabledHeader="disabledHeader">
        <div id="partners" class="h-auto">
            <div class="flex justify-end items-center pt-5">
                <button type="button" class="px-4 py-3 rounded-lg border-none flex items-center" @click="$modal.open('addPartner')">
                    <span class="w-3 h-3 transform rotate-45 mr-3">
                        <icon name="cross" class="fill-current text-orange-400"></icon>
                    </span>
                    <span class="leading-none text-orange-400 text-base">Добавить партнера</span>
                </button>
            </div>
            <div class="flex justify-between pt-6 mb-4">
                <div class="pt-2">
                    <h1 class="text-3xl font-semibold text-gray-900">Партнеры</h1>
                </div>
                <div class="flex">
                    <div class="flex items-center">
                        <span class="mr-2 text-gray-900">Сортировка:</span>
                        <div class="w-48">
                            <multiselect v-model="selectedCategory"
                                         placeholder="По статусу"
                                         track-by="label"
                                         label="label"
                                         :options="categories">
                            </multiselect>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-2 text-gray-900">Сортировка:</span>
                        <div class="w-48">
                            <multiselect v-model="selectedEstablishment"
                                         placeholder="Заведения"
                                         track-by="label"
                                         label="label"
                                         :options="establishments">
                            </multiselect>
                        </div>
                    </div>
                    <div class="flex pl-1">
                        <input type="text" class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100" placeholder="Поиск партнеров">
                        <button class="bg-orange-400 text-white py-3 px-7 rounded-r-md">Найти</button>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                <div class="flex items-center">
                    <icon class="mr-3 w-4 h-4" name="question"></icon>
                    <span class="text-sm text-gray-400">Для подробной информации нажмите на строку необходимого курьера</span>
                </div>
            </div>
            <partners-table :errors="errors" :partner-types="partnerTypes" :schemes="schemes" :partners="partners"></partners-table>
            <div class="modal">
                <modal class="add-partner" name="addPartner" close-text>
                    <div class="-mt-6">
                        <h2 class="text-xl text-gray-900 font-medium mb-5">Добавить партнера</h2>
                        <partner-data-form :errors="errors" :partner-types="partnerTypes" :schemes="schemes"></partner-data-form>
                    </div>
                </modal>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'
import PartnersTable from '@/Components/Partners/PartnersTable'
import Modal from  '@/Plugins/Modal/Component'
import PartnerDataForm from '@/Components/Partners/PartnerDataForm'

export default {
    components: {
        AppLayout,
        PartnersTable,
        Modal,
        PartnerDataForm,
    },
    data() {
        return {
            disabledHeader: true,
            selectedCategory: '',
            categories : [
                {
                    label: 'Кафе',
                    value: 'Cafe',
                },
                {
                    label: 'Флористика',
                    value: 'Floristics',
                },
                {
                    label: 'Сувениры',
                    value: 'Souvenirs',
                },
            ],
            selectedEstablishment: '',
            establishments: [
                {
                    label: 'Все',
                    value: 'everything',
                },
                {
                    label: 'Фаиза',
                    value: 'Faiza',
                },
                {
                    label: 'Нац кухня',
                    value: 'National cuisine',
                },
                {
                    label: 'Европейская кухня',
                    value: 'European cuisine',
                },
                {
                    label: 'Доставка',
                    value: 'Delivery',
                },
                {
                    label: 'Японская кухня',
                    value: 'Japanese food',
                },
                {
                    label: 'Пиццерия и Фастфуд',
                    value: 'Pizzeria',
                },
                {
                    label: 'Корейская кухня',
                    value: 'Korean food',
                },
                {
                    label: 'Боорсок',
                    value: 'Boorsok',
                },
                {
                    label: 'Кухни мира',
                    value: 'World cuisines',
                },
                {
                    label: 'Здоровое питание',
                    value: 'Healthy eating',
                },
            ],
        }
    },
    props: {
        partners: {
            type: Array,
            default: () => []
        },
        errors: {
            type: Object
        },
        partnerTypes: {
            type: Object,
            default: () => ({}),
        },
        deliverySchemes: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        schemes() {
            return Object.keys(this.deliverySchemes).map(value => ({
                label: this.deliverySchemes[value]['title'],
                value: this.deliverySchemes[value]['id'],
            }))
        }
    },
}
</script>
<style>
    #partners .modal .add-partner .modal-block {
        width: 65%;
        max-height: 90%;
        overflow-y: scroll;
    }
    #partners .modal .add-partner .modal-block::-webkit-scrollbar {
        width: 5px;
        background-color: #f9f9fd;
    }
    #partners .modal .add-partner .modal-block::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background-color: #BFBFBF;
    }
</style>
