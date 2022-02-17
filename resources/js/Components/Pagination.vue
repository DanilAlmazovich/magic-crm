<template>
    <div class="flex justify-end mb-2">
        <div class="flex">
            <inertia-link :href="meta.links[0].url || ''" preserve-state
               :class="{'pointer-events-none bg-gray-100': !meta.links[0].url}"
               class="w-10 h-10 flex items-center justify-center border border-solid border-gray-200 rounded-l-md">
                <icon class="w-3 h-3 fill-current flex transform -rotate-90" name="arrow"/>
            </inertia-link>
            <inertia-link v-for="(link, index) in linkList" :href="link.url || ''" :key="`paginator-link-list-${index}`" preserve-state
               :class="{'text-white bg-orange-400  pointer-events-none border-orange-400': link.active, 'bg-gray-100': !link.url}"
               class="w-10 h-10 flex items-center justify-center border border-solid border-gray-200 border-l-0 first:border-r">
                <span>{{ link.label }}</span>
            </inertia-link>
            <inertia-link :href="meta.links[meta.links.length - 1].url || ''" preserve-state
               :class="{'pointer-events-none bg-gray-100': !meta.links[meta.links.length - 1].url}"
               class="w-10 h-10 flex items-center justify-center border border-solid border-gray-200 border-l-0 rounded-r-md">
                <icon class="w-3 h-3 fill-current flex transform rotate-90" name="arrow"/>
            </inertia-link>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        meta: {
            type: Object,
        }
    },

    computed: {
        linkList() {
            this.meta.links.forEach((link, index) => {
                if(link.label === '...' && this.meta.links.length / 2 > index) {
                    link.url = `${this.meta.path}?page=${Math.round((this.meta.current_page ) / 2)}`
                }
                if(link.label === '...' && this.meta.links.length / 2 < index) {
                    link.url = `${this.meta.path}?page=${Math.round(this.meta.current_page + (this.meta.last_page - (this.meta.current_page)) / 2)}`
                }
            })
            return this.meta.links.slice(1, -1)
        }
    },
}
</script>
