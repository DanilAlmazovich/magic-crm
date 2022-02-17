<template>
    <header>
        <div class="flex justify-between items-center py-6">
            <div class="flex flex-wrap">
                <inertia-link
                    :href="route('clients')"
                    :class="route().current('clients') ? 'text-orange-400 border-b-2 border-orange-400' : 'text-gray-900'"
                    class="nav-link font-medium text-gray-900 leading-relaxed mb-5 mr-8">Все клиенты</inertia-link>
                <inertia-link
                    :href="route('blacklist.index')"
                    :class="route().current('blacklist.index') ? 'text-orange-400 border-b-2 border-orange-400' : 'text-gray-900'"
                    class="nav-link font-medium text-gray-900 leading-relaxed mb-5 mr-8">Черный список</inertia-link>
            </div>
        </div>
    </header>
</template>
