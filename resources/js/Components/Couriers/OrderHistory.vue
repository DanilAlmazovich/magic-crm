<template>
    <tr>
        <td class="border-l-2 border-orange-400"></td>
        <td class="text-lg text-left text-gray-900 leading-none pl-2 py-3 border-b border-gray-150">{{ order.id }}</td>
        <td class="text-lg text-left text-gray-900 border-b border-gray-150 leading-none py-3">{{ moment(order.created_at).format('YYYY-MM-DD') }}</td>
        <td class="text-lg text-left text-gray-900 border-b border-gray-150 leading-none py-3">{{ status }}</td>
        <td class="text-base text-left text-gray-900 border-b border-gray-150 leading-none py-3">
            <span v-if="order.reviews_avg_star">
                <icon name="star" class="dropdown-icon w-4 h-4 h-auto inline-block fill-current text-orange-400"></icon>
                <span class="text-base text-gray-900 font-medium">{{ parseFloat(order.reviews_avg_star).toFixed(2) }}</span>
            </span>
        </td>
        <td class="text-base text-orange-400 border-b border-gray-150 leading-none py-3">
            <button @click="open()">
                Подробнее
            </button>
        </td>
    </tr>
</template>
<script>
    export default {
        props: ['order', 'status'],
        methods: {
            open() {
                this.$emit('open')
            }
        }
    }
</script>
