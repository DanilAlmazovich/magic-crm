import axios from 'axios'

class CatalogApi {
    constructor() {
        this.http = axios.create({
            baseURL: process.env.MIX_CATALOG_URL + '/api'
        })
    }

    async getCatalogForBranch(branch) {
        const { data: { data } } = await this.http.get(`crm/catalog/${branch.crm_id || branch.id}/products`)

        return data
    }

    async getDiscountsForBranch(establishment) {

        const { data: { data } } = await this.http.post(
            `crm/catalog/discounts`,
            {
                crm_id: establishment.selectedBranch.id,
                amount: establishment.products.reduce((total, product) => {
                    return total + (product.present ? 0 : ((product.discount_price || product.price) * product.quantity))
                }, 0),
                products: establishment.products.map(item => {
                    return {
                        id: item.externalId || item.id,
                        quantity: item.quantity,
                        discount_id: item.externalDiscountId || item.discount_id
                    }
                })
            })

        return data
    }

    async getPartnersByCategories() {
        const { data: { data } } = await this.http.get('crm/catalog/categories/partners')

        return data
    }

    async searchPartnersByCategories(search, categoryIds) {
        const { data: { data } } = await this.http.get('crm/catalog/categories/partners/search', {
            params: {
                categoryIds: categoryIds,
                search: search
            }
        })

        return data
    }
}

export default new CatalogApi
