<template>
    <transition name="modal-fade">
        <div class="modal-container fixed h-screen w-full top-0 left-0 flex justify-center items-center pt-4" v-if="show" :class="confirmed ? 'z-40' : 'z-100'">
            <div class="modal-block relative rounded-xl bg-white mb-8 py-5 px-6 max-h-full overflow-y-auto scrollbar">
                <div class="flex justify-between items-center">
                    <div class="mr-4">
                        <slot name="title"/>
                    </div>
                    <button v-if="closeButton" @click="confirmed ? $modal.question().then(() => close()) : close()" class="button-close close flex items-center">
                        <svg class="fill-current text-red-400 w-3 h-3" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;">
                            <g>
                                <g>
                                    <path d="M284.3,256L506.1,34.1c7.8-7.8,7.8-20.5,0-28.3c-7.8-7.8-20.5-7.8-28.3,0L256,227.7L34.1,5.9C26.3-2,13.7-2,5.9,5.9
                                s-7.8,20.5,0,28.3L227.7,256L5.9,477.9c-7.8,7.8-7.8,20.5,0,28.3c3.9,3.9,9,5.9,14.1,5.9s10.2-2,14.1-5.9L256,284.3l221.9,221.9
                                c3.9,3.9,9,5.9,14.1,5.9s10.2-2,14.1-5.9c7.8-7.8,7.8-20.5,0-28.3L284.3,256z"/>
                                </g>
                            </g>
                        </svg>
                        <span class="text-red-400 ml-3" v-if="closeText">Закрыть</span>
                    </button>
                </div>
                <slot :data="slotData" />
            </div>
            <div class="absolute h-full w-full z-back blackout top-0" @click="close"></div>
        </div>
    </transition>
</template>

<script>
    import modals from './modals'
    export default {
        props: {
            name: {
                type: String,
                default: '',
            },
            closeButton: {
                type: Boolean,
                default: true
            },
            closeText: {
                type: Boolean,
                default: false
            },
            confirmed: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                show: false,
                slotData: {}
            }
        },
        methods: {
            close() {
                this.$modal.close(this.name)
            }
        },
        mounted() {
            modals.$on('close', name => {
                if(name === this.name){
                    this.show = false
                }
            })
            modals.$on('open', (name, data) => {
                if(name === this.name){
                    this.show = true
                    this.slotData = data
                }
            })
        },
    }
</script>
<style scoped>
.z-back {
    z-index: -1;
}
.blackout {
    background: rgba(220, 220, 220, 0.45);
}
.modal-fade-enter-active, .modal-fade-leave-active {
    transition: opacity .3s;
}
.modal-fade-enter, .modal-fade-leave-to {
    opacity: 0;
}
</style>
