<template>
    <modal class="items-center" name="OrderAddressDialog" close-text>
        <div slot="title">
            <span class="text-xl text-gray-900 font-medium mb-5">
                {{ address.id ? 'Изменить адрес' : 'Добавить адрес'}}
            </span>
        </div>
        <div class="py-4">
            <div class="flex flex-col mb-6">
                <label class="text-gray-900 font-medium mb-2">Адрес</label>
                <div class="relative" v-click-outside="hideAddressDropdown">
                    <input v-model="address.attributes.address"
                           type="text"
                           placeholder="Введите адрес"
                           class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-full leading-tight py-3 rounded-md focus:border-orange-400"
                           ref="inputAddress"
                           @input="geocode(address.attributes)">
                    <div v-if="geocodeAddressDropdown"
                         class="absolute z-20 left-0 right-0">
                        <div class="rounded border bg-white max-h-60 overflow-y-auto py-1">
                            <div v-for="geocodeAddress in geocodeAddresses"
                                 class="cursor-pointer px-2 hover:bg-gray-100"
                                 @click="selectGeocodeAddress(address.attributes, geocodeAddress)">
                                <strong>{{ geocodeAddress.address }}</strong>
                                <span v-if="geocodeAddress.type !== 'building'"> - {{ geocodeAddress.short }}</span>
                                <span v-if="geocodeAddress.purpose">({{ geocodeAddress.purpose }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex mb-6">
                <div class="flex flex-col">
                    <label class="text-gray-900 font-medium mb-2">Доп инфо</label>
                    <input v-model="address.attributes.additionalInfo"
                           type="text"
                           placeholder="Введите дополнительную информацию"
                           class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md focus:border-orange-400">
                </div>
                <div class="pl-4 flex flex-col">
                    <label class="text-gray-900 font-medium mb-2">Комментарий</label>
                    <input v-model="address.attributes.comment"
                           type="text"
                           placeholder="Введите комментарий"
                           class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md focus:border-orange-400">
                </div>
            </div>
            <div v-if="address.attributes.type === 'pickup'" class="flex pb-4 mb-6">
                <div class="flex flex-col">
                    <label class="text-gray-900 font-medium mb-2">Сумма выкупа</label>
                    <input class="border border-solid border-gray-200 text-gray-800 placeholder-gray-800 w-56 leading-tight py-3 rounded-md focus:border-orange-400"
                           type="number"
                           v-model="address.attributes.buyoutAmount" placeholder="Сумма выкупа">
                </div>
                <div class="pl-4 flex flex-col">
                    <label class="text-gray-900 font-medium mb-2">Тип адреса</label>
                    <div class="bg-white flex items-center justify-between border border-solid
                                border-gray-200 rounded w-56 leading-tight rounded-md text-gray-900 pl-2 py-0.5">
                        <multiselect placeholder="Тип адреса"
                                     v-model="address.attributes.pickupAddressType"
                                     track-by="value"
                                     label="label"
                                     :options="pickupAddressTypes">
                        </multiselect>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7 mr-5"
                        @click="submit">
                    {{ this.address.id ? 'Изменить' : 'Добавить' }}
                </button>
                <button class="leading-tight py-3 border border-orange-400 text-orange-400 text-white rounded-md px-7"
                        @click="$modal.close('OrderAddressDialog')">
                    Отмена
                </button>
            </div>
        </div>
    </modal>
</template>

<script>
import {cloneDeep, debounce} from 'lodash'
import ClickOutside from 'vue-click-outside'
import axios from "axios";

export default {
    name: 'OrderAddressDialog',
    directives: {
        ClickOutside
    },
    props: {
        pickupAddressTypes: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            address: {attributes: {}},
            resolve: null,
            geocodeAddressDropdown: false,
            geocodeAddresses: []
        }
    },
    methods: {
        hideAddressDropdown() {
            this.geocodeAddressDropdown = null
        },
        geocode: debounce(async function (address) {
            if(address.address) {

                const { data: addresses } = await axios.post('/maps/geocode', {
                    address: address.address
                })

                this.geocodeAddresses = addresses

                this.geocodeAddressDropdown = true
            }
        }, 500),
        selectGeocodeAddress(address, geocodeAddress) {
            address.address = `${geocodeAddress.address} (${geocodeAddress.purpose})`

            if (geocodeAddress.type !== 'building') {
                address.address += ` - ${geocodeAddress.short}`
            }

            address.latitude = geocodeAddress.geocode.latitude
            address.longitude = geocodeAddress.geocode.longitude

            this.geocodeAddressDropdown = false
            this.geocodeAddresses = []
        },
        open(address) {
            return new Promise(resolve => {
                this.address = cloneDeep(address)
                this.resolve = resolve
                this.$modal.open('OrderAddressDialog')
                this.$nextTick(() => {
                    this.$refs.inputAddress.focus()
                })
            })
        },
        async submit() {
            if (this.address.attributes.pickupAddressType) {
                this.address.attributes.pickupAddressType = this.address.attributes.pickupAddressType.value
            }

            this.$modal.close('OrderAddressDialog')

            this.resolve(this.address)
        }
    },
}
</script>
