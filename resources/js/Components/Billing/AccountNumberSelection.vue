<template>
    <div class="flex flex-col w-full">
        <label class="text-gray-900 text-xl font-medium mb-3">Выберите номер счета</label>
        <multiselect
            v-model="wallet"
            placeholder="Поиск"
            track-by="id"
            label="label"
            open-direction="above"
            :allow-empty="false"
            :options="options"
            :internal-search="false"
            class="rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0 focus:border-gray-150 focus:outline-none text-gray-900"
            @input="onInput"
            @search-change="searchChange">
        </multiselect>
    </div>
</template>
<script>
import debounce from 'lodash/debounce'

export default {
    props: {
        owners: {
            type: Array,
            default: () => []
        }
    },
    computed: {
        options() {
            return this.wallets.map(w => {
                return {
                    id: w.id,
                    label: w.attributes.label
                }
            })
        }
    },
    data() {
        return {
            wallet: {},
            wallets: []
        }
    },
    methods: {
        onInput(option) {
            this.$emit('wallet-selected', this.wallets.find(wallet => wallet.id === option.id))
        },
        searchChange: debounce(function(search) {
            this.loadWallets(search)
        }, 500),
        async loadWallets(search) {
            let { data } = await axios.get('/billing/wallets-list', {
                params: {
                    search,
                    owners: this.owners
                }
            })

            this.wallets = data.data
        }
    },
    mounted() {
        this.loadWallets()
    }
}
</script>
