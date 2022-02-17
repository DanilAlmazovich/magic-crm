<template>
    <div>
        <div class="min-h-screen flex">
            <sidebar class="w-15/100" @toggle="openSidebar"></sidebar>
            <div class="open-sidebar relative pr-10">
                <div class="w-full h-full">
                    <div
                        class="bg-gray-125 h-full px-6 relative transform origin-left transition duration-300 ease-in-out z-20"
                        :class="[{'-translate-x-full': mapStatus}, pageName === 'orders' && open ? 'w-7/12' : contentWidth]">
                        <button class="absolute left-full top-44 bg-gray-900 px-1 py-4 rounded-r-md z-20"
                                @click="toggleMap">
                            <icon :class="{'rotate-90': mapStatus, '-rotate-90': !mapStatus}" name="arrow"
                                  class="w-4 inline-block fill-current text-white transform"></icon>
                        </button>
                        <v-header v-if="this.name ===  'header'" :loading="loading"/>
                        <component v-else :is="`${ this.name }-header`"/>
                        <main>
                            <slot></slot>
                        </main>
                    </div>
                    <div class="absolute bg-gray-400 right-0 top-0 w-full h-full flex items-center justify-center"
                         :class="[mapStatus ? 'z-10' : 'z-1']">
                        <div id="map" class="w-full h-full"></div>
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
import DG from '2gis-maps'
import axios from 'axios'
import DirectoryHeader from '@/Components/DirectoryHeader'

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
            default: 'header'
        },
        pageName: {
            type: String,
            default: null
        },
    },
    data() {
        return {
            url: null,
            open: JSON.parse(localStorage.getItem('sidebarOpen')),
            mapStatus: false,
            map: null,
            initialized: false,
            couriers: [],
            markers: {},
            interval: null,
            header: 'header',
            statuses: null,
        }
    },
    components: {
        Sidebar,
        VHeader,
        DirectoryHeader,
    },
    async mounted() {
        this.url = window.location.pathname
        this.map = DG.map('map', {
            'center': [42.858, 74.60],
            'zoom': 13,
        });
    },
    methods: {
        async toggleMap() {
            if (this.mapStatus) {
                this.stopPolling()
                this.mapStatus = false

                return
            }

            this.mapStatus = true

            if (!this.initialized) {
                await this.addCouriersToMap()

                this.initialized = true

                this.pollCouriers()
            } else {
                this.pollCouriers()
            }
        },
        async fetchCouriers() {
            const {data} = await axios.get(this.route('couriers.on-map'))
            this.couriers = data.couriers
            this.statuses = data.statuses
        },
        async addCouriersToMap() {
            await this.fetchCouriers()

            this.couriers.forEach(courier => {

                const myDivIcon = DG.divIcon({
                    iconAnchor: [15, 30],
                    popupAnchor: [7, -18],
                    iconSize: ['auto', 30],
                    html: `<div class="dg-custom-icon-wrapper">
                               <div class="dg-custom-label-wrapper">
                                   <div class="dg-custom-label">${ courier.full_name }</div>
                               </div>
                               <svg version="1.1" viewBox="0 0 512 512" width="30" height="30" xml:space="preserve">
                                   <path class="fill-current text-blue-500" d="M256,0C153.755,0,70.573,83.182,70.573,185.426c0,126.888,165.939,313.167,173.004,321.035
                                       c6.636,7.391,18.222,7.378,24.846,0c7.065-7.868,173.004-194.147,173.004-321.035C441.425,83.182,358.244,0,256,0z M256,278.719
                                       c-51.442,0-93.292-41.851-93.292-93.293S204.559,92.134,256,92.134s93.291,41.851,93.291,93.293S307.441,278.719,256,278.719z"/>
                               </svg>
                           </div>`
                });

                const orderLink = courier.orders[0] ? `<span class="order"><a href="${ this.route('orders.show', {order: courier.orders[0].id }) }">#${ courier.orders[0].id }</a></span>` : null

                this.$set(
                    this.markers,
                    courier.id,
                    DG.marker([courier.latitude, courier.longitude], {
                        icon: myDivIcon
                    }).addTo(this.map)
                        .bindPopup(`<div class="map-popup">
                            <div class="flex items-center"><h2 class="name mr-3">${ courier.full_name }</h2>${ orderLink }</div>
                            <div class="flex justify-between items-center"><span class="text-sm text-gray-800 w-1/2">Статус:</span><span class="text-gray-150 text-sm flex flex-col w-1/2"><span>${ courier.status }</span></span></div>
                            <div class="flex justify-between items-center"><span class="text-sm text-gray-800 w-1/2">Назначенных заказов:</span><span class="text-gray-150 text-sm w-1/2">${ courier['current_orders_count'] }</span></div>
                            <div class="triangle w-10 h-10 bg-white absolute transform rotate-90"></div>
                        </div>`)
                )
            })
        },
        pollCouriers() {
            this.interval = setInterval(async () => {
                await this.fetchCouriers()

                this.couriers.forEach(courier => {
                    this.markers[courier.id].setLatLng([courier.latitude, courier.longitude])
                })
            }, 1000)
        },
        stopPolling() {
            clearInterval(this.interval)
        },
        openSidebar(value) {
           this.open = value.sidebarOpen;
        }
    }
}
</script>
<style>
    .open-sidebar {
        transition: width .3s ease-in-out;
        width: calc(100% - 85px);
    }
    .closed-sidebar {
        width: 95%;
    }
    .dg-custom-icon-wrapper {
        position: relative;
    }
    .dg-custom-label-wrapper {
        position: absolute;
        box-shadow: 0px 3px 10px grey;
        bottom: 8px;
        border-radius: 4px;
    }
    .dg-custom-label {
        background-color: white;
        padding: 5px 10px;
        border-radius: 4px;
        white-space: nowrap;
        position: relative;
        z-index: 10;
    }
    .dg-custom-label::before {
        content: '';
        position: absolute;
        left: 7px;
        z-index: -1;
        bottom: -3px;
        width: 15px;
        height: 15px;
        background: white;
        transform: rotate(45deg);
    }
</style>
