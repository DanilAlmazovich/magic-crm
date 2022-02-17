<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Схема</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Базовая стоимость</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Беспл. в пределах</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Стоимость 1 км</th>
            </tr>
            <tr v-for="(scheme, index) in schemes">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 pl-2 list">{{ index + 1 }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ scheme.title }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ scheme.base_cost }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ scheme.free_within_area }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ scheme.cost_per_kilometer }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">
                    <div class="flex justify-end items-center">
                        <button class="mr-3 text-orange-400 flex items-center" @click="selectEditingScheme(scheme)">
                            <icon name="editing" class="w-5 h-5 fill-current text-orange-400 mr-2"></icon>
                            <span>Редактировать</span>
                        </button>
                        <button class="w-5 h-5" @click="$modal.open('removePartnerDeliveryScheme')">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </button>
                    </div>
                </td>
            </tr>
        </table>
        <div slot="modals">
            <div class="modal">
                <modal name="editingDeliveryScheme" close-text>
                    <partner-delivery-scheme-form :scheme="selectedScheme"></partner-delivery-scheme-form>
                </modal>
                <modal class="confirmation" name="removePartnerDeliveryScheme" close-text>
                    <div class="flex flex-col items-center px-10">
                        <div class="w-12 h-12 mb-5">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </div>
                        <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                            <span class="leading-none">Вы действительно</span>
                            <span class="leading-none">хотите удалить?</span>
                        </p>
                    </div>
                    <div class="flex justify-center items-center px-5">
                        <button class="text-orange-400 text-base mr-10">Да</button>
                        <button @click="$modal.close('removePartnerDeliveryScheme')" class="text-orange-400 text-base ml-10">Нет</button>
                    </div>
                </modal>
            </div>
        </div>
    </div>
</template>

<script>
import PartnerDeliverySchemeForm from './PartnerDeliverySchemeForm';

export default {
    name: 'DeliverySchemeTable',
    props: {
        schemes: Array,
    },
    components: {
        PartnerDeliverySchemeForm,
    },
    data() {
        return {
            selectedScheme: null,
        }
    },
    methods: {
        selectEditingScheme(scheme) {
            this.selectedScheme = scheme
            this.$modal.open('editingDeliveryScheme')
        }
    }
}
</script>

<style scoped>
.list {
    width: 20%;
}
.list:nth-child(1) {
    width: 4%;
}
</style>
