<template>
    <div class="py-4 mb-1">
        <h2 class="text-xl font-semibold text-gray-900 mb-3">Тип заказа</h2>
        <div class="flex">
            <order-type-link
                route-name="orders.create.standard"
                class="mr-4"
                :phone="phone"
            >
                Стандартный
            </order-type-link>
            <order-type-link
                route-name="orders.create.personal"
                :phone="phone"
            >
                Личный курьер
            </order-type-link>
        </div>
    </div>
</template>

<script>
    import OrderTypeLink from '@/Components/Orders/OrderTypeLink'

    export default {
        name: 'OrderType',
        components: {
            OrderTypeLink,
        },
        props: {
            phone: String,
        },
    }
</script>
