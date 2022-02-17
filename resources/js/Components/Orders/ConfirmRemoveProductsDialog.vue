<template>
    <modal class="items-center" name="ConfirmRemoveProductsDialog" :close-button="false">
        <div slot="title">
            <span class="text-xl text-gray-900 font-medium mb-5">
                Подтверждение удаления продуктов
            </span>
        </div>
        <div class="py-4">
            <div>В заведении "{{ branch }}" нет в меню следующих блюд:</div>
            <ul>
                <li v-for="item in items"><strong>{{ item.name }}</strong></li>
            </ul>
            <div>Эти позиции будут удалены</div>
            <div class="flex justify-end">
                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7 mr-5"
                        @click="submit">
                    Удалить
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
    name: 'ConfirmRemoveProductsDialog',
    data() {
        return {
            branch: '',
            items: [],
            resolve: null,
            reject: null,
        }
    },
    methods: {
        cancel() {
            this.$modal.close('ConfirmRemoveProductsDialog')
            this.reject()
        },
        open(data) {
            return new Promise((resolve, reject) => {
                this.branch = data.branch
                this.items = data.items
                this.resolve = resolve
                this.reject = reject

                this.$modal.open('ConfirmRemoveProductsDialog')
            })
        },
        submit() {
            this.$modal.close('ConfirmRemoveProductsDialog')
            this.resolve()
        }
    }
}
</script>
