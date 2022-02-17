<template>
    <div>
        <div class="toggle" @click.stop="toggle" :class="{'is-active': expanded}">
            <slot name="trigger" v-bind:expanded="expanded"></slot>
        </div>
        <div class="inner" ref="inner" :style="{height: height + 'px'}">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            initialHeight: {
                type: Number,
                default: 0
            }
        },
        data() {
            return {
                height: 0,
                expanded: false,
            }
        },
        methods: {
            toggle() {
                this.expanded = !this.expanded

                this.$emit('toggle', this.expanded)

                if (!this.expanded) {
                    return this.height = 0
                }
                this.height = this.$refs.inner.scrollHeight
            }
        },
    }
</script>

<style >
    .inner {
        overflow: hidden;
        transition: height .4s ease
    }
</style>
