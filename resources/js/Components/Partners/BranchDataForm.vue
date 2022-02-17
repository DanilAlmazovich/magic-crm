<template>
    <div class="mb-3 branch">
        <div class="flex mb-4">
            <div class="flex items-center px-4 relative">
                <div class="absolute top-0 left-0 w-full h-full flex justify-center items-center">
                    <span class="text-base text-gray-900">{{ number + 1 }}</span>
                </div>
            </div>
            <div class="flex flex-wrap w-full">
                <div class="w-1/4 flex items-end pr-2">
                    <div class="flex flex-col w-full">
                        <input class="px-3 py-2 focus:border-gray-150 focus:outline-none rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                               type="text"
                               name="full_name"
                               required
                               v-model="branch.full_name"
                               placeholder="Контактное лицо"
                               :disabled="isView">
                    </div>
                </div>
                <div class="w-1/4 flex items-end px-1">
                    <div class="flex flex-col w-full">
                        <input class="px-3 py-2 focus:border-gray-150 focus:outline-none rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                               type="text"
                               name="name"
                               required
                               v-model="branch.name"
                               placeholder="Название филиала"
                               :disabled="isView">
                    </div>
                </div>
                <div class="w-1/4 flex items-end px-1">
                    <div class="flex flex-col w-full">
                        <the-mask class="px-3 py-2 focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                  mask="+###############"
                                  name="phone"
                                  v-model="branch.phone"
                                  placeholder="+"
                                  :disabled="isView" />
                    </div>
                </div>
                <div class="w-1/4 flex items-end pl-2">
                    <div class="flex flex-col w-full relative">
                        <input class="pl-3 pr-12 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                               type="text"
                               name="address"
                               required
                               v-model="branch.address"
                               placeholder="Адрес"
                               @blur="geocodeAddress"
                               :disabled="isView"
                        >

                        <div class="w-12 h-full absolute top-0 right-0 flex justify-center items-center">
                            <icon name="map" class="h-5 w-5 fill-current text-red-400"></icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between w-full">
            <div class="flex pl-8 w-9/12">
                <div class="w-1/3 flex items-end pr-2">
                    <div class="flex flex-col w-full">
                        <input class="px-3 py-2 focus:border-gray-150 focus:outline-none rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                               type="number"
                               name="freeDeliveryValue"
                               required
                               min="0"
                               v-model="branch.free_delivery_value"
                               placeholder="Сумма беспл. доставки"
                               :disabled="isView"
                        >
                    </div>
                </div>
                <div class="w-1/3 flex items-end pl-2 relative">
                    <multiselect
                        v-model="selectedSchedule"
                        class="border-2 border-gray-150 rounded-lg"
                        placeholder="График работы"
                        track-by="id"
                        label="name"
                        @select="scheduleSelected"
                        :allow-empty="false"
                        :options="schedules"
                        :disabled="isView"
                    >
                    </multiselect>
                </div>
                <div class="w-1/3 flex items-end pl-2">
                    <multiselect
                        v-model="selectedDeliveryScheme"
                        class="border-2 border-gray-150 rounded-lg"
                        placeholder="Схема доставки"
                        track-by="value"
                        label="label"
                        :allow-empty="false"
                        @select="schemeSelected"
                        :options="schemes"
                        :disabled="isView"
                    >
                    </multiselect>
                </div>
            </div>
            <button @click="destroy( { branchId: branch.id, partnerId: branch.partner_id })"
                    :disabled="isView"
                    type="button"
                    :class="`w-10 h-10 rounded-lg border-2 border-red-400 flex items-center justify-center ${isView ? 'cursor-not-allowed' : ''}`">
                <icon name="cross" class="h-4 w-4 fill-current text-red-400"></icon>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "branch-data-form",
    props: {
        branch: Object,
        schemes: {
            type: Array,
            default: () => [],
        },
        isView: {
            default: false,
            type: Boolean,
            required: false,
        },
        number: null,
        error: null,
    },
    data() {
      return {
          selectedDeliveryScheme: null,
          selectedSchedule: null,
          schedules: [],
      }
    },
    methods: {
        async destroy({ branchId = null, partnerId = null }) {
            if (branchId && partnerId) {
                await this.$inertia.delete(`partners/${partnerId}/branch/${branchId}`)
            }
            this.$destroy();
            this.$parent.form['branches'].splice(this.number, 1)
        },
        schemeSelected(val) {
            this.branch.selectedSchemeId = val.value
        },
        scheduleSelected(val) {
            this.branch.selectedWorkSchedule = val.id
        },
        async geocodeAddress() {
            const { data: addresses } = await axios.post('maps/geocode', {
                address: this.branch.address
            })

            const nearestAddress = addresses.length ? addresses[0] : null

            if (nearestAddress) {
                this.branch.latitude = nearestAddress.geocode.latitude
                this.branch.longitude = nearestAddress.geocode.longitude
                this.branch.address = nearestAddress.full
            }
        },
        async getSchedules() {
            await axios.get('/dictionaries/work-schedules').then(({data}) => {
                this.schedules = data.data
            })

            if (this.branch?.id && this.branch?.work_schedule) {
                this.schedules.push(this.branch.work_schedule)
                this.selectedSchedule = this.schedules.find(schedule => schedule.id === this.branch.work_schedule.id)
            }
        },
    },
    created() {
        this.getSchedules()

        if (this.branch?.id) {
            this.selectedDeliveryScheme = this.schemes.find(val => val.value === this.branch.delivery_scheme_id)
        }
    }
}
</script>
<style>
    .multiselect .multiselect__content-wrapper .multiselect__content {
        max-height: 100px;
        overflow-y: scroll;
    }
    .multiselect .multiselect__content-wrapper .multiselect__content::-webkit-scrollbar {
        width: 5px;
        background-color: #f9f9fd;
    }
    .multiselect .multiselect__content-wrapper .multiselect__content::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background-color: #BFBFBF;
    }
</style>

