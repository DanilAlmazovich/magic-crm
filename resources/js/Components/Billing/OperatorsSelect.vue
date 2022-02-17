<template>
    <div class="flex items-end mb-5 mr-8">
        <span class="mr-2 text-gray-900">Оператор:</span>
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
                        <div v-for="operator in selected"
                             :key="`operator-select-selected-operator-list-${operator.id}`">
                            <checkbox class="mb-2 justify-end">
                                <span
                                    @click.prevent="unSelect(operator)"
                                    class="name text-sm text-gray-900">
                                    {{ operator.name }}
                                </span>
                            </checkbox>
                        </div>
                    </div>

                    <hr style="border: 2px solid blue;">

                    <div class="flex flex-col">
                        <div v-for="operator in options"
                             :key="`operator-select-unselected-operator-list-${operator.id}`">
                            <checkbox class="mb-2 justify-end">
                                <span @click.prevent="select(operator)"
                                      class="name text-sm text-gray-900">
                                    {{ operator.name }}
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
import { cloneDeep } from 'lodash'

export default {
    name: 'OperatorsSelect',
    components: {
        JetDropdown
    },
    props: {
        value: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        options() {
            return this.operators.filter(courier => {
                return this.selected.findIndex(
                    selectedCourier => courier.id === selectedCourier.id
                ) === -1
            })
        }
    },
    data() {
        return {
            search: null,
            operators: [],
            selected: []
        }
    },
    methods: {
        apply() {
            this.$emit('input', cloneDeep(this.selected))
        },
        async reload () {
            const { data } = await axios.get('/operators-for-select', {
                params: {
                    search: this.search,
                    excludes: this.selected.map(c => c.id)
                }
            })
            this.operators = Array.isArray(data) ? data : []
        },
        select(operator) {
            this.selected.push(operator)
            this.reload()
        },
        reset() {
            const isReload = this.selected.length
            this.selected = []
            this.search = null
            if (isReload) this.reload()
            this.$emit('input', [])
        },
        unSelect(operator) {
            const index = this.selected.findIndex(selectedOperator => selectedOperator.id === operator.id)
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
