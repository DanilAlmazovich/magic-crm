<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Наименование</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Макс. сумма в офис</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr v-for="(payment, index) in dynamicPayments">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 pl-2 list">{{ index + 1 }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ payment.title }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ payment.max_sum_to_office }} сом</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">
                    <div class="flex justify-end items-center">
                        <button class="mr-3 text-orange-400 flex items-center" @click="selectEditingPayment(payment)">
                            <icon name="editing" class="w-5 h-5 fill-current text-orange-400 mr-2"></icon>
                            <span>Редактировать</span>
                        </button>
                        <button class="w-5 h-5" @click="selectRemovingPayment(payment.id)">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </button>
                    </div>
                </td>
            </tr>
        </table>
        <modal name="editingScheme" close-text>
            <dynamic-payment-form :dynamic-payment="dynamicPayment"></dynamic-payment-form>
        </modal>
        <modal class="confirmation" name="deleteScheme" close-text>
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
                <button @click="removeDynamicPayment" class="text-orange-400 text-base mr-10">Да</button>
                <button @click="$modal.close('deleteScheme')" class="text-orange-400 text-base ml-10">Нет</button>
            </div>
        </modal>
    </div>
</template>

<script>
import DynamicPaymentForm from '@/Components/Directory/DynamicPaymentForm';

export default {
    name: 'DynamicPaymentTable',
    components: {
        DynamicPaymentForm
    },
    data() {
        return {
            dynamicPayment: null,
            removingDynamicPayment: null,
        }
    },
    props: {
        dynamicPayments: Array
    },
    methods: {
        selectEditingPayment(dynamicPayment) {
            this.dynamicPayment = dynamicPayment

            this.$modal.open('editingScheme')
        },
        async removeDynamicPayment() {
            await this.$inertia.delete(`dynamic-commission-calculations/${this.removingDynamicPayment}`, {
                onSuccess: () => {
                    this.$modal.close('deleteScheme')
                }
            })
        },
        selectRemovingPayment(id) {
            this.removingDynamicPayment = id
            this.$modal.open('deleteScheme')
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
