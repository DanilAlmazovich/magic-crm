<template>
    <div class="w-full pb-10">
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">ФИ</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Телефон</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Тип</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Заказы за день</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1">Статус</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr class="cursor-pointer hover:bg-gray-100"
                v-for="courier in couriers"
                @click.prevent="$inertia.visit(`couriers/${courier.id}`)"
                :key="courier.id">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-3 pl-2 list" v-text="courier.id"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list" v-text="courier.full_name"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list" v-text="phone(courier)"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list" v-text="transports[courier.type]"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list" v-text="courier.orders_count"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list" v-text="courier.schedule"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list" v-text="status(courier)"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-6 list">
                    <div class="flex justify-center items-center">
                        <button class="w-5 h-5 mr-3" v-on:click.stop="$emit('openEditForm', courier)">
                            <icon name="editing" class="h-auto fill-current text-orange-400 mr-2"></icon>
                        </button>
                        <button class="w-5 h-5" v-on:click.stop="openRemoveConfirm(courier.id)">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </button>
                    </div>
                </td>
            </tr>
        </table>
        <modal class="confirmation" name="removeCourierConfirmation" close-text>
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
                <button class="text-orange-400 text-base mr-10" @click="removeCourier">Да</button>
                <button @click="$modal.close('removeCourierConfirmation')" class="text-orange-400 text-base ml-10">Нет</button>
            </div>
        </modal>
    </div>
</template>

<script>
import CourierEditForm from '@/Components/Couriers/CourierEditForm';

export default {
    props: {
        couriers: Array,
        transports: Object,
        calculationTypes: Object,
        dynamicCalculationTypes: Array,
        constantCourierPayments: Array,
    },
    components: {
        CourierEditForm
    },
    data() {
        return {
            selectedCourier: null
        }
    },
    methods: {
        phone(courier) {
            return courier.phone.replace(/(\d{3})(\d{3})(\d{3})(\d{3})/, '+$1 ($2) $3-$4')
        },
        status(courier) {
            return courier.is_active ? 'Активен' : 'Не активен'
        },
        openRemoveConfirm(courierId) {
            this.selectedCourier = courierId
            this.$modal.open('removeCourierConfirmation')
        },
        removeCourier() {
            this.$inertia.delete(`/couriers/${this.selectedCourier}`, {
                onSuccess: () => {
                    this.$modal.close('removeCourierConfirmation')
                }
            })
        }
    }
}
</script>

<style scoped>
.list {
    width: 15%;
}
.list:nth-child(1) {
    width: 4%;
}
 .courier-modals .modal .modal-container .modal-block {
     width: 55%;
 }
.courier-modals .modal .modal-container .modal-block {
    max-height: 90%;
    overflow-y: scroll;
}
.courier-modals .modal .modal-container .modal-block::-webkit-scrollbar {
    width: 5px;
    background-color: #f9f9fd;
}
.courier-modals .modal .modal-container .modal-block::-webkit-scrollbar-thumb {
    border-radius: 10px;
    background-color: #BFBFBF;
}
</style>
