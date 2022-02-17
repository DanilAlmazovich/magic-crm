<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Номер счета</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Владелец</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Баланс</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Долг</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr v-for="wallet in wallets"
                :key="`wallet-list-item-${wallet.id}`"
                class="cursor-pointer hover:bg-gray-100">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2 list">
                    {{ wallet.id }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 list">
                    {{ wallet.attributes.number }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 list">
                    {{ wallet.attributes.ownerFullName }}
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 list">
                    {{ wallet.attributes.amount }} сом
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 list">
                    <span v-if="wallet.attributes.debt">{{ wallet.attributes.debt }} сом</span>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 list">
                    <button class="text-orange-400 text-base"
                            @click="$modal.open('transactionsModal', {amount: wallet.attributes.amount, walletId: wallet.id})">
                        Посмотреть состояние
                    </button>
                </td>
            </tr>
        </table>
    </div>
</template>
<script>
export default {
    props: {
        wallets: {
            type: Array,
            default: () => []
        }
    }
}
</script>
<style scoped>
.list:nth-child(1) {
    width: 1%;
}
.list {
    width: 5%;
}
</style>
