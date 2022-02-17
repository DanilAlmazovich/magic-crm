<template>
    <modal class="confirmation" name="cancellation" close-text>
        <div class="flex flex-col items-center px-10">
            <div class="w-12 h-12 mb-5">
                <icon name="remove" class="h-auto fill-current mr-2"></icon>
            </div>
            <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                <span class="leading-none">Вы действительно</span>
                <span class="leading-none">хотите отменить?</span>
                <span class="leading-none">"{{ operationLabel }}"</span>
            </p>
        </div>

        <label class="text-gray-900 text-xl font-medium mb-3">Коментарий</label>
        <input v-model="comment"
               type="text"
               placeholder="Ввод"
               class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">

        <div class="flex justify-between items-center px-5">
            <button class="text-orange-400 text-base" @click="submit">Да</button>
            <button class="text-orange-400 text-base" @click="$modal.close('cancellation')">Нет</button>
        </div>
    </modal>
</template>

<script>
export default {
    name: 'CancellationModal',
    computed: {
        operationLabel() {
            return this.operation.attributes ? this.operation.attributes.typeLabel: '';
        },
        formData() {
            return {
                comment: this.comment
            }
        }
    },
    data() {
        return {
            comment: '',
            operation: {},
            resolve: null
        }
    },
    methods: {
        open(operation) {
            return new Promise(resolve => {
                this.operation = operation || {}
                this.resolve = resolve
                this.$modal.open('cancellation')
            })
        },
        async submit() {
            await window
                .axios
                .post(`/billing/operations/${this.operation.id}/cancel`, this.formData)

            this.$modal.close('cancellation')
            this.resolve(this.operation)
        }
    }
}
</script>

<style scoped>

</style>
