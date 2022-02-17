<template>
    <inertia-link class="flex items-center relative h-5 mb-4 text-gray-600 relative hover:text-gray-900 duration-200 transition"
                  :href="href">
        <icon class="icon mr-7 fill-current w-5 h-5 absolute z-40" :name="icon"></icon>
        <span class="text-lg leading-none transition duration-200 ease-in-out opacity-1 ml-14" :class="{'opacity-0': !sidebarOpen}">{{ name }}</span>
    </inertia-link>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                default: '',
                required: true
            },
            href: {
                type: String,
                default: '',
                required: true
            },
            icon: {
                type: String,
                default: '',
                required: true
            },
            sidebarOpen: {
                type: Boolean,
                default: false,
            }
        }
    }
</script>
