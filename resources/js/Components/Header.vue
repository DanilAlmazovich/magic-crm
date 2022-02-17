<template>
    <header>
        <div class="flex justify-between items-center py-6">
            <div class="flex">
                <inertia-link
                        :href="route('orders.list')"
                        :class="route().current('orders.*') ? 'text-orange-400 border-b-2 border-orange-400' : 'text-gray-900'"
                        class="nav-link font-medium leading-relaxed mr-8">Заказы</inertia-link>
                <inertia-link
                        v-if="$page.props.auth.user.isAdmin || $page.props.auth.user.isCourierOperator"
                        :href="route('couriers.index')"
                        :class="route().current('couriers.*') ? 'text-orange-400 border-b-2 border-orange-400' : 'text-gray-900'"
                        class="nav-link font-medium leading-relaxed mr-8">Курьеры</inertia-link>

<!--                TODO: fines and blacklist-->
<!--                <inertia-link v-if="$page.props.auth.user.isAdmin || $page.props.auth.user.isCourierOperator"-->
<!--                              href="fines" class="font-medium text-gray-900 leading-relaxed mr-8">Штрафы</inertia-link>-->
<!--                <inertia-link href="#" class="nav-link font-medium text-gray-900 leading-relaxed mr-8">Черный список</inertia-link>-->
            </div>
<!--            <button class="flex items-center text-gray-900" @click="$inertia.reload()">-->
<!--                <icon name="loading" class="w-4 h-auto fill-current mr-2"></icon>-->
<!--                <span>Обновить</span>-->
<!--            </button>-->
        </div>
    </header>
</template>
