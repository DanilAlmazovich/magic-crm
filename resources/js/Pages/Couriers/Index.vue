<template>
    <app-layout-map content-width="w-10/12">
        <div class="flex justify-end items-center -mt-16">
            <button type="button" class="px-4 py-3 rounded-lg border-none flex items-center" @click="$modal.open('addCourier')">
                    <span class="w-3 h-3 transform rotate-45 mr-3">
                        <icon name="cross" class="fill-current text-orange-400"></icon>
                    </span>
                <span class="leading-none text-orange-400 text-base">Добавить курьера</span>
            </button>
        </div>
        <div class="flex justify-between pt-6 mb-4">
            <div class="pt-2">
                <h1 class="text-3xl font-semibold text-gray-900">Список курьеров</h1>
            </div>
            <div class="flex">
                <div class="flex pl-1">
                    <input type="text" v-model="q"
                           class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100"
                           placeholder="Поиск курьеров">
                    <button class="bg-orange-400 text-white py-3 px-7 rounded-r-md" @click.prevent="filter">Найти</button>
                </div>
            </div>
        </div>
        <div class="mb-5">
            <div class="flex items-center mb-5">
                <span class="mr-2 text-gray-900">Сортировка:</span>
                <div class="w-40 mr-4">
                    <multiselect v-model="status"
                                 placeholder="По статусу"
                                 track-by="value"
                                 label="label"
                                 :options="statuses"
                                 @input="filter">
                    </multiselect>
                </div>
                <div class="w-40">
                    <multiselect v-model="type"
                                 placeholder="По типу"
                                 track-by="label"
                                 label="label"
                                 :options="courierTypes"
                                 @input="filter">
                    </multiselect>
                </div>
            </div>
        </div>
        <div class="flex items-center">
            <icon class="mr-3 w-4 h-4" name="question"></icon>
            <span class="text-sm text-gray-400">
                Для подробной информации нажмите на строку необходимого курьера
            </span>
        </div>
        <couriers-table
            :couriers="couriers"
            @openEditForm="editCourier"
            :calculation-types="calculationTypes"
            :dynamic-calculation-types="dynamicCalculationTypes"
            :constant-courier-payments="constantCourierPayments"
            :transports="transports" />

        <div class="courier-modals" slot="modals">
            <div class="modal">
                <modal name="editCourierForm" close-text>
                    <CourierEditForm
                        :courier="courier"
                        :calculation-types="calculationTypes"
                        :dynamic-calculation-types="dynamicCalculationTypes"
                        :constant-courier-payments="constantCourierPayments"
                        :transports="transports">
                    </CourierEditForm>
                </modal>
                <modal name="addCourier" close-text>
                    <CourierEditForm
                        :calculation-types="calculationTypes"
                        :dynamic-calculation-types="dynamicCalculationTypes"
                        :constant-courier-payments="constantCourierPayments"
                        :transports="transports">
                    </CourierEditForm>
                </modal>
            </div>
        </div>
    </app-layout-map>
</template>
<script>
import AppLayoutMap from '@/Layouts/AppLayoutMap'
import CouriersTable from '@/Components/Couriers/CouriersTable'
import Label from "../../Jetstream/Label";
import CourierEditForm from '@/Components/Couriers/CourierEditForm';

export default {
    components: {
        Label,
        AppLayoutMap,
        CouriersTable,
        CourierEditForm,
    },
    props: {
        couriers: Array,
        transports: Object,
        query: Array,
        calculationTypes: Object,
        dynamicCalculationTypes: Array,
        constantCourierPayments: Array,
    },
    data() {
        return {
            status: null,
            type: null,
            q: null,
            courier: null,
            statuses: [
                {
                    label: 'Активен',
                    value: true,
                },
                {
                    label: 'Не активен',
                    value: false,
                },
            ],
        }
    },
    computed: {
       courierTypes() {
           return Object.keys(this.transports).map(value => ({
               label: this.transports[value],
               value
           }))
       }
    },
    methods: {
        filter() {
            this.$inertia.reload({
                only: ['couriers'],
                data: {
                    is_active: this.status?.value,
                    type: this.type?.value,
                    q: this.q
                }
            })
        },
        editCourier(courier) {
            this.courier = courier
            this.$modal.open('editCourierForm')
        }
    },
}
</script>
<style>
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
