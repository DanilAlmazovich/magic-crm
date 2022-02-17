<template>
    <div class="flex items-center rounded bg-gray-100 counter">
        <button v-if="!disabled" class="px-2 py-2" @click.prevent="minusValue()">
            <icon name="minus" class="w-4 h-auto block"></icon>
        </button>
        <input type="number"
               class="counter w-5 text-center p-0 border-none bg-gray-100 font-medium text-lg mx-1"
               :disabled="disabled"
               v-model="quantity">
        <button v-if="!disabled" class="px-2 py-2" @click.prevent="plusValue()">
            <icon name="plus" class="w-4 h-auto block fill-current text-orange-400"></icon>
        </button>
    </div>
</template>

<script>
    export default {

        props: {
            value: Number,
            disabled: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                quantity: this.value
            }
        },

        watch: {
            quantity(val) {
                this.$emit("input", parseInt(val))
            }
        },

        methods: {
            minusValue() {
                if(this.quantity > 0) {
                    this.quantity--
                }
            },
            plusValue() {
                this.quantity++
            }
        },

    }
</script>

<style scoped>

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

</style>

