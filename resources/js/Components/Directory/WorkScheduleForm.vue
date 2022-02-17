<template>
    <form @submit.prevent="submit">
        <div class="-mt-6">
            <h2 class="text-xl text-gray-900 font-medium mb-5">График работы</h2>
            <div>
                <input type="text"
                       required
                       v-model="form.name"
                       placeholder="Наименование"
                       class="text-gray-500 border-2 border-gray-150 rounded-lg w-60 px-3 py-2 text-base">
            </div>
            <div class="flex">
                <div class="w-2/3 border-r-2 border-gray-150 pr-6">
                    <day :week-days="weekDays" v-for="(day, index) in days" :key="index" :day="day"></day>
                </div>
                <div class="w-1/3 pl-6">
                    <p class="text-gray-900 text-lg font-medium leading-none mb-5">Добавить переопределения даты</p>
                    <p class="text-gray-150 text-base leading-none mb-5">Добавьте даты, когда ваша доступность изменится с ваших рабочих часов в неделю</p>
                    <button class="w-full py-3 mb-4 rounded-lg border-2 border-orange-400 text-orange-400 text-center"
                            type="button" @click.prevent="$modal.open('addCalendar')">Добавить</button>
                    <div class="flex justify-between border-b-2 border-gray-150 py-4" v-for="(item, idx) in workingDates" :key="idx">
                        <span class="text-base text-gray-900">{{ moment(item.specificDate).format('DD-MM-YY') }}</span>
                        <span class="text-base text-gray-900">{{ item.opening }} - {{ item.closing }}</span>
                        <button type="button" @click.prevent="removeHour(idx)" class="rounded-full border-red-400 border-2 border-solid flex items-center w-4 h-4 justify-center mr-4">
                            <icon name="close" class="w-2 h-2 fill-current text-red-400 inline-block"/>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex justify-end pt-8">
                <button type="submit" @click.prevent="submit" :disabled="form.processing" class="px-10 py-3 text-white bg-orange-400 rounded-lg">Сохранить</button>
            </div>
        </div>
        <modal class="calendar overflow-y-scroll" name="addCalendar">
            <div class="py-3 h-full">
                <p class="text-center text-xl font-medium leading-none mb-8">Выберите дату/даты, которую вы хотите назначить определенными часами</p>
                <div class="flex justify-center">
                    <calendar :value=null v-model="workingDateTmp.specificDate"  is-expanded class="border-none"></calendar>
                </div>
                <div>
                    <div
                        class="flex items-center justify-between"
                        v-bind:class="!idx ? `border-b-2 border-gray-150 pb-4` : `py-4`"
                        v-for="(item, idx) in workingDateTmp.hours" :key="idx">
                        <div class="flex items-center">
                            <date-picker v-model="item.opening"
                                         format="HH:mm"
                                         type="time"
                                         class="time-picker border-gray-150 border-2 text-gray-150 px-3 py-2 rounded-lg"
                                         placeholder="00:00">
                            </date-picker>
                            <span class="px-3">-</span>
                            <date-picker v-model="item.closing"
                                         format="HH:mm"
                                         type="time"
                                         class="time-picker border-gray-150 border-2 text-gray-150 px-3 py-2 rounded-lg"
                                         placeholder="00:00">
                            </date-picker>
                        </div>
                        <div v-if="!idx" class="flex items-center">
                            <button type="button" @click.prevent="addTimeIntervalIntoDateTmp" class="border border-solid border-orange-400 p-3 rounded-md mr-12 flex items-center  ml-3">
                                <icon name="plus" class="w-4 h-4 block fill-current text-orange-400"/>
                            </button>
                            <button type="button" @click.prevent="removeTimeIntervalFromDateTmp(idx)" class="rounded-full border-red-400 border-2 border-solid flex items-center w-4 h-4 justify-center mr-4">
                                <icon name="close" class="w-2 h-2 fill-current text-red-400 inline-block"/>
                            </button>
                        </div>
                        <button v-else type="button" @click.prevent="removeTimeIntervalFromDateTmp(idx)" class="rounded-full border-red-400 border-2 border-solid flex items-center w-4 h-4 justify-center mr-4">
                            <icon name="close" class="w-2 h-2 fill-current text-red-400 inline-block"/>
                        </button>
                    </div>
                </div>
                <div class="flex justify-between">
                    <button class="text-base text-gray-150" type="button" @click.prevent="cancelWorkDateModalForm">Отмена</button>
                    <button class="text-base text-orange-400" type="button" @click.prevent="submitWorkDateModalForm">Выбрать</button>
                </div>
            </div>
        </modal>
    </form>
</template>

<script>
import DatePicker from 'vue2-datepicker'
import Calendar from 'v-calendar/lib/components/date-picker.umd'
import Day from './Day';
import moment from 'moment';

