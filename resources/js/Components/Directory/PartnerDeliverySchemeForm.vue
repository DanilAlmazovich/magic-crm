<template>
    <div class="-mt-6">
        <form @submit.prevent="submitForm()">
            <h2 class="text-xl text-gray-900 font-medium mb-5">Редактировать</h2>
            <div class="flex pb-8">
                <div class="w-1/3 pr-6 relative flex flex-col pb-7">
                    <label for="scheme"
                           class="text-gray-900 text-xl font-medium mb-3">Схема</label>
                    <input id="scheme"
                           type="text"
                           placeholder="Ввод"
                           required
                           v-model="form.title"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                    <span v-if="form.errors.title" class="absolute bottom-0 left-0 leading-none text-red-400 text-sm px-1">{{ form.errors.title }}</span>
                </div>
                <div class="w-1/3 px-3 flex flex-col relative pb-7">
                    <label for="basic-pay"
                           class="text-gray-900 text-xl font-medium mb-3">Базовая стоимость</label>
                    <input id="basic-pay"
                           type="number"
                           placeholder="Ввод"
                           required
                           v-model="form.baseCost"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                    <span v-if="form.errors.baseCost" class="absolute bottom-0 left-0 leading-none text-red-400 text-sm px-1">{{ form.errors.baseCost }}</span>
                </div>
                <div class="w-1/3 pl-6 flex flex-col relative pb-7">
                    <label for="payment-per-hour"
                           class="text-gray-900 text-xl font-medium mb-3">Беспл. в пределах</label>
                    <input id="payment-per-hour"
                           type="number"
                           placeholder="Ввод"
                           required
                           v-model="form.freeWithinArea"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                    <span v-if="form.errors.freeWithinArea" class="absolute bottom-0 left-0 leading-none text-red-400 text-sm px-1">{{ form.errors.freeWithinArea }}</span>
                </div>
            </div>
            <div class="flex justify-between">
                <div class="w-1/3 pr-6 flex flex-col">
                    <label for="one-kilometer"
                           class="text-gray-900 text-xl font-medium mb-3">Стоимость 1 км</label>
                    <input id="one-kilometer"
                           type="number"
                           placeholder="Ввод"
                           required
                           v-model="form.costPerKilometer"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                </div>
                <div class="flex items-end">
                    <button type="submit" :disabled="form.processing || form.hasErrors" :class="`leading-tight py-3 bg-orange-400 text-white rounded-md px-7 ${form.processing || form.hasErrors ? 'cursor-not-allowed' : ''}`">
                        Сохранить
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: 'PartnerDeliverySchemeForm',
    props: {
        scheme: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                title: this.scheme?.title,
                baseCost: this.scheme?.base_cost,
                freeWithinArea: this.scheme?.free_within_area,
                costPerKilometer: this.scheme?.cost_per_kilometer
            })
        }
    },
    methods: {
        submitForm() {
            const method = this.scheme?.id ? 'put' : 'post'
            const url = method === 'post' ? '' : `/dictionaries/partner-delivery-schemes/${this.scheme.id}`

            this.form[method](url, {
                onSuccess: () => {
                    method === 'post' ? this.$modal.close('addScheme') : this.$modal.close('editingDeliveryScheme')
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
