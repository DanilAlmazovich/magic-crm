<template>
    <app-layout-map :name="name" content-width="w-11/12">
        <div class="delivery-promotions">
            <div class="pt-2 flex justify-between items-center pb-6">
                <h1 class="text-3xl font-semibold text-gray-900">Акции по доставке</h1>
                <button class="text-base text-orange-400 flex items-center" @click="$modal.open('addShare')">
                    <icon name="plus" class="w-4 fill-current mr-2"></icon>
                    <span>Добавить схему</span>
                </button>
            </div>
            <delivery-promotions-table></delivery-promotions-table>
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
        </div>
        
        <div slot="modals">
            <div class="modal delivery-promotions">
                <modal name="addShare" close-text>
                    <div class="-mt-6">
                        <h2 class="text-xl text-gray-900 font-medium mb-5">Добавить акцию</h2>
                        <div class="flex justify-between pb-8">
                            <div class="flex w-9/12">
                                <div class="w-1/2 pr-6 flex flex-col">
                                    <label for="share-name"
                                        class="text-gray-900 text-xl font-medium mb-3">Наименование</label>
                                    <input id="share-name" type="text"
                                        placeholder="Ввод"
                                        class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                            focus:border-gray-150 focus:outline-none text-gray-900">
                                </div>
                                <div class="w-1/2 px-3 flex flex-col">
                                    <label for="percent"
                                        class="text-gray-900 text-xl font-medium mb-3">Процент снижения</label>
                                    <input id="percent" type="number"
                                        placeholder="Ввод"
                                        class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                            focus:border-gray-150 focus:outline-none text-gray-900">
                                </div>
                            </div>
                            <div class="flex items-end">
                                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7">
                                    Сохранить
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </modal>
                <modal name="editShare" close-text>
                    <div class="-mt-6">
                        <h2 class="text-xl text-gray-900 font-medium mb-5">Редактировать</h2>
                        <div class="flex justify-between pb-8">
                            <div class="flex w-9/12">
                                <div class="w-1/2 pr-6 flex flex-col">
                                    <label for="scheme"
                                        class="text-gray-900 text-xl font-medium mb-3">Наименование</label>
                                    <input id="scheme" type="text"
                                        placeholder="Ввод"
                                        class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                            focus:border-gray-150 focus:outline-none text-gray-900">
                                </div>
                                <div class="w-1/2 px-3 flex flex-col">
                                    <label for="basic-pay"
                                        class="text-gray-900 text-xl font-medium mb-3">Процент снижения</label>
                                    <input id="basic-pay" type="number"
                                        placeholder="Ввод"
                                        class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                            focus:border-gray-150 focus:outline-none text-gray-900">
                                </div>
                            </div>
                            <div class="flex items-end">
                                <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7">
                                    Сохранить
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </modal>
            </div>
        </div>
    </app-layout-map>
</template>
<script>
import AppLayoutMap from '@/Layouts/AppLayoutMap'
import DeliveryPromotionsTable from '@/Components/Directory/DeliveryPromotionsTable'

export default {
    components: {
        AppLayoutMap,
        DeliveryPromotionsTable,
    },
    data () {
        return {
            name: 'directory',

        }
    }
}
</script>
<style>
    .modal .modal-container .modal-block {
        max-height: 90%;
        width: 50%;
    }
    .delivery-promotions .pagination{
        border-left: none;

    }
    .delivery-promotions .active {
        border-top: none;
        border-bottom: none;
        background: #FFBE17;
    }
</style>
