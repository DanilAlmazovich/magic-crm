<template>
    <div class="w-full pb-10">
        <div class="flex justify-end py-2 items-center">
            <button type="button" class="px-4 py-3 rounded-lg border-none flex items-center" @click="$modal.open('addScheduleForm')">
                            <span class="w-3 h-3 transform rotate-45 mr-3">
                                <icon name="cross" class="fill-current text-orange-400"></icon>
                            </span>
                <span class="leading-none text-orange-400 text-base">Добавить график</span>
            </button>
        </div>
        <table class="w-full">
            <tr>
                <th class="text-sm text-left text-gray-200 list pr-1">№</th>
                <th class="text-sm text-left text-gray-200 list pr-1">Наименование</th>
                <th class="text-sm text-left text-gray-200 list pr-1">График работы</th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
                <th class="text-sm text-left text-gray-200 list pr-1"></th>
            </tr>
            <tr v-for="(schedule, index) in schedules.data">
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 pl-2 list">{{ index + 1 }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ schedule.attributes.name }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">{{ getDaysOfWeek(schedule) }}</td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list"></td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">
                    <div class="flex justify-end items-center">
                        <button class="mr-3 text-orange-400 flex items-center" @click="$emit('edit', schedule.id)">
                            <icon name="editing" class="w-5 h-5 fill-current text-orange-400 mr-2"></icon>
                            <span>Редактировать</span>
                        </button>
                    </div>
                </td>
                <td class="text-sm text-left text-gray-900 border-b border-solid border-gray-150 leading-none py-4 list">
                    <div class="flex justify-end items-center">
                        <button class="w-5 h-5" @click="removingSchedule(schedule)">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </button>
                    </div>
                </td>
            </tr>
        </table>
        <div class="modal">
            <modal class="confirmation" name="removeWorkSchedule" close-text>
                <div class="flex flex-col items-center px-10">
                    <div class="w-12 h-12 mb-5">
                        <icon name="remove" class="h-auto fill-current mr-2"></icon>
                    </div>
                    <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                        <span class="leading-none">Вы действительно</span>
                        <span class="leading-none">хотите удалить?</span>
                    </p>
                </div>
                <div class="flex justify-center items-center px-5">
                    <button class="text-orange-400 text-base mr-10" @click="removeSchedule">Да</button>
                    <button @click="$modal.close('removeWorkSchedule')" class="text-orange-400 text-base ml-10">Нет</button>
                </div>
            </modal>
        </div>
    </div>
</template>
<script>
import WorkScheduleForm from '@/Components/Directory/WorkScheduleForm';

export default {
    name: 'WorkScheduleTable',
    components: {
        WorkScheduleForm
    },
    props: {
        schedules: {
            type: Object,
            default: () => ({}),
        },
        daysOfWeek: {
            type: Array,
            default: () => [],
        }
    },
    data() {
        return {
            selectedRemoveSchedule: null
        }
    },
    methods: {
        getDaysOfWeek(schedule) {
            let daysOfWeek = []

            schedule.relationships.workHours.map((item) => {
                if (item.attributes.dayOfWeek >= 0) {
                    daysOfWeek.push(this.daysOfWeek[item.attributes.dayOfWeek])
                }
            })

            const sortedDays = []
            daysOfWeek = daysOfWeek.sort()

            this.daysOfWeek.map((day) => {
                daysOfWeek.map(el => {
                    if (day === el) {
                        sortedDays.push(day)
                    }
                })
            })

            return sortedDays.join(', ')
        },
        removingSchedule(schedule) {
            this.selectedRemoveSchedule = schedule.id
            this.$modal.open('removeWorkSchedule')
        },
        async removeSchedule() {
            await this.$inertia.delete(`/dictionaries/work-schedule/${this.selectedRemoveSchedule}`, {
                onSuccess: () => {
                    this.$modal.close('removeWorkSchedule')
                    this.selectedRemoveSchedule = null
                }
            })
        }
    },
}
</script>

<style scoped>
.list {
    width: 23%;
}
.list:nth-child(1) {
    width: 4%;
}
.work-schedule .modal .modal-container .modal-block {
    max-height: 90%;
    width: 50%;
}
.work-schedule .modal .calendar .modal-block {
    width: 21%;
    border-bottom-left-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
}
.work-schedule .vc-container {
    border: none;
}
</style>
