<template>
    <modal class="items-center" name="AddBranchDialog" close-text>
        <div slot="title">
            <span class="text-xl text-gray-900 font-medium mb-5">
                Добавить заведение
            </span>
        </div>
        <div class="flex items-center mb-5">
            <div class="bg-white flex items-center justify-between border border-solid mr-5
                                border-gray-200 rounded w-56 leading-tight rounded-md mr-4 text-gray-900 pl-2 py-0.5">
                <multiselect v-model="selectedCategory"
                             :allow-empty="false"
                             placeholder="Категории"
                             track-by="id"
                             label="name"
                             :options="categories">
                </multiselect>
            </div>
            <div class="bg-white flex items-center justify-between border border-solid mr-5
                                border-gray-200 rounded w-56 leading-tight rounded-md mr-4 text-gray-900 pl-2 p-0.5">
                <multiselect v-model="selectedPartner"
                             placeholder="Заведения"
                             track-by="id"
                             label="name"
                             :options="partners">
                </multiselect>
            </div>
        </div>
        <div class="py-4">
            <div class="flex justify-end py-4 text-orange-400">
                <button class="flex items-center" @click.prevent="submit">
                    <icon class="w-3 h-3 fill-current mr-3" name="plus"/>
                    <span>Добавить заведение</span>
                </button>
            </div>
        </div>
    </modal>
</template>

<script>
import OrdersMixins from '@/Mixins/orders-mixins'

export default {
    name: 'AddBranchDialog',
    data() {
        return {
            categories: [],
            selectedCategory: null,
            selectedPartner: null,
            resolve: null
        }
    },
    async created() {
        this.categories = await this.loadPartnersByCategoriesWithBranches()
        this.selectedCategory = this.categories[0]
    },
    computed: {
        partners() {
            return this.selectedCategory?.partners || []
        }
    },
    methods: {
        open() {
            return new Promise(resolve => {
                this.resolve = resolve

                this.$modal.open('AddBranchDialog')
            })
        },
        submit() {
            this.$modal.close('AddBranchDialog')
            this.resolve(this.selectedPartner)
        }
    },
    mixins: [OrdersMixins]
}
</script>
