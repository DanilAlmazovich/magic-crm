<template>
    <div class="mb-3">
        <div class="flex mb-3">
            <div class="flex items-center px-4 relative">
                <div class="absolute top-0 left-0 w-full h-full flex justify-center items-center">
                    <span class="text-base text-gray-900">1</span>
                </div>
            </div>
            <div class="flex flex-wrap w-full">
                <div class="w-1/4 flex items-end pr-2">
                    <div class="flex flex-col w-full">
                        <input class="px-3 py-2 focus:border-gray-150 focus:outline-none rounded-lg
                                                         border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                               type="text"
                               placeholder="Контактное лицо">
                    </div>
                </div>
                <div class="w-1/4 flex items-end px-1">
                    <div class="flex flex-col w-full">
                        <input class="px-3 py-2 focus:border-gray-150 focus:outline-none rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                               type="text"
                               placeholder="Название филиала">
                    </div>
                </div>
                <div class="w-1/4 flex items-end px-1">
                    <div class="flex flex-col w-full">
                        <the-mask class="px-3 py-2 focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                  mask="+###############"
                                  placeholder="+"/>
                    </div>
                </div>
                <div class="w-1/4 flex items-end pl-2">
                    <div class="flex flex-col w-full relative">
                        <input class="pl-3 pr-12 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                               type="text"
                               placeholder="Адрес">
                        <div class="w-12 h-full absolute top-0 right-0 flex justify-center items-center">
                            <icon name="map" class="h-5 w-5 fill-current text-red-400"></icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between w-full">
            <div class="flex pl-8 w-9/12">
                <div class="w-1/3 flex items-end pr-2">
                    <div class="flex flex-col w-full">
                        <input class="px-3 py-2 focus:border-gray-150 focus:outline-none rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                               type="number"
                               placeholder="Сумма беспл. доставки">
                    </div>
                </div>
                <div class="w-1/3 flex items-end px-1">
                    <div class="flex flex-col w-full">
                        <input class="px-3 py-2 focus:border-gray-150 focus:outline-none rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                               type="text"
                               placeholder="График работы">
                    </div>
                </div>
                <div class="w-1/3 flex items-end pl-2">
                    <multiselect v-model="selectedDeliveryScheme"
                                 class="border-2 border-gray-150 rounded-lg"
                                 placeholder="Схема доставки"
                                 track-by="label"
                                 label="label"
                                 :options="deliveryScheme">
                    </multiselect>
                </div>
            </div>
            <button type="button" class="w-10 h-10 rounded-lg border-2 border-red-400 flex items-center justify-center">
                <icon name="cross" class="h-4 w-4 fill-current text-red-400"></icon>
            </button>
        </div>
    </div>
</template>
