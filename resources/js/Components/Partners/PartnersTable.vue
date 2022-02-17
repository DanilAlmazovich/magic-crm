<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">ФИО</th>
                <th class="text-sm text-left text-gray-200 list pr-1">E-mail</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Название заведения</th>
                <th class="text-sm text-left text-gray-200 list pr-1">% за заказы</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Правовые документы</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Доп инфо</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr v-for="(partner, index) in partners" :key="partner.id">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 pl-2 list">{{ index + 1 }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">
                    <div class="flex flex-col">
                        <span>{{ partner.full_name }}</span>
                        <span>{{ partner.phone }}</span>
                    </div>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ partner.email }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ partner.name }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ `${partner.remuneration} %` }}</td>
                <td class="text-sm text-left text-orange-400 border-b border-solid border-gray-150 leading-none py-4 list">
                    <button class="flex items-center">
                        <icon name="file" class="w-4 h-auto fill-current text-orange-400 mr-2"></icon>
                        <span>Открыть</span>
                    </button>
                </td>
                <td class="text-sm text-left text-orange-400 border-b border-solid border-gray-150 leading-none py-4 list">
                    <button @click="openPartnerForm(partner, true)">Открыть</button>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">
                    <div class="flex justify-center items-center">
                        <button class="w-5 h-5 mr-3" @click="openPartnerForm(partner)">
                            <icon name="editing" class="h-auto fill-current text-orange-400 mr-2"></icon>
                        </button>

                        <button class="w-5 h-5" @click="openConfirmWindow(partner.id)">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </button>
                    </div>
                </td>
            </tr>
        </table>
        <div class="modal">
            <modal name="partnerEditing" close-text>
                <div class="-mt-6">
                    <h2 class="text-xl text-gray-900 font-medium mb-5">Редактировать данные</h2>
                    <partner-data-form :errors="errors" :partner-types="partnerTypes" :partner="selectedPartner" :is-view="isView" :schemes="schemes"></partner-data-form>
                </div>
            </modal>
            <modal name="partner" close-text>
                <div class="-mt-6">
                    <h2 class="text-xl text-gray-900 font-medium mb-5">Данные партнера</h2>
                    <partner-data-form :errors="errors" :partner-types="partnerTypes" :partner="selectedPartner" :schemes="schemes"></partner-data-form>
                </div>
            </modal>
            <modal class="confirmation" name="deletePartnerConfirmation" close-text>
                <div class="flex flex-col items-center px-10">
                    <div class="w-12 h-12 mb-5">
                        <icon name="remove" class="h-auto fill-current mr-2"></icon>
                    </div>
                    <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                        <span class="leading-none">Вы действительно</span>
                        <span class="leading-none">хотите удалить?</span>
                    </p>
                </div>
                <div class="flex justify-between items-center px-5">
                    <button class="text-gray-150 text-base" @click="$modal.close('deletePartnerConfirmation')">Отмена</button>
                    <button class="text-red-400 text-base" @click="deletePartner">Удалить</button>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
import Modal from  '@/Plugins/Modal/Component'
import PartnerDataForm from '@/Components/Partners/PartnerDataForm'

export default {
    components: {
        Modal,
        PartnerDataForm
    },
    props: {
        partners: {
            type: Array,
            default: () => [],
        },
        schemes: {
            type: Array,
            default: () => [],
        },
        errors: Object,
        partnerTypes: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            selectedPartner: null,
            isView: false,
            deletablePartner: null,
            partnerFormModalTitle: ''
        }
    },
    methods: {
        openPartnerForm(partner, isView = false) {
            this.selectedPartner = partner
            this.isView = isView
            this.$modal.open('partnerEditing')
        },
        openConfirmWindow(id) {
            this.$modal.open('deletePartnerConfirmation')
            this.deletablePartner = id
        },

        async deletePartner() {
            if (this.deletablePartner) {
                await this.$inertia.delete(`partners/${this.deletablePartner}`)
                this.$modal.close('deletePartnerConfirmation')
            }
        }
    }
}
</script>

<style scoped>
    .list:nth-child(1) {
        width: 4%;
    }
    .list:nth-child(2) {
        width: 13%;
    }
    .list:nth-child(3) {
        width: 13%;
    }
    .list:nth-child(4) {
        width: 14%;
    }
    .list:nth-child(5) {
        width: 10%;
    }
    .list:nth-child(6) {
        width: 15%;
    }
    .list:nth-child(7) {
        width: 22%;
    }
    .list:nth-child(8) {
        width: 8%;
    }
</style>
