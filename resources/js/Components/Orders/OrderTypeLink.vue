<template>
    <inertia-link :href="route(routeName, { phone: this.phone })"
            class="border border-solid bg-white leading-tight p-3 w-56 rounded-md flex justify-between cursor-pointer items-center mr-4"
            :class="[isCurrent ? 'border-orange-400' : 'border-gray-200']">
        <span :class="[isCurrent ? 'text-orange-400' : 'text-gray-800']">
            <slot />
        </span>
        <span class="w-5 h-5 rounded-full border border-solid border-gray-200 relative">
            <span
                v-if="isCurrent"
                class="w-2/3 h-2/3 bg-orange-400 absolute rounded-full inset-0 m-auto"
                >
            </span>
        </span>
    </inertia-link>
</template>

<script>
    export default {
        name: 'OrderTypeLink',
        props: {
            name: String,
            routeName: String,
            phone: String,
        },
        computed: {
            isCurrent() {
                return this.route().current(this.routeName)
            },
        }
    }
</script>
