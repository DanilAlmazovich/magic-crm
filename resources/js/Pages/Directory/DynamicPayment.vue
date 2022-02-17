<template>
    <app-layout-map :name="name" content-width="w-11/12">
        <div class="pt-2 flex justify-between items-center pb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Схема динамической оплаты курьеру</h1>
            <button class="text-base text-orange-400"  @click="$modal.open('addScheme')">Добавить схему</button>
        </div>
        <dynamic-payment-table :dynamic-payments="schemes"></dynamic-payment-table>
        <div slot="modals">
            <div class="modal">
                <modal name="addScheme" close-text>
                    <dynamic-payment-form></dynamic-payment-form>
                </modal>
            </div>
        </div>
    </app-layout-map>
</template>
<script>
import AppLayoutMap from '@/Layouts/AppLayoutMap'
import DynamicPaymentTable from '@/Components/Directory/DynamicPaymentTable'
import DynamicPaymentForm from '@/Components/Directory/DynamicPaymentForm';

export default {
    components: {
        AppLayoutMap,
        DynamicPaymentTable,
        DynamicPaymentForm,
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
        schemes: Array
    }
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
