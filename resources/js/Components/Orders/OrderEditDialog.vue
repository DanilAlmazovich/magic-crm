<template>
    <modal class="items-end" name="OrderEditDialog" close-text>
        <div class="mt-8">
            <h2 class="text-xl text-gray-900 font-medium mb-5">Изменить данные заказа</h2>
            <div class="flex pb-4 mb-6">
                <div class="mr-4">
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">Тип заказа</h2>
                    <div class="flex">
                        <div class="relative" v-for="(label, type) in deliveryTypes">
                            <radio-button class="mr-4"
                                          :class="type === 'pre-order' ? ['absolute', 'left-0', 'top-0', 'bg-white'] : []"
                                          @change="selectDeliveryType(type)"
                                          :value="type" :label="label"
                                          v-if="type === 'pre-order' ? !form.deliveryTime : true"
                                          v-model="form.deliveryType"/>
                            <div v-if="type === 'pre-order'">
                                <v-date-picker v-model="form.deliveryTime" mode="dateTime" :minute-increment="5" is24hr ref="datepicker">
                                    <template v-slot="{ inputValue, togglePopover }">
                                        <div
                                            class="flex items-center border border-solid border-orange-400 w-44 focus:border-orange-400 mr-4 px-3 py-3 rounded-md">
                                            <input class="leading-tight w-full text-orange-400"
                                                   @click.prevent="togglePopover"
                                                   :value="inputValue"/>
                                            <icon name="edit" @click.prevent="togglePopover"
                                                  class="w-4 h-4 inline-block fill-current text-orange-400"></icon>
                                        </div>
                                    </template>
                                </v-date-picker>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex pb-4 mb-6 mt-16">
                <div class="w-2/4 pl-4 flex flex-col">
                    <label class="text-gray-900 text-xl font-medium mb-3">Сдача с</label>
                    <input v-model="form.change"
                           type="text"
                           placeholder="Введите сдачу"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                </div>
                <div class="w-2/4 pl-4 flex flex-col">
                    <label class="text-gray-900 text-xl font-medium mb-3">Примечание</label>
                    <input v-model="form.comment"
                           type="text"
                           placeholder="Введите примечание"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                </div>
            </div>
            <div class="flex justify-end">
                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7 mr-5 mt-16"
                        @click="submit">
                    Сохранить
                </button>
                <button class="leading-tight py-3 border border-orange-400 text-orange-400 text-white rounded-md px-7 mt-16"
                        @click="$modal.close('OrderEditDialog')">
                    Отмена
                </button>
            </div>
        </div>
    </modal>
</template>

<script>
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'

export default {
    name: 'OrderEditDialog',
    components: {
        VDatePicker
    },
    props: {
        deliveryTypes: {
            type: Object,
            default: {}
        }
    },
    data() {
        return {
            form: {
                change: 0,
                comment: '',
                deliveryTime: null,
                deliveryType: Object.keys(this.deliveryTypes)[0]
            },
            orderId: null,
            resolve: null
        }
    },
    methods: {
        open(data = {}) {
            return new Promise(resolve => {
                this.form.change = data.change
                this.form.comment = data.comment
                this.form.deliveryTime = data.deliveryTime
                this.form.deliveryType = data.deliveryType
                this.resolve = resolve

                this.$modal.open('OrderEditDialog')
            })
        },
        selectDeliveryType(type) {
            if (type === 'pre-order') {
                return this.$refs.datepicker[0].togglePopover()
            }

            this.form.deliveryTime = null
        },
        submit() {
            this.$modal.close('OrderEditDialog')
            this.resolve(this.form)
        }
    }
}
</script>

<style scoped>

</style>
