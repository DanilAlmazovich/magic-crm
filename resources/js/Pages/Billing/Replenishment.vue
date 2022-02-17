<template>
    <app-layout :name="name" content-width="w-10/12">
        <div class="flex items-center justify-between pt-10 mb-4">
            <div class="pt-2">
                <h1 class="text-3xl font-semibold text-gray-900">Пополнения через системы</h1>
            </div>
        </div>
        <div class="flex justify-between">
            <div class="flex items-center">
                <span class="mr-2 text-gray-900">Номер счета:</span>
                <div class="w-40">
                    <multiselect v-model="selectedAccount"
                                placeholder="номер счета"
                                track-by="label"
                                label="label"
                                :options="account">
                    </multiselect>
                </div>
            </div>
            <button class="text-orange-400 text-base">Сбросить фильтр</button>
        </div>
        <replenishment-table/>
        <div class="flex justify-end">
            <div class="flex w-auto">
                <button class="w-12 h-12 flex items-center justify-center border border-gray-200 rounded-tl-lg rounded-bl-lg">
                    <icon name="arrow" class="w-4 block fill-current text-gray-900 transform -rotate-90"/>
                </button>
                <button class="pagination active hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200">1</button>
                <button class="pagination hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200">2</button>
                <button class="pagination hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200">3</button>
                <button class="pagination hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200">4</button>
                <button class="pagination hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200">5</button>
                <button class="pagination hover:bg-gray-300 w-12 h-12 flex items-center justify-center border border-gray-200 border-l-none rounded-tr-lg rounded-br-lg">
                    <icon name="arrow" class="w-4 block fill-current text-gray-900 transform rotate-90"/>
                </button>
            </div>
        </div>
        <div class="modal">
                <modal class="confirmation" name="allowReplenishment" close-text>
                    <div class="flex flex-col items-center px-10">
                        <div class="w-12 h-12 mb-5">
                            <icon name="success" class="h-auto fill-current text-green-700 mr-2"></icon>
                        </div>
                        <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                            <span class="leading-none">Разрешить</span>
                            <span class="leading-none">пополнение?</span>
                        </p>
                    </div>
                    <div class="flex justify-between items-center px-5">
                        <button class="text-orange-400 text-base">Да</button>
                        <button class="text-orange-400 text-base" @click="$modal.close('allowReplenishment')">Нет</button>
                    </div>
                </modal>
                <modal class="confirmation" name="rejectReplenishment" close-text>
                    <div class="flex flex-col items-center px-10">
                        <div class="w-12 h-12 mb-5">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </div>
                        <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                            <span class="leading-none">Отклонить</span>
                            <span class="leading-none">пополнение?</span>
                        </p>
                    </div>
                    <div class="flex justify-between items-center px-5">
                        <button class="text-orange-400 text-base">Да</button>
                        <button class="text-orange-400 text-base" @click="$modal.close('rejectReplenishment')">Нет</button>
                    </div>
                </modal>
            </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'
import ReplenishmentTable from  '@/Components/Billing/ReplenishmentTable'
import JetDropdown from '@/Jetstream/Dropdown'

export default {
    props: {
        replenishments: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            name: 'billing',
            account: [
                {
                    label: 'Все',
                },
                {
                    label: 'Выполнен',
                },
                {
                    label: 'Не выполнен',
                },
            ],
            selectedAccount: '',
        }
    },
    components: {
        AppLayout,
        ReplenishmentTable,
        JetDropdown,
    }
}
</script>
<style scoped>
    .pagination {
        border-left: none;
    }
    .active {
        border-top: none;
        border-bottom: none;
        background: #FFBE17;
    }
</style>
