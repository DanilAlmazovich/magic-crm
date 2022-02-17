<template>
    <directory-layout :name="name" content-width="w-11/12">
        <div class="pt-2 flex items-center justify-between">
            <h1 class="text-3xl font-semibold text-gray-900">Схема постоянной оплаты</h1>
            <button class="text-base text-orange-400" @click="$modal.open('addScheme')">Добавить схему</button>
        </div>
        <div class="flex justify-between pt-6 mb-4">
            <div class="flex justify-between">
                <div class="flex items-center">
                    <span class="mr-2 text-gray-900">Сортировка:</span>
                    <div class="w-36">
                        <multiselect v-model="selectedWorkShift"
                                     placeholder="По смене"
                                     track-by="label"
                                     label="label"
                                     :options="workShift">
                        </multiselect>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-gray-900">Сортировка:</span>
                    <div class="w-36">
                        <multiselect v-model="selectedStatus"
                                     placeholder="По статусу"
                                     track-by="label"
                                     label="label"
                                     :options="status">
                        </multiselect>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-gray-900">Сортировка:</span>
                    <div class="w-36">
                        <multiselect v-model="selectedType"
                                     placeholder="Тип"
                                     track-by="label"
                                     label="label"
                                     :options="paymentTypes">

                        </multiselect>
                    </div>
                </div>
                <div class="flex pl-1">
                    <input type="text" class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100" placeholder="Поиск партнеров">
                    <button class="bg-orange-400 text-white py-3 px-7 rounded-r-md">Найти</button>
                </div>
            </div>
        </div>
        <payment-table :schemes="schemes"></payment-table>
        <div slot="modals">
            <div class="modal">
                <modal name="addScheme" close-text>
                    <payment-editing-form></payment-editing-form>
                </modal>
            </div>
        </div>
    </directory-layout>
</template>
<script>
import DirectoryLayout from '@/Layouts/DirectoryLayout'
import PaymentTable from '@/Components/Directory/PaymentTable'
import PaymentEditingForm from '@/Components/Directory/PaymentEditingForm';

export default {
    components: {
        DirectoryLayout,
        PaymentTable,
        PaymentEditingForm,
    },
    props: {
        schemes: {
            default: () => [],
            type: Array,
        }
    },
    data() {
        return {
            name: 'directory',
            selectedWorkShift: '',
            workShift : [
                {
                    label: 'Ночная',
                    value: 'Night',
                },
                {
                    label: 'Дневная',
                    value: 'Daytime',
                },
            ],
            selectedStatus: '',
            status: [
                {
                    label: 'Активен',
                    value: 'active',
                },
                {
                    label: 'Не активен',
                    value: 'not active',
                },
            ],
            selectedType: [],
            paymentTypes : [
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
        }
    },
}
</script>
<style>
.modal .modal-container .modal-block {
    max-height: 90%;
    width: 50%;
}
.multiselect__placeholder {
    font-weight: 500;
    color: #000;
}
</style>
