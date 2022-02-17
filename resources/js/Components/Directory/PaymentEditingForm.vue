<template>
    <div class="-mt-6">
        <form @submit.prevent="submit">
            <h2 class="text-xl text-gray-900 font-medium mb-5">Редактировать</h2>
            <div class="flex pb-8">
                <div class="w-1/3 pr-6 flex flex-col">
                    <label for="title"
                           class="text-gray-900 text-xl font-medium mb-3">Схема</label>
                    <input id="title"
                           type="text"
                           placeholder="Ввод"
                           required
                           v-model="form.title"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                </div>
                <div class="w-1/3 px-3 flex flex-col">
                    <label for="basic-pay"
                           class="text-gray-900 text-xl font-medium mb-3">Баз. оплата за заказ</label>
                    <input id="basic-pay"
                           type="number"
                           required
                           v-model="form.baseCostPerOrder"
                           placeholder="Ввод"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                </div>
                <div class="w-1/3 pl-6 flex flex-col">
                    <label for="payment-per-hour"
                           class="text-gray-900 text-xl font-medium mb-3">Оплата за час работы</label>
                    <input id="payment-per-hour"
                           type="number"
                           required
                           v-model="form.paymentPerHour"
                           placeholder="Ввод"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" :disabled="form.processing || form.hasErrors" :class="`leading-tight py-3 bg-orange-400 text-white rounded-md px-7  ${form.processing || form.hasErrors ? 'cursor-not-allowed' : ''}`">
                    Сохранить
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "PaymentEditingForm",
    props: {
        payment: {
            default: () => ({}),
            type: Object
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                title: this.payment?.title,
                baseCostPerOrder: this.payment?.base_cost_per_order,
                paymentPerHour: this.payment?.payment_per_hour,
            })
        }
    },

    methods: {
        submit() {
            const method = this.payment?.id ? 'put' : 'post'
            const url = method === 'post' ? 'constant-courier-payment-schemes' :
                `constant-courier-payment-schemes/${this.payment.id}`;

            this.form[method](url, {
                onSuccess: () => {
                    method === 'post' ? this.$modal.close('addScheme') : this.$modal.close('editingPayment')
                    this.form.reset()
                }
            })
        }
    }
}
</script>

