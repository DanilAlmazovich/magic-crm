<template>
    <modal name="StatusesListModal" close-text>
        <div class="flex items-center" slot="title">
            <span class="text-xl font-medium mr-36">История изменения статусов заказа</span>
        </div>
        <div class="pt-4">
            <table>
                <tr>
                    <th class="text-sm text-gray-400 text-left pr-10 font-normal py-3">№</th>
                    <th class="text-sm text-gray-400 text-left pr-10 font-normal py-3">Дата</th>
                    <th class="text-sm text-gray-400 text-left pr-10 font-normal py-3">Статус</th>
                    <th class="text-sm text-gray-400 text-left pr-10 font-normal py-3">Создатель</th>
                    <th class="text-sm text-gray-400 text-left pr-10 font-normal py-3">Субъект</th>
                    <th class="text-sm text-gray-400 text-left font-normal py-3">Комментарий</th>
                </tr>
                <tr v-for="(status, index) in statuses"
                    :key="`statuses-list-modal-${status.id}`"
                    class="history-table border-b border-solid border-gray-200">
                    <td class="text-sm text-gray-800 pr-10 py-4">{{ index + 1 }}</td>
                    <td class="text-sm text-gray-800 pr-10 py-4">{{ moment(status.attributes.createdAt).format('HH:mm DD.MM.YYYY') }}</td>
                    <td class="text-sm text-green-700 pr-10 py-4">{{ status.attributes.label }}</td>
                    <td class="text-sm text-gray-800 pr-10 py-4">
                        {{ status.attributes.reportable.label }}: {{ status.attributes.reportable.name }}
                    </td>
                    <td class="text-sm text-gray-800 pr-10 py-4">
                        {{ status.attributes.subject ? status.attributes.subject.label + ': ' + status.attributes.subject.name : '-'}}
                    </td>
                    <td class="text-sm text-gray-800 py-4">{{ status.attributes.comment || '-' }}</td>
                </tr>
            </table>
        </div>
    </modal>
</template>

<script>
export default {
    name: 'StatusesListModal',
    data() {
        return {
            statuses: []
        }
    },
    methods: {
        async open(orderId) {
            const { data: { data } } = await axios.get(`/orders/${orderId}/statuses`)
            this.statuses = data
            this.$modal.open('StatusesListModal')
        }
    }
}
</script>

<style scoped>

</style>
