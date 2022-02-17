<template>
    <form @submit.prevent="submitForm()">
        <div class="-mt-6">
            <h2 class="text-xl text-gray-900 font-medium mb-5">Добавить схему</h2>
            <div class="flex">
                <div class="w-1/3 flex flex-col relative mb-5 mr-2 pb-5">
                    <label for="scheme"
                           class="text-gray-900 text-xl font-medium mb-3">Наименование схемы</label>
                    <input id="scheme"
                           type="text"
                           v-model="form.title"
                           required
                           placeholder="Ввод"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                    <span v-if="form.errors.title" class="absolute bottom-0 left-0 leading-none text-red-400 text-sm">{{ form.errors.title }}</span>
                </div>
                <div class="w-1/3 flex flex-col relative mb-5 ml-2">
                    <label for="max-sum"
                           class="text-gray-900 text-xl font-medium mb-3">Макс. сумма в офис</label>
                    <input id="max-sum"
                           type="number"
                           v-model="form.maxSumToOffice"
                           placeholder="Ввод"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                    <span v-if="form.errors.maxSumToOffice" class="absolute bottom-0 left-0 leading-none text-red-400 text-sm">{{ form.errors.maxSumToOffice }}</span>
                </div>
            </div>
            <div class="mb-5">
                <h3 class="text-lg text-gray-900 font-medium mb-2">Доплаты курьеру</h3>
                <surcharges-sub-form
                    @add-surcharge="addSurcharges"
                    @remove-surcharge="removeSurcharge"
                    v-for="(surcharge, index) in form.surcharges"
                    :surcharge="surcharge"
                    :number="index"
                    :surcharges="form.surcharges"
                    :key="index">
                </surcharges-sub-form>
            </div>
            <div class="mb-5">
                <h3 class="text-lg text-gray-900 font-medium mb-2">Проценты суммы в офис</h3>
                <sum-percentages-to-office-sub-form
                    @add-sum-percentage-to-office="addSumPercentagesToOffice"
                    @remove-sum="removeSumPercentageToOffice"
                    v-for="(sumPercentageToOffice, index) in form.sumPercentagesToOffice"
                    :sum-percentage-to-office="sumPercentageToOffice"
                    :number="index"
                    :sum-percentages-to-office="form.sumPercentagesToOffice"
                    :key="index">
                </sum-percentages-to-office-sub-form>
            </div>
            <div class="flex justify-end">
                <button type="submit" :disabled="form.processing || form.hasErrors" :class="`leading-tight py-3 bg-orange-400 text-white rounded-md px-7 ${form.processing || form.hasErrors ? 'cursor-not-allowed' : ''}`">
                    Добавить
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import SurchargesSubForm from './SurchargesSubForm';
import SumPercentagesToOfficeSubForm from './SumPercentagesToOfficeSubForm';

export default {
    name: 'DynamicPaymentForm',
    components: {
        SurchargesSubForm,
        SumPercentagesToOfficeSubForm,
    },
    props: {
        dynamicPayment: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                title: this.dynamicPayment?.title,
                maxSumToOffice: this.dynamicPayment?.max_sum_to_office,
                surcharges: this.dynamicPayment?.surcharges || [],
                sumPercentagesToOffice: this.dynamicPayment?.sum_percentages_to_office || [],
            })
        }
    },
    created(){
        if (! this.dynamicPayment.id || this.dynamicPayment.surcharges?.length === 0) {
            this.addSurcharges()
        }

        if (! this.dynamicPayment.id || this.dynamicPayment.sumPercentagesToOffice?.length === 0) {
            this.addSumPercentagesToOffice()
        }
    },
    methods: {
        async submitForm() {
            const method = this.dynamicPayment?.id ? 'put' : 'post'
            const url = method === 'put' ? `/dictionaries/dynamic-commission-calculations/${this.dynamicPayment?.id}` : ''

            await this.form[method](url, {
                onSuccess: () => {
                    method === 'put' ? this.$modal.close('editingScheme') : this.$modal.close('addScheme')
                }
            })
        },
        addSurcharges() {
            this.form.surcharges.push({
                id: null,
                form: null,
                to: null,
                sum: null,
            })
        },
        addSumPercentagesToOffice() {
            this.form.sumPercentagesToOffice.push({
                id: null,
                from: null,
                to: null,
                value: null,
            })
        },
        async removeSurcharge(index) {
            if (this.form.surcharges.length === 1) {
                return
            }

            const surcharge = this.form.surcharges[index]['id']
            if (surcharge) {
                await this.$inertia.delete(`/dictionaries/dynamic-commission-calculations/surcharge/${surcharge}`)
            }
            this.form.surcharges.splice(index, 1)
        },
        async removeSumPercentageToOffice(index) {
            if (this.form.sumPercentagesToOffice.length === 1) {
                return
            }

            const sumPercentageToOffice = this.form.sumPercentagesToOffice[index]['id']

            if (sumPercentageToOffice) {
                await this.$inertia.delete(`/dictionaries/dynamic-commission-calculations/sumPercentageToOffice/${sumPercentageToOffice}`)
            }
            this.form.sumPercentagesToOffice.splice(index, 1)
        },
    },
}
</script>
