import CatalogApi from '../Apis/CatalogApi'

export default {
    methods: {
        newPickupAddress(){
            return {
                longitude: null,
                latitude: null,
                comment: null,
                additionalInfo: null,
                address: null,
                type: 'pickup',
                isLargeSize: false,
                buyoutAmount: null,
                pickupAddressType: null,
            }
        },
        async loadPartnersByCategoriesWithBranches() {
            const allPartners = {}
            const categories = await CatalogApi.getPartnersByCategories()
            categories.forEach(category => {
                category.name = category.name.ru
                category.partners.forEach(partner => {
                    partner.name = partner.name.ru

                    if (partner.branches.length) {
                        partner.branches.forEach(branch => branch.name = branch.name.ru)
                    } else {
                        partner['$isDisabled'] = true
                    }

                    allPartners[`partner-${partner.id}`] = partner;
                })
            })

            const selectedCategory = {
                name: 'Все категории',
                id: 0,
                partners: Object.keys(allPartners).map(index => allPartners[index])
            }

            categories.unshift(selectedCategory)

            return categories
        }
    }
}
