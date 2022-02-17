<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Схема</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Базовая оплата за заказ</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Оплата за час работы</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr v-for="(payment, index) in schemes" :key="index">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 pl-2 list">{{ index + 1 }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ payment.title }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ payment.base_cost_per_order }} сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ payment.payment_per_hour }} сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">
                    <div class="flex justify-end items-center">
                        <button class="mr-3 text-orange-400 flex items-center" @click="openEditModal(payment)">
                            <icon name="editing" class="w-5 h-5 fill-current text-orange-400 mr-2"></icon>
                            <span>Редактировать</span>
                        </button>
                        <button class="w-5 h-5" @click="openDeletePaymentConfirmModal(payment.id)">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </button>
                    </div>
                </td>
            </tr>
        </table>
        <modal name="editingPayment" close-text>
            <payment-editing-form :payment="selectedPayment"></payment-editing-form>
        </modal>
        <modal class="confirmation" name="deleteConstantPayment" close-text>
            <div class="flex flex-col items-center px-10">
                <div class="w-12 h-12 mb-5">
                    <icon name="remove" class="h-auto fill-current mr-2"></icon>
                </div>
                <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                    <span class="leading-none">Вы действительно</span>
                    <span class="leading-none">хотите удалить?</span>
                </p>
            </div>
            <div class="flex justify-center items-center px-5">
                <button @click="deletePayment" class="text-orange-400 text-base mr-10">Да</button>
                <button @click="$modal.close('deleteConstantPayment')" class="text-orange-400 text-base ml-10">Нет</button>
            </div>
        </modal>
    </div>
</template>

<script>
import PaymentEditingForm from "./PaymentEditingForm";

export default {
    name: 'PaymentTable',
    components: {
        PaymentEditingForm,
    },
    props: {
        schemes: {
            type: Array,
            default: () => [],
        }
    },

    data() {
        return {
            selectedPayment: null,
            selectedDeletingPayment: null,
        }
    },

    methods: {
        openEditModal(payment) {
            this.selectedPayment = payment
            this.$modal.open('editingPayment')
        },
        openDeletePaymentConfirmModal(paymentId) {
            this.selectedDeletingPayment = paymentId
            this.$modal.open('deleteConstantPayment')
        },
        deletePayment() {
            this.$inertia.delete(`constant-courier-payment-schemes/${this.selectedDeletingPayment}`, {
                onSuccess: () => {
                    this.$modal.close('deleteConstantPayment')
                }
            })
        }
    }
}
</script>

<style scoped>
.list {
    width: 20%;
}
.list:nth-child(1) {
    width: 4%;
}
</style>
