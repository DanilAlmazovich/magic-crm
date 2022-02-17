<template>
    <div>
        <div class="min-h-screen flex">
            <sidebar class="w-15/100" @toggle="openSidebar"></sidebar>
            <div class="open-sidebar relative pr-10" :class="{'closed-sidebar': !open}">
                <div class="w-full h-full">
                    <div class="bg-gray-125 h-full px-6 relative transform origin-left transition duration-300 ease-in-out" :class="[{'-translate-x-full': mapStatus}, contentWidth]">
                        <button class="absolute left-full top-44 bg-gray-900 px-1 py-4 rounded-r-md" @click="mapStatus = !mapStatus">
                            <icon :class="{'rotate-90': mapStatus, '-rotate-90': !mapStatus}" name="arrow" class="w-4 inline-block fill-current text-white transform"></icon>
                        </button>
                        <div v-if="name !== 'directory'">
                            <v-header ></v-header>
                        </div>
                        <div v-if="name === 'directory'">
                            <directory-header></directory-header>
                        </div>
                        <div v-if="name === 'clients'">
                            <clients-header></clients-header>
                        </div>
                        <main>
                            <slot></slot>
                        </main>
                    </div>
                    <div class="absolute bg-gray-400 -z-1 right-0 top-0 w-full h-full">
                    </div>
                </div>
            </div>
            <slot name="modals"></slot>
        </div>
    </div>
</template>

<script>
import Sidebar from '@/Components/Sidebar'
import VHeader from '@/Components/Header'
import DirectoryHeader from '@/Components/DirectoryHeader'
import ClientsHeader from '@/Components/ClientsHeader'

export default {
    props: {
        loading: {
            type: Boolean,
            default: false
        },
        contentWidth: {
            type: String,
            default: 'w-full'
        },
        name: {
            type: String,
        }
    },
    data() {
        return {
            open: false,
            mapStatus: false
        }
    },
    components: {
        Sidebar,
        VHeader,
        DirectoryHeader,
        ClientsHeader
    },
    methods: {
        openSidebar(value) {
           this.open = value.open;
        }
    }
}
</script>
<style scoped>
    .open-sidebar {
        transition: width .3s ease-in-out;
        width: calc(100% - 85px);
    }
    .closed-sidebar {
        width: 95%;
    }
</style>
