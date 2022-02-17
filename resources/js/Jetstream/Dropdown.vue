<template>
    <div class="relative" :class="{'selected': open}">
        <div @click="open = ! open">
            <slot name="trigger"></slot>
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="fixed inset-0 z-40" @click="open = false">
        </div>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <div v-show="open"
                    class="absolute z-50 mt-4 rounded-md -right-4 shadow-default"
                    :class="[widthClass, alignmentClasses]"
                    style="display: none;">
                <div class="rounded-md content relative" :class="contentClasses">
                    <slot name="content"></slot>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: {
            align: {
                default: 'right'
            },
            width: {
                default: '48'
            },
            contentClasses: {
                default: () => ['py-1', 'bg-white']
            }
        },

        data() {
            return {
                open: false
            }
        },

        created() {
            const closeOnEscape = (e) => {
                if (this.open && e.keyCode === 27) {
                    this.open = false
                }
            }

            this.$once('hook:destroyed', () => {
                document.removeEventListener('keydown', closeOnEscape)
            })

            document.addEventListener('keydown', closeOnEscape)
        },

        computed: {
            widthClass() {
                return {
                    '48': 'w-48',
                    'auto': 'w-auto',
                }[this.width.toString()]
            },

            alignmentClasses() {
                if (this.align === 'left') {
                    return 'origin-top-left left-0'
                } else if (this.align === 'right') {
                    return 'origin-top-right right-0'
                } else {
                    return 'origin-top'
                }
            },
        },

        methods: {
            close() {
                this.open = false
            }
        }
    }
</script>

<style scoped>
    .content:before {
        position: absolute;
        content: '';
        top: -10px;
        right: 12px;
        width: 1.5rem;
        height: 1.5rem;
        background: white;
        transform: rotate(45deg);
        border-radius: 0.25rem;
        z-index: -1;
        box-shadow: 0 0 10px 0 #E0E0E0;
    }
</style>
