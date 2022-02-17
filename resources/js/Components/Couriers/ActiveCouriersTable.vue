<template>
    <div class="min-w-[800px]">
        <div class="flex items-center" slot="title">
            <span class="text-xl font-medium mr-36">Назначить курьера</span>
        </div>
        <div class="pb-4 pt-6">
            <div class="flex items-center justify-between mb-2">
                <span class="mr-16 font-medium text-lg">Курьеры на смене</span>
                <div class="flex pl-1 mr-4">
                    <input type="text"
                           class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100"
                           placeholder="Поиск курьеров"
                           @keyup.enter="filter"
                           v-model="query">
                    <button class="text-white py-3 px-7 rounded-r-md"
                            :disabled="filterLoading"
                            :class="[filterLoading ? 'bg-gray-600 cursor-not-allowed' : 'bg-yellow-400']"
                            @click="filter">
                        {{ filterLoading ? 'Поиск...' : 'Найти' }}
                    </button>
                </div>
                <div class="flex items-center">
                    <span class="mr-4 text-gray-900">Сортировка:</span>
                    <div class="flex items-center">
                        <multiselect v-model="selectedSortOption"
                                     placeholder="Все"
                                     track-by="value"
                                     label="label"
                                     :options="sortOptions">
                        </multiselect>
                    </div>
                </div>
            </div>
            <div class="mb-6 max-h-96 overflow-y-auto scrollbar">
                <table class="w-full">
                    <tr>
                        <th class="text-left text-sm text-gray-200 pt-1 pb-2">№</th>
                        <th class="text-left text-sm text-gray-200 pt-1 pb-2">ФИ</th>
                        <th class="text-left text-sm text-gray-200 pt-1 pb-2">Телефон</th>
                        <th class="text-left text-sm text-gray-200 pt-1 pb-2">Тип</th>
                        <th class="text-left text-sm text-gray-200 pt-1 pb-2"></th>
                    </tr>
                    <tr v-for="(courier, index) in filteredCouriers" :key="index">
                        <td class="border-b border-solid border-gray-150 text-sm py-2">{{ index + 1 }}</td>
                        <td class="border-b border-solid border-gray-150 text-sm py-2">{{ courier.full_name }}</td>
                        <td class="border-b border-solid border-gray-150 text-sm py-2">{{ courier.phone }}</td>
                        <td class="border-b border-solid border-gray-150 text-sm py-2">{{ courier.type }}</td>
                        <td class="w-5 py-2 pl-10 border-b border-solid border-gray-150">
                            <radio-button v-model="selectedCourier" :value="courier.id" only-icon></radio-button>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="flex justify-end">
                <button @click="assignCourierToOrder" class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7 ml-2">
                    Назначить
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ActiveCouriersTable',
    props: {
        orderId: null,
    },
    created() {
        this.getActiveCouriers()
    },
    data() {
        return {
            selectedCourier: null,
            couriers: [],
            selectedSortOption: null,
            sortOptions: [
                {
                    value: 'operator',
                    label: 'По оператору',
                },
                {
                    value: 'courier',
                    label: 'По курьеру',
                },
                {
                    value: 'partner',
                    label: 'По заведению',
                },
            ],
            query: '',
            filterLoading: false,
            filteredCouriers: [],
        }
    },
    methods: {
        filter() {
            this.filterLoading = true

            this.filteredCouriers = this.couriers.filter(courier => {
                return courier.full_name.toLowerCase().includes(this.query.toLowerCase()) ||
                    courier.phone.includes(this.query)
            })

            this.filterLoading = false
        },
        async getActiveCouriers() {
            await axios.get('/couriers/get/active').then(({ data }) => {
                this.couriers = data
                this.filteredCouriers = this.couriers
            })
        },
        async assignCourierToOrder() {
            await this.$inertia.post(`/orders/${this.orderId}/courier/${this.selectedCourier}`)
                .then(() => {
                    this.selectedCourier = null
                    this.$modal.close('orderAssignCourier')
                })
        }
    }
}
</script>
