<template>
    <app-layout-map :name="name" content-width="w-11/12">
        <div class="pt-2 flex items-center justify-between">
            <h1 class="text-3xl font-semibold text-gray-900">Схема доставки партнера</h1>
            <button class="text-base text-orange-400" @click="$modal.open('addScheme')">Добавить схему</button>
        </div>
        <div class="flex justify-between pt-6 mb-4">
            <div class="flex">
                <div class="flex items-center">
                    <span class="mr-2 text-gray-900">Сортировка:</span>
                    <div class="w-48">
                        <multiselect v-model="selectedWorkShift"
                                     placeholder="По смене"
                                     track-by="label"
                                     label="label"
                                     :options="workShift">
                        </multiselect>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-gray-900">Кафе::</span>
                    <div class="w-48">
                        <multiselect v-model="selectedEstablishments"
                                     placeholder="По статусу"
                                     track-by="label"
                                     label="label"
                                     :options="establishments">
                        </multiselect>
                    </div>
                </div>
            </div>
            <div class="flex pl-1">
                <input type="text" class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100" placeholder="Поиск партнеров">
                <button class="bg-orange-400 text-white py-3 px-7 rounded-r-md">Найти</button>
            </div>
        </div>
        <delivery-scheme-table :schemes="schemes"></delivery-scheme-table>
        <div slot="modals">
            <div class="modal">
                <modal name="addScheme" close-text>
                    <partner-delivery-scheme-form></partner-delivery-scheme-form>
                </modal>
            </div>
        </div>
    </app-layout-map>
</template>
<script>
import AppLayoutMap from '@/Layouts/AppLayoutMap'
import DeliverySchemeTable from '@/Components/Directory/DeliverySchemeTable'
import PartnerDeliverySchemeForm from '@/Components/Directory/PartnerDeliverySchemeForm';

export default {
    components: {
        AppLayoutMap,
        DeliverySchemeTable,
        PartnerDeliverySchemeForm,
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
            selectedEstablishments: '',
            establishments: [
                {
                    label: 'KFC',
                    value: 'KFC',
                },
                {
                    label: 'Фаиза',
                    value: 'Фаиза',
                },
            ],
        }
    },
    props: {
        schemes: {
            type: Array,
            default: () => []
        },
    }
}
</script>
<style>
.modal .modal-container .modal-block {
    max-height: 90%;
    width: 50%;
}
.modal .confirmation .modal-block {
    width: auto;
}
.multiselect__placeholder {
    font-weight: 500;
    color: #000;
}
</style>
