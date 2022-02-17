<template>
    <div class="flex items-end mb-5 mr-8">
        <span class="mr-2 text-gray-900">Курьер:</span>
        <div>
            <jet-dropdown>
                <div slot="trigger" class="border-b border-gray-900">
                    <input v-model="search"
                           type="text"
                           placeholder="Поиск"
                           class="border-none placeholder-gray-200 text-gray-900">
                </div>
                <div slot="content" class="px-3 py-3">
                    <div class="flex flex-col">
                        <div v-for="user in selected"
                             :key="`user-select-selected-user-list-${user.id}`">
                            <checkbox class="mb-2 justify-end">
                                <span
                                    @click.prevent="unSelect(user)"
                                    class="name text-sm text-gray-900">
                                    {{ user.full_name }}
                                </span>
                            </checkbox>
                        </div>
                    </div>

                    <hr style="border: 2px solid blue;">

                    <div class="flex flex-col">
                        <div v-for="courier in options"
                             :key="`courier-select-unselected-courier-list-${courier.id}`">
                            <checkbox class="mb-2 justify-end">
                                <span @click.prevent="select(courier)"
                                      class="name text-sm text-gray-900">
                                    {{ courier.full_name }}
                                </span>
                            </checkbox>
                        </div>
                    </div>
                    <div class="pt-8">
                        <button class="w-full px-5 py-2 rounded-lg border-orange-400 border-2 text-orange-400 text-base"
                                @click="apply">
                            Применить
                        </button>
                    </div>
                </div>
            </jet-dropdown>
        </div>
    </div>
</template>

<script>
import JetDropdown from '@/Jetstream/Dropdown'
import { debounce } from 'lodash'

export default {
    name: "CourierSelect",
    components: {
        JetDropdown
    },
    props: {
        isActive: {
            type: Boolean,
            default: true
        },
        type: {
            type: String,
            default: undefined
        },
        value: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        options() {
            return this.couriers.filter(courier => {
                return this.selected.findIndex(
                    selectedCourier => courier.id === selectedCourier.id
                ) === -1
            })
        }
    },
    data() {
        return {
            search: null,
            couriers: [],
            selected: []
        }
    },
    methods: {
        apply() {
            this.$emit('input', this.selected)
        },
        async reload () {
            const { data } = await axios.get('/couriers-for-select', {
                params: {
                    is_active: this.isActive,
                    type: this.type,
                    q: this.search,
                    exclude: this.selected.map(c => c.id)
                }
            })
            this.couriers = Array.isArray(data) ? data : []
        },
        select(courier) {
            this.selected.push(courier)
            this.reload()
        },
        unSelect(courier) {
            const index = this.selected.findIndex(selectedCourier => selectedCourier.id === courier.id)
            if (index !== -1) {
                this.selected.splice(index, 1)
                this.reload()
            }
        }
    },
    watch: {
        search: {
            handler: debounce(function() {
                this.reload()
            }, 300),
            immediate: true
        }
    }
}
</script>

<style scoped>

</style>
