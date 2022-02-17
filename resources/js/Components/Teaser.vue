<template>
    <p>
        <span v-text="description" class="text-base text-gray-900 mr-1"></span>
        <button class="text-orange-400 text-base" v-if="text.length >= maxLength && isCollapsed"
                @click="isCollapsed = false">Свернуть</button>
        <button class="text-orange-400 text-base" v-if="text.length >= maxLength && !isCollapsed"
                @click="isCollapsed = true">Развернуть</button>
    </p>
</template>

<script>
    export default {

        props: {
            text: {
                type: String,
                default: ''
            },
            maxLength: {
                type: Number,
                default: 30
            }
        },

        data() {
            return {
                isCollapsed: false,
            }
        },

        computed: {
            description() {
                return this.isCollapsed ? this.text : _.truncate(this.text, {'length': this.maxLength})
            }
        },
    }
</script>