export default {
    name: 'WorkScheduleForm',
    components: {
        DatePicker,
        Calendar,
        Day
    },
    props: {
        scheduleId: null,
        weekDays: {
            default: () => [],
            type: Array,
        }
    },
    data() {
        return {
            days: [],
            workingDates: [],
            workingDateTmp: {
                specificDate: new Date(),
                hours: []
            },
            schedule: null,
            form: this.$inertia.form({
                name: null,
                workHours: [],
            }),
            allHours: [],
        }
    },
    methods: {
        async submit() {
            this.prepareHours()

            const url = this.scheduleId ? `work-schedule/${this.scheduleId}` : 'work-schedule'
            const method = this.scheduleId ? 'put' : 'post'

            await this.form[method](url, {
                onSuccess: () => {
                    method === 'post' ? this.$modal.close('addScheduleForm') : this.$modal.close('workScheduleForm')
                }
            })
        },
        addTimeIntervalIntoDateTmp() {
            this.workingDateTmp.hours.push({
                opening: null,
                closing: null,
            })
        },
        removeTimeIntervalFromDateTmp(index) {
            this.workingDateTmp.hours.splice(index, 1)
        },
        submitWorkDateModalForm() {
            const specificDate = moment(this.workingDateTmp.specificDate).format('YYYY-MM-DD')
            this.workingDateTmp.hours.map((item) => {
                this.workingDates.push({
                    opening: moment(item.opening).format('HH:mm'),
                    closing: moment(item.closing).format('HH:mm'),
                    specificDate,
                })
            })
            this.clearWorkDateTmp()
            this.$modal.close('addCalendar')
        },
        cancelWorkDateModalForm() {
            this.clearWorkDateTmp()
            this.$modal.close('addCalendar')
        },
        clearWorkDateTmp() {
            this.workingDateTmp.hours.length = 0
            this.workingDateTmp.hours.push({
                opening: null,
                closing: null,
            })
        },
        async removeHour(idx) {
            const hour = this.workingDates[idx]
            if (hour.id) {
                await axios.delete(`/dictionaries/work-schedules/delete/work-hour/${hour.id}`)
            }
            this.workingDates.splice(idx, 1)
        },
        prepareHours() {
            this.days.map((day) => {
                day.hours.map((hour) => {
                    this.form.workHours.push({
                        id: hour.id ?? null,
                        opening: moment(hour.opening).format('HH:mm'),
                        closing: moment(hour.closing).format('HH:mm'),
                        dayOfWeek: this.weekDays.indexOf(day.day)
                    })
                })
            })

            this.form.workHours = this.form.workHours.concat(this.workingDates)
        },
        async getSchedule() {
            if (this.scheduleId) {
                await axios.get(`/dictionaries/work-schedules/${this.scheduleId}`)
                    .then(({data}) => {
                        this.schedule = data.data
                    })
            }
        },
        async prepareForm() {
            await this.getSchedule()

            if (this.schedule?.id) {
                this.form.name = this.schedule?.name
                this.preparePresavedHours(this.schedule.work_hours)

                this.allHours.map(hour => {
                    if (hour.dayOfWeek >= 0) {
                        const key = this.days.indexOf(this.days.find(day => day.day === this.weekDays[hour.dayOfWeek]))

                        if (key >= 0) {
                            this.days[key]['hours'].push(hour)
                        } else {
                            if (this.weekDays[hour.dayOfWeek]) {
                                const hours = []
                                hours.push(hour)
                                this.days.push({
                                    day: this.weekDays[hour.dayOfWeek],
                                    is_active: true,
                                    hours
                                })
                            }
                        }
                    }

                    if (hour.specificDate) {
                        this.workingDates.push(hour)
                    }
                })

                this.weekDays.map(item => {
                    const isFound = this.days.indexOf(this.days.find(elem => elem.day === item)) >= 0

                    if (! isFound) {
                        this.days.push({
                            day: item,
                            is_active: false,
                            hours: []
                        })
                    }
                })
            } else {
                this.weekDays.map((item) => {
                    this.days.push({
                        day: item,
                        is_active: false,
                        hours: []
                    })
                })
            }

            if (this.workingDateTmpHoursLength) {
                this.addTimeIntervalIntoDateTmp()
            }

            this.filterDays()
        },
        preparePresavedHours(hours) {
            hours.map(hour => {
                const splitHour = (hour, key) => hour[key].split(':')

                const splitOpening = splitHour(hour, 'opening')
                const opening = new Date()
                opening.setHours(splitOpening[0], splitOpening[1], '00')

                const splitClosing = splitHour(hour, 'closing')
                const closing = new Date()
                closing.setHours(splitClosing[0], splitClosing[1], '00')

                if (hour.day_of_week >= 0) {
                    this.allHours.push({
                        id: hour.id,
                        opening,
                        closing,
                        dayOfWeek: hour.day_of_week,
                    })
                }

                if (hour.specific_date) {
                    this.allHours.push({
                        id: hour.id,
                        opening: moment(opening).format('HH:mm'),
                        closing: moment(closing).format('HH:mm'),
                        specificDate: hour.specific_date,
                    })
                }
            })
        },
        filterDays() {
            const daysCache = []
            this.weekDays.map((day, key) => {
                this.days.map(item => {
                    if (day === item.day) {
                        daysCache.push(item)
                    }
                })
            })
            this.days = daysCache
        }
    },
    created() {
        this.prepareForm()
    },
    computed: {
        workingDateTmpHoursLength() {
            return this.workingDateTmp.hours.length < 1
        }
    }
}
</script>
