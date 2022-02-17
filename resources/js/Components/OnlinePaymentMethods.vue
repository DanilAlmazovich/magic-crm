<template>
    <div>
        <div class="flex items-center" slot="title">
            <span class="text-xl font-medium mr-36">Онлайн оплата заказа</span>
        </div>
        <div class="py-6 mb-2">
            <h4 class="text-lg text-gray-900 font-medium mb-4">Онлайн кошельки</h4>
            <div class="flex flex-wrap">
                <div class="mr-4 mb-3" v-for="paymentMethod in paymentMethods">
                    <radio-button :value="paymentMethod.id" :label="paymentMethod.title" @change="selectedPaymentMethodChanged" v-model="selectedPaymentMethod"></radio-button>
                </div>
            </div>
        </div>
        <div class="flex py-1 justify-between items-center">
            <div class="flex text-lg mr-16">
                <span class="mr-2">Сумма: </span>
                <span>{{ orderCost }} сом</span>
            </div>
            <div class="flex items-center">
                <checkbox class="text-orange-400 mr-6" @change="itShouldSendBankDetailsChanged" v-model="shouldSendDetails">Выслать реквизит</checkbox>
                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7" @click="$modal.close('onlinePaymentMethods')">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'OnlinePaymentMethods',
    props: {
        orderCost: 0,
    },
    data() {
        return {
            paymentMethods: null,
            shouldSendDetails: false,
            selectedPaymentMethod: null,
        }
    },
    created() {
        this.loadPaymentMethods()
    },
    methods: {
        async loadPaymentMethods() {
            const { data: { data } } = await axios.get('/dictionaries/payment-methods/get-active-methods')
            this.paymentMethods = data
        },
        selectedPaymentMethodChanged(v) {
            this.$emit('update-selected-payment-method', this.selectedPaymentMethod)
        },
        itShouldSendBankDetailsChanged(v) {
            this.$emit('update-it-should-send-details', this.shouldSendDetails)
        }
    },
}
</script>

<style scoped>

</style>
