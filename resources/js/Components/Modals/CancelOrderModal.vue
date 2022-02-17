<template>
    <div>
        <modal name="orderCancel" close-text>
            <template v-slot:title>
                <div class="flex items-center">
                    <span class="text-xl font-medium mr-36">Отменить заказ</span>
                </div>
            </template>
            <template v-slot="{data: {orderId}}">
                <div class="pt-6">
                    <textarea placeholder="Примечание" class="w-full border border-solid border-gray-150 rounded-lg mb-4 h-16 focus:border-orange-400" v-model="reason" />
                    <div class="flex justify-end">
                        <button class="py-3 px-7 border border-solid border-red-400 bg-red-400 rounded-lg text-white"
                                @click="cancel(orderId)">
                            Отменить заказ
                        </button>
                    </div>
                </div>
            </template>
        </modal>

        <modal name="orderSuccessfullyCanceled">
            <div class="py-4 mb-2 flex flex-col items-center px-10">
                <icon name="green-check" class="mb-4"></icon>
                <div class="text-center w-32">
                    <span class="font-medium">Заказ успешно отменен</span>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
export default {
    component: 'CancelOrderModal',
    data() {
        return {
            reason: '',
            cancel(id) {
                axios.post(`/orders/${id}/cancel`, {reason: this.reason})
                    .then(() => {
                        this.$modal.close('orderCancel')
                        this.$modal.open('orderSuccessfullyCanceled')

                        this.$inertia.reload({
                            only: ['order'],
                        }).then(() => {
                            this.$emit('canceled')

                            setTimeout(() => this.$inertia.visit('/orders'), 1000)
                        })
                    })
            },
        }
    }
}
</script>
