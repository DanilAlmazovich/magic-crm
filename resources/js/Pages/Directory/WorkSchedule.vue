<template>
    <app-layout-map :name="name" content-width="w-11/12">
        <div class="flex justify-between pt-6 mb-4">
            <div class="pt-2">
                <h1 class="text-3xl font-semibold text-gray-900">График работы</h1>
            </div>
            <div class="flex">
                <div class="flex items-center">
                    <span class="mr-2 text-gray-900">Сортировка:</span>
                    <div class="w-48">
                        <multiselect v-model="selectedCategory"
                                     placeholder="По статусу"
                                     track-by="label"
                                     label="label"
                                     :options="categories">
                        </multiselect>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="mr-2 text-gray-900">Сортировка:</span>
                    <div class="w-48">
                        <multiselect v-model="selectedEstablishment"
                                     placeholder="Заведения"
                                     track-by="label"
                                     label="label"
                                     :options="establishments">
                        </multiselect>
                    </div>
                </div>
                <div class="flex pl-1">
                    <input type="text" class="bg-gray-100 w-56 px-3 rounded-l-md border-gray-100 focus:border-gray-100" placeholder="Поиск партнеров">
                    <button class="bg-orange-400 text-white py-3 px-7 rounded-r-md">Найти</button>
                </div>
            </div>
        </div>
        <work-schedule-table :days-of-week="daysOfWeek" @edit="selectEditSchedule" :schedules="schedules"></work-schedule-table>
        <div slot="modals" class="work-schedule">
            <div class="modal">
                <modal name="workScheduleForm" close-text>
                    <work-schedule-form :week-days="daysOfWeek" :scheduleId="selectedSchedule"></work-schedule-form>
                </modal>
                <modal name="addScheduleForm" close-text>
                    <work-schedule-form :week-days="daysOfWeek"></work-schedule-form>
                </modal>
            </div>
        </div>
    </app-layout-map>
</template>
<script>
import AppLayoutMap from '@/Layouts/AppLayoutMap'
import WorkScheduleTable from '@/Components/Directory/WorkScheduleTable'
import Checkbox from '@/Jetstream/Checkbox'
import DatePicker from 'vue2-datepicker'
import Calendar from 'v-calendar/lib/components/date-picker.umd'
import WorkScheduleForm from '@/Components/Directory/WorkScheduleForm';

export default {
    components: {
        AppLayoutMap,
        WorkScheduleTable,
        Checkbox,
        DatePicker,
        Calendar,
        WorkScheduleForm,
    },
    props: {
        schedules: {
            type: Object,
            default: () => ({})
        },
        daysOfWeek: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            name: 'directory',
            selectedCategory: '',
            categories : [
                {
                    label: 'Кафе',
                    value: 'Cafe',
                },
                {
                    label: 'Флористика',
                    value: 'Floristics',
                },
                {
                    label: 'Сувениры',
                    value: 'Souvenirs',
                },
            ],
            selectedEstablishment: '',
            establishments: [
                {
                    label: 'Все',
                    value: 'everything',
                },
                {
                    label: 'Фаиза',
                    value: 'Faiza',
                },
                {
                    label: 'Нац кухня',
                    value: 'National cuisine',
                },
                {
                    label: 'Европейская кухня',
                    value: 'European cuisine',
                },
                {
                    label: 'Доставка',
                    value: 'Delivery',
                },
                {
                    label: 'Японская кухня',
                    value: 'Japanese food',
                },
                {
                    label: 'Пиццерия и Фастфуд',
                    value: 'Pizzeria',
                },
                {
                    label: 'Корейская кухня',
                    value: 'Korean food',
                },
                {
                    label: 'Боорсок',
                    value: 'Boorsok',
                },
                {
                    label: 'Кухни мира',
                    value: 'World cuisines',
                },
                {
                    label: 'Здоровое питание',
                    value: 'Healthy eating',
                },
            ],
            selectedSchedule: null,
        }
    },
    methods: {
        selectEditSchedule(schedule) {
            this.selectedSchedule = schedule
            this.$modal.open('workScheduleForm')
        }
    }
}
</script>
<style>
    .work-schedule .modal .modal-container .modal-block {
        max-height: 90%;
        overflow-y: scroll;
        width: 50%;
    }
    .work-schedule .modal .modal-container .modal-block::-webkit-scrollbar {
        width: 5px;
        background-color: #f9f9fd;
    }
    .work-schedule .modal .modal-container .modal-block::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background-color: #BFBFBF;
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
