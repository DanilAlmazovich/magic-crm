<template>
    <div class="flex justify-between">
        <div class="flex">
            <div class="flex items-center text-base mr-6">
                <span class="mr-3">Дата с</span>
                <div class="flex items-center">
                    <v-date-picker v-model="dateFrom" mode="date" is-expanded  ref="datepicker" :masks="masks">
                        <template v-slot="{ inputValue, togglePopover }">
                            <div class="flex items-center">
                                <span class="font-medium mr-3"
                                      @click="togglePopover">{{ inputValue }}</span>
                                <button class="flex items-center" @click="togglePopover">
                                    <icon name="calendar" class="dropdown-icon w-4 inline-block fill-current text-orange-400"></icon>
                                </button>
                            </div>
                        </template>
                    </v-date-picker>
                </div>
            </div>
            <div class="flex items-center text-base mr-6">
                <span class="mr-3">Дата по</span>
                <div class="flex items-center">
                    <v-date-picker v-model="dateTo" mode="date" is-expanded  ref="datepicker" :masks="masks">
                        <template v-slot="{ inputValue, togglePopover }">
                            <div class="flex items-center">
                                <span class="font-medium mr-3"
                                      @click="togglePopover">{{ inputValue }}</span>
                                <button class="flex items-center" @click="togglePopover">
                                    <icon name="calendar" class="dropdown-icon w-4 inline-block fill-current text-orange-400"></icon>
                                </button>
                            </div>
                        </template>
                    </v-date-picker>
                </div>
            </div>
            <branch-select v-if="isBranches" :category-ids="categoryIDs" v-model="branches"/>
            <couriers-select v-if="isCouriers" />
            <div v-if="isCategoriesSelect"
                 class="flex mb-5 items-center justify-between rounded min-w-56 leading-tight rounded-md mr-4 pl-2">
                <categories-select v-model="categoryIDs" />
            </div>
            <operators-select v-if="isOperators" v-model="operators" ref="refOperatorsSelect"/>
<!--            <div class="flex items-end mb-5 mr-5">-->
<!--                <span class="mr-2 text-gray-900">Оператор:</span>-->
<!--                <div>-->
<!--                    <jet-dropdown>-->
<!--                        <div slot="trigger" class="border-b border-gray-900">-->
<!--                            <input type="text"-->
<!--                                   placeholder="Поиск"-->
<!--                                   class="border-none placeholder-gray-200 text-gray-900">-->
<!--                        </div>-->
<!--                        <div slot="content" class="px-3 py-3">-->
<!--                            <div class="flex flex-col">-->
<!--                                <div v-for="operator in operators">-->
<!--                                    <checkbox :value="operator.name"-->
<!--                                              :key="operator.name"-->
<!--                                              class="mb-2 justify-end">-->
<!--                                        <span class="ml-4 text-sm text-gray-900">{{ operator.name }}</span>-->
<!--                                    </checkbox>-->
<!--                                    <div class="pl-4" v-for="branch in operator.branches">-->
<!--                                        <checkbox :value="branch.name"-->
<!--                                                  :key="branch.name"-->
<!--                                                  class="mb-2 justify-end">-->
<!--                                            <span class="ml-4 text-sm text-gray-900">{{ branch.name }}</span>-->
<!--                                        </checkbox>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="pt-8">-->
<!--                                <button class="w-full px-5 py-2 rounded-lg border-orange-400 border-2 text-orange-400 text-base">Применить</button>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </jet-dropdown>-->
<!--                </div>-->
<!--            </div>-->
        </div>
        <button class="text-orange-400 text-base" @click="setFilters()">
            Сбросить фильтр
        </button>
    </div>
</template>

<script>
import JetDropdown from '@/Jetstream/Dropdown'
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'
import CouriersSelect from './CouriersSelect'
import OperatorsSelect from './OperatorsSelect'
import BranchSelect from './BranchSelect'
import CategoriesSelect from './CategoriesSelect';

export default {
    props: {
        isBranches: {
            type: Boolean,
            default: false
        },
        isCategoriesSelect: {
            type: Boolean,
            default: false
        },
        isCouriers: {
            type: Boolean,
            default: false
        },
        isOperators: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            branches: [],
            categoryIDs: [],
            couriers: [],
            operators: [],
            dateFrom: null,
            dateTo: null,
            masks: {
                input: 'DD.MM',
            }
        }
    },
    computed: {
        requestData() {
            const data = {}

            if (this.dateFrom) data.date_from = this.dateFrom.toISOString().slice(0, 10)
            if (this.dateTo) data.date_to = this.dateTo.toISOString().slice(0, 10)
            if (this.isBranches && Array.isArray(this.branches) && this.branches.length)
                data.branch_ids = this.branches.map(branch => branch.id)
            if (this.isCouriers && Array.isArray(this.couriers) && this.couriers.length)
                data.courier_ids = this.couriers.map(courier => courier.id)
            if (this.isOperators && Array.isArray(this.operators) && this.operators.length)
                data.operator_ids = this.operators.map(operator => operator.id)
            if (this.isCategoriesSelect && Array.isArray(this.categoryIDs) && this.categoryIDs.length)
                data.category_ids = this.categoryIDs

            return data
        }
    },
    components: {
        BranchSelect,
        OperatorsSelect,
        CategoriesSelect,
        CouriersSelect,
        JetDropdown,
        VDatePicker
    },
    methods: {
        setFilters(params = {}) {
            this.dateFrom = params.date_from ? new Date(params.date_from) : null
            this.dateTo = params.date_to ? new Date(params.date_to) : null
            this.branches = []
            this.categoryIDs = params.category_ids || []

            if (this.isOperators) {
                this.$refs.refOperatorsSelect.reset()
            }
        }
    },
    created() {
        const params = new URL(document.location).searchParams
        if (params.has('category_ids[]')) {
            this.categoryIDs = params.getAll('category_ids[]').map(id => Number(id))
        }
    },
    name: 'FilterPanel',
    watch: {
        requestData: {
            handler(newValue) {
                this.$emit('change', newValue)
            },
            deep: true
        }
    }
}
</script>

<style scoped>

</style>
