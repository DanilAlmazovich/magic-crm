<template>
    <div class="radio-button">
        <input :id="value" type="radio" class="hidden" :value="value" v-model="proxyChecked">
        <label v-if="!onlyIcon" :for="value" class="border bg-white border-solid border-gray-200 leading-tight p-3 rounded-md flex justify-between cursor-pointer items-center" :class="width">
            <span class="text-gray-800 check-title">{{ label || value }}</span>
            <span class="w-5 h-5 rounded-full border border-solid border-gray-200 relative check-wrap">
                <span class="w-2/3 h-2/3 bg-orange-400 absolute rounded-full inset-0 m-auto check hidden"></span>
            </span>
        </label>
        <label v-if="onlyIcon" :for="value" class="leading-tight p-3 rounded-md flex justify-between cursor-pointer items-center">
            <span class="w-5 h-5 rounded-full border border-solid border-gray-200 relative check-wrap">
                <span class="w-2/3 h-2/3 bg-orange-400 absolute rounded-full inset-0 m-auto check hidden"></span>
            </span>
        </label>
    </div>
</template>

<script>
    export default {
        model: {
            prop: "checked",
            event: "change",
        },

        props: {
            checked: {
                default: false,
            },
            value: {
                type: [String, Number],
                default: null,
            },
            label: String,
            width: {
                type: String,
                default: 'w-56'
            },
            onlyIcon: {
                type: Boolean,
                default: false
            }
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

    .radio-button input:checked + label {
        border-color: #FFBE17;
    }
    .radio-button input:checked + label .check-title {
        color: #FFBE17;
    }
    .radio-button input:checked + label .check-wrap {
        border-color: #FFBE17;
    }
    .radio-button input:checked + label .check-wrap .check {
        display: block;
    }

</style>

