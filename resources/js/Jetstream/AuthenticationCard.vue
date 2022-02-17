<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <slot name="logo" />
        </div>
        <h1 class="text-3xl font-medium my-8">Вход в систему</h1>
        <div class="w-full sm:max-w-md overflow-hidden sm:rounded-lg px-16 mb-6">
            <slot />
        </div>
    </div>
</template>
