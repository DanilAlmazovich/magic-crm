<template>
    <form @submit.prevent="submit" class="-mt-6">
        <h2 class="text-xl text-gray-900 font-medium mb-5">Редактировать данные клиента</h2>
        <div class="flex pb-4">
            <div class="w-4/12 pr-2 flex flex-col">
                <label class="text-gray-900 text-xl font-medium mb-3">Данные клиента</label>
                <the-mask class="px-3 py-2 focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                          mask="+###############"
                          v-model="form.phone"
                          :aria-disabled="isView"
                          placeholder="+"/>
                <span v-if="form.hasErrors && form.errors.phone" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">{{ form.errors.phone }}</span>
            </div>
            <div class="w-4/12 px-1 flex flex-col pt-6">
                <div class="mb-4"></div>
                <input type="text"
                       required
                       placeholder="ФИ"
                       :disabled="isView"
                       v-model="form.name"
                       class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                <span v-if="form.hasErrors && form.errors.name" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">{{ form.errors.name }}</span>
            </div>
        </div>
        <div class="flex pb-6">
            <div class="w-full pr-2 flex flex-col mr-2">
                <label class="text-gray-900 text-xl font-medium mb-3">Причина блокировки</label>
                <textarea cols="10" rows="3"
                          v-model="form.reason"
                          required
                          :disabled="isView"
                          class="w-full px-3 py-2 focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                          placeholder="Ввод"></textarea>
                <span v-if="form.hasErrors && form.errors.reason" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">{{ form.errors.reason }}</span>
            </div>
        </div>
        <div class="flex justify-end">
            <button class="leading-tight py-3 bg-orange-400 text-white rounded-md px-7" :disabled="form.processing || isView" type="submit">
                Добавить
            </button>
        </div>
    </form>
</template>

<script>
export default {
    name: 'BlacklistForm',
    props: {
        client: {
            type: Array,
            default: () => []
        },
        isView: false,
        blacklist: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.blacklist.name ?? this.client['name'] ?? null,
                reason: this.blacklist.reason ?? null,
                phone: this.blacklist.phone ?? this.client['phone'] ?? null,
            })
        }
    },
    methods: {
        async submit() {
            await this.form.post('/blacklist', {
                onSuccess: () => {
                    this.$modal.close('addToBlacklist')
                    this.$modal.open('userAddedSuccessfullyIntoBlacklist')
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
