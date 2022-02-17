<template>
    <label class="flex checkbox items-center cursor-pointer" :for="value" :disabled="disabled" :class="{'selected': value}">
        <input :id="value" type="checkbox" :value="value" v-model="proxyChecked"
               class="rounded border-gray-300 text-orange-400 shadow-sm focus:border-indigo-300 focus:ring focus:ring-orange-400 focus:ring-opacity-50 hidden">
        <span>
            <slot></slot>
        </span>
        <span class="w-6 h-6 rounded-md border border-solid border-orange-400 flex items-center justify-center bg-white check-icon">
            <icon name="check" class="w-4 h-auto fill-current text-white "/>
        </span>
    </label>
</template>

<script>
    export default {
        model: {
            prop: "checked",
            event: "change",
        },

        props: {
            checked: {
                type: [Array, Boolean],
                default: false,
            },
            value: {
                default: null,
            },
            disabled: Boolean
        },

        computed: {
            proxyChecked: {
                get() {
                    return this.checked;
                },
                set(val) {
                    this.$emit("change", val);
                },
            },
        },
    }
</script>

<style scoped>

    .checkbox input:checked + span + .check-icon {
        background: #FFBE17;
    }

</style>

