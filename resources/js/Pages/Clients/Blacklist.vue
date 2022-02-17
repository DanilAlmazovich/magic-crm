<template>
    <app-layout :name="name" content-width="w-10/12">
        <div id="blacklist">
            <div class="flex justify-end items-center -mt-16">
                <button type="button" class="pl-4 py-3 rounded-lg border-none flex items-center" @click="$modal.open('addToBlacklist')">
                    <span class="w-3 h-3 transform rotate-45 mr-3">
                        <icon name="cross" class="fill-current text-orange-400"></icon>
                    </span>
                    <span class="leading-none text-orange-400 text-base">Добавить в черный список</span>
                </button>
            </div>
            <div class="flex justify-between pt-16 mb-4">
                <div class="pt-2">
                    <h1 class="text-3xl font-semibold text-gray-900">Черный список</h1>
                </div>
                <div class="flex pl-1 mr-8">
                    <input type="text"
                           class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100"
                           placeholder="Поиск клиентов">
                    <button class="bg-orange-400 text-white py-3 px-7 rounded-r-md">Найти</button>
                </div>
            </div>
            <blacklist-table :blacklists="blacklists" :roles="roles"></blacklist-table>
            <pagination :meta="blacklists"></pagination>
            <div slot="modals">
                <div class="modal">
                    <modal class="clients" name="addToBlacklist" close-text>
                        <blacklist-form></blacklist-form>
                    </modal>
                    <modal class="clients" name="viewBlacklistItem" close-text>
                        <blacklist-form :blacklist="item" :is-view="true"></blacklist-form>
                    </modal>
                    <modal name="userAddedSuccessfully" class="successfull">
                        <div class="py-4 mb-2 flex flex-col items-center px-10">
                            <icon name="green-check" class="mb-4"></icon>
                            <div class="text-center w-48">
                                <span class="font-medium">Пользователь успешно добавлен</span>
                            </div>
                        </div>
                    </modal>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'
import BlacklistTable from '@/Components/Clients/BlacklistTable'
import BlacklistForm from '@/Components/Clients/BlacklistForm'
import Pagination from '@/Components/Pagination'

export default {
    data() {
        return {
            name: 'clients',
        }
    },
    components: {
        AppLayout,
        BlacklistTable,
        BlacklistForm,
        Pagination
    },
    props: {
        blacklists: {
            type: Object,
            default: () => ({})
        },
        roles: {
            type: Object,
            default: () => ({})
        },
        item: {
            type: Object,
            default: () => ({})
        }
    },
    mounted() {
        if (this.item.id) {
            this.$modal.open('viewBlacklistItem')
        }
    }
}
</script>
<style>
    #blacklist .modal .modal-block{
        width: 42%;
    }
    #blacklist .modal .successfull .modal-block{
        border-radius: 0.75rem;
        width: auto;
    }
</style>
