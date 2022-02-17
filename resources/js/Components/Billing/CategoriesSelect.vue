<template>
    <multiselect v-model="selectedCategories"
                 placeholder="Выбрать категорию"
                 track-by="id"
                 label="name"
                 :options="categories"
                 :multiple="true"
                 :loading="isLoading"
                 @input="onInput">
    </multiselect>
</template>

<script>
import CatalogApi from '../../Apis/CatalogApi';

export default {
    name: 'CategoriesSelect',
    props: {
        value: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            categories: [],
            isLoading: false,
            selectedCategories: []
        }
    },
    async created() {
        this.isLoading = true
        const categories = await CatalogApi.getPartnersByCategories()
        this.isLoading = false
        this.categories = categories.map(category => ({
            id: category.id,
            name: category.name.ru
        }))
        this.fillSelectedCategory(this.value)
    },
    methods: {
        fillSelectedCategory(ids) {
            this.selectedCategories = this.categories.filter(category => {
                return ids.some(id => category.id === id)
            })
        },
        onInput() {
            this.$emit('input', this.selectedCategories.map(category => category.id))
        }
    },
    watch: {
        value: {
            handler(newValue) {
                this.fillSelectedCategory(newValue)
            },
            immediate: true,
            deep: true
        }
    }
}
</script>
