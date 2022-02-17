<template>
    <div class="flex items-center pb-3">
        <span class="text-base text-black mr-5">{{ number + 1 }}</span>
        <div class="w-3/12 pr-2 flex flex-col">
            <input type="number"
                   v-model="sumPercentageToOffice.from"
                   required
                   placeholder="Сумма от"
                   class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
        </div>
        <div class="w-3/12 px-1 flex flex-col">
            <input type="number"
                   v-model="sumPercentageToOffice.to"
                   required
                   placeholder="Сумма до"
                   class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
        </div>
        <div class="w-3/12 pl-2 flex flex-col mr-2">
            <input id="payment-per-hour"
                   type="number"
                   v-model="sumPercentageToOffice.value"
                   required
                   placeholder="Процент суммы в офис"
                   class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
        </div>
        <button
            v-if="increasable"
            @click="$emit('add-sum-percentage-to-office')"
            class="border border-solid border-orange-400 p-3 rounded-md flex items-center">
            <icon name="plus" class="w-4 h-4 block fill-current text-orange-400"/>
        </button>

        <button
            v-else-if="removable"
            @click.prevent="$emit('remove-sum', number)"
            class="border border-solid border-red-400 p-3 rounded-md flex items-center">
            <icon name="close" class="w-4 h-4 block fill-current text-red-400"/>
        </button>
    </div>
</template>

<script>
export default {
    name: 'SumPercentagesToOfficeSubForm',
    props: {
        sumPercentageToOffice: Object,
        number: Number,
        sumPercentagesToOffice: Array,
    },
    computed: {
        removable() {
            return  this.sumPercentagesToOffice.length > 1
        },
        increasable() {
            return ! this.number
        }
    },
}
</script>

<style scoped>

</style>
