<template>
    <div class="flex items-end mb-5 mr-5">
        <span class="mr-2 text-gray-900">Филиал заведения:</span>
        <div>
            <jet-dropdown width="auto" ref="dropdown">
                <div slot="trigger" class="border-b border-gray-900">
                    <input v-model="search"
                           type="text"
                           placeholder="Поиск"
                           class="border-none placeholder-gray-200 text-gray-900">
                </div>
                <div slot="content" class="px-3 py-3">
                    <div>
                        <div class="flex">
                           <div class="min-w-56 flex flex-col pr-3 border-r-2 border-gray-300">
                                <div v-for="partner in options">
                                    <div class="flex items-center" @click.prevent="selectPartner(partner)">
                                        <span
                                            class="name text-base text-gray-800 font-medium cursor-pointer">
                                            {{ partner.fullName }}
                                        </span>
                                    </div>

                                    <div class="pl-4" v-for="branch in partner.branches">
                                        <span @click.prevent="selectBranch(branch)" class="name text-base text-gray-800 cursor-pointer">
                                            {{ branch.fullName }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="min-w-56 flex flex-col pl-3">
                                <span class="text-lg text-black font-medium">Выбрано:</span>
                                <div v-for="branch in selected" class="flex items-center">
                                    <span class="name text-base text-black text-gray-900 cursor-pointer">
                                        {{ branch.fullName }}
                                    </span>
                                    <button class="w-3 h-3 ml-4"  @click.prevent="unSelect(branch)">
                                        <icon name="cross" class="fill-current text-gray-500"></icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button class="w-1/2 px-5 py-2 rounded-lg border-orange-400 border-2 text-orange-400 text-base"
                                    @click="apply">
                                Применить
                            </button>
                        </div>
                    </div>
                </div>
            </jet-dropdown>
        </div>
    </div>
</template>

<script>
import JetDropdown from '@/Jetstream/Dropdown'
import debounce from 'lodash/debounce'
import { cloneDeep } from 'lodash'
import CatalogApi from '../../Apis/CatalogApi';

export default {
    name: "BranchSelect",
    components: {
        JetDropdown
    },
    props: {
        value: {
            type: Array,
            default: () => []
        },
        removedEstablishment: {
            type: Object,
        },
        categoryIds: {
            type: Array,
            default: [],
        }
    },
    data() {
        return {
            partners: [],
            search: null,
            selected: []
        }
    },
    computed: {
        options() {
            return this.partners.map(partner => {
                return {
                    id: partner.crm_id,
                    fullName: partner.name.ru,
                    branches: partner.branches.map(branch => {
                        return {
                            id: branch.crm_id,
                            fullName: branch.name.ru,
                        }
                    }),
                }
            })
        }
    },
    methods: {
        apply() {
            this.$refs.dropdown.close()
            this.$emit('input', cloneDeep(this.selected))
        },
        selectBranch(selBranch) {
            let index = this.selected.findIndex(branch => branch.id === selBranch.id)
            if (index === -1) {
                this.selected.push(selBranch)
            }
        },
        selectPartner(partner) {
            partner.branches.forEach(branch => {
                this.selectBranch(branch)
            });
        },
        unSelect(searchBranch) {
            let index = this.selected.findIndex(branch => branch.id === searchBranch.id)
            if (index !== -1) {
                this.selected.splice(index, 1)
            }
        },
        async reload() {
            this.partners = await CatalogApi.searchPartnersByCategories(this.search, this.categoryIds)
        }
    },
    watch: {
        categoryIds() {
            this.reload()
        },
        search: {
            handler: debounce(function() {
                this.reload()
            }, 300),
            immediate: true
        },
        selected() {
            this.$emit('selected', this.selected)
        },
        removedEstablishment() {
            let index = this.selected.findIndex(branch => branch.id === this.removedEstablishment.id)
            if (index !== -1) {
                this.selected.splice(index, 1)
            }
        }
    }
}
</script>
