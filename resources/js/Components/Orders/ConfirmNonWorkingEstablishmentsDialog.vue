<template>
    <modal class="items-center" name="ConfirmNonWorkingEstablishmentsDialog" :close-button="false">
        <div slot="title">
            <span class="text-xl text-gray-900 font-medium mb-5">
                Подтверждение удаления продуктов
            </span>
        </div>
        <div class="py-4">
            <div>Эти заведения не работают в указанное в заказе время:</div>
            <ul>
                <li v-for="branch in branches"><strong>{{ branch.name }}</strong></li>
            </ul>
            <div class="flex justify-end">
                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7 mr-5"
                        @click="submit">
                    Продолжить
                </button>
                <button class="leading-tight py-3 border border-orange-400 text-orange-400 text-white rounded-md px-7"
                        @click="cancel">
                    Отмена
                </button>
            </div>
        </div>
    </modal>

</template>

<script>
export default {
    name: 'ConfirmNonWorkingEstablishmentsDialog',
    data() {
        return {
            branches: [],
            resolve: null,
            reject: null,
        }
    },
    methods: {
        cancel() {
            this.$modal.close('ConfirmNonWorkingEstablishmentsDialog')
            this.reject()
        },
        open(branches) {
            return new Promise((resolve, reject) => {
                this.branches = branches
                this.resolve = resolve
                this.reject = reject

                this.$modal.open('ConfirmNonWorkingEstablishmentsDialog')
            })
        },
        submit() {
            this.$modal.close('ConfirmNonWorkingEstablishmentsDialog')
            this.resolve()
        }
    }
}
</script>
