<template>
    <modal class="items-center" name="ClientDataDialog" close-text>
        <div class="flex items-center" slot="title">
            <span class="text-xl font-medium mr-36">Изменить данные клиента</span>
        </div>
        <div class="py-4">
            <div class="flex pb-4 mb-6">
                <div class="flex flex-col">
                    <label class="text-gray-900 font-medium mb-3">Фамилия и имя</label>
                    <input v-model="form.name"
                           type="text"
                           placeholder="Введите фамилию и имя"
                           class="px-3 w-56 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                </div>
                <div class="pl-6 flex flex-col">
                    <label class="text-gray-900 font-medium mb-3">Телефон</label>
                    <div class="w-56">
                        <multiselect placeholder="Телефон"
                                     v-model="form.phone"
                                     class="w-full rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900"
                                     :options="phones"
                                     :taggable="true"
                                     @tag="newPhone">
                        </multiselect>
                    </div>
                </div>
            </div>
            <div class="flex flex-end">
                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7 mr-5"
                        @click="submit">
                    Сохранить
                </button>
                <button class="leading-tight py-3 border border-orange-400 text-orange-400 text-white rounded-md px-7"
                        @click="$modal.close('ClientDataDialog')">
                    Отмена
                </button>
            </div>
        </div>
    </modal>
</template>

<script>
export default {
    name: 'ClientDataDialog',
    props: {
        phones: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            clientId: 0,
            resolve: null,
            form: {
                name: '',
                phone: ''
            }
        }
    },
    methods: {
        newPhone(phone) {
            this.phones.push(phone)
            this.form.phone = phone
        },
        open(data = {}) {
            return new Promise(resolve => {
                this.resolve = resolve
                this.form.name = data.name
                this.form.phone = data.phone
                this.clientId = data.clientId

                this.$modal.open('ClientDataDialog')
            })
        },
        submit () {
            this.$modal.close('ClientDataDialog')
            this.resolve(this.form)
        }
    }
}
</script>

<style scoped>

</style>
