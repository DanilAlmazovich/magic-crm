<template>
    <div>
        <div class="min-h-screen flex">
            <sidebar class="w-15/100" @toggle="openSidebar"></sidebar>
            <div class="relative px-6 bg-gray-125" :class="open ? 'w-85/100' : 'open'">
                <div v-if="!disabledHeader">
                    <v-header v-if="this.name ===  'header'" :loading="loading"/>
                    <component  v-else :is="`${ this.name }-header`"/>
                </div>
                <main>
                    <slot></slot>
                    <modal class="confirmation" name="question" close-text>
                        <div class="flex flex-col items-center px-10">
                            <div class="w-12 h-12 mb-5">
                                <icon name="remove" class="h-auto fill-current mr-2"></icon>
                            </div>
                            <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                                <span class="leading-none">Вы уверены</span>
                                <span class="leading-none">что хотите выполнить это действие?</span>
                            </p>
                        </div>
                        <div class="flex justify-center items-center px-5">
                            <button class="text-orange-400 text-base mr-10" @click="$modal.confirm()">Да</button>
                            <button @click="$modal.close('question')" class="text-orange-400 text-base ml-10">Нет</button>
                        </div>
                    </modal>
                </main>
            </div>
        </div>
    </div>
</template>

<script>
    import Sidebar from '@/Components/Sidebar'
    import VHeader from '@/Components/Header'
    import ClientsHeader from '@/Components/ClientsHeader'
    import BillingHeader from '@/Components/BillingHeader'

    export default {
        props: {
            loading: {
                type: Boolean,
                default: false
            },
            disabledHeader: {
              type: Boolean,
              default: false
            },
            name: {
                type: String,
                default: 'header'
            }
        },
        data() {
            return {
                open: JSON.parse(localStorage.getItem('sidebarOpen')),
            }
        },
        components: {
            Sidebar,
            VHeader,
            ClientsHeader,
            BillingHeader,
        },
        methods: {
            openSidebar(value) {
                this.open = value.sidebarOpen;
            }
        }
    }
</script>

<style scoped>
    .open {
        width: calc(100% - 83px);
    }
</style>
