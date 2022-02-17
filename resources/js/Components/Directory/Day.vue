<template>
    <div class="pt-3 flex justify-between border-b-2 border-gray-150">
        <div class="flex pt-3">
            <div>
                <checkbox class="mr-10" @change="handleDayCheckboxClick" :checked="day.is_active" v-model="day.is_active"></checkbox>
            </div>
            <span class="text-lg text-black font-medium">{{ day.day }}</span>
        </div>
        <div>
            <div v-if="!hoursLength" class="flex items-center justify-between">
                <div class="flex pb-3 pr-8 text-left">
                    <div>Не выбрано</div>
                </div>
                <div class="flex items-center mb-3">
                    <button @click.prevent="addHour" type="button" class="border border-solid border-orange-400 p-3 rounded-md mr-12 flex items-center ml-3">
                        <icon name="plus" class="w-4 h-4 block fill-current text-orange-400"/>
                    </button>
                </div>
            </div>
            <div v-else class="flex items-center justify-between" v-for="(hour, idx) in day.hours">
                <div class="flex items-center pb-3 pr-8">
                    <date-picker v-model="hour.opening"
                                 format="HH:mm"
                                 type="time"
                                 class="time-picker border-gray-150 border-2 text-gray-150 px-3 py-2 rounded-lg"
                                 placeholder="00:00">
                    </date-picker>
                    <span class="px-3">-</span>
                    <date-picker v-model="hour.closing"
                                 format="HH:mm"
                                 type="time"
                                 class="time-picker border-gray-150 border-2 text-gray-150 px-3 py-2 rounded-lg"
                                 placeholder="00:00">
                    </date-picker>
                </div>
                <div class="flex items-center mb-3">
                    <button v-if="!idx" @click.prevent="addHour" type="button" class="border border-solid border-orange-400 p-3 rounded-md mr-12 flex items-center ml-3">
                        <icon name="plus" class="w-4 h-4 block fill-current text-orange-400"/>
                    </button>
                    <button type="button" @click.prevent="removeHour(idx)" class="rounded-full border-red-400 border-2 border-solid flex items-center w-4 h-4 justify-center mr-4">
                        <icon name="close" class="w-2 h-2 fill-current text-red-400 inline-block"/>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker'
import Calendar from 'v-calendar/lib/components/date-picker.umd'
import Checkbox from '@/Jetstream/Checkbox'

export default {
    name: 'Day',
    props: {
        day: {
            type: Object,
            default: () => ({})
        },
        weekDays: Array,
    },
    components: {
        Calendar,
        Checkbox,
        DatePicker,
    },
    methods: {
        addHour() {
            this.day.hours.push({
                opening: null,
                closing: null,
            })

            if (this.day.hours.length) {
                this.day.is_active = true
            }
        },
        async removeHour(idx) {
            const hour = this.day.hours[idx]
            if (hour.id) {
                await axios.delete(`/dictionaries/work-schedules/delete/work-hour/${hour.id}`)
            }
            this.day.hours.splice(idx, 1)

            if (! this.day.hours.length) {
                this.day.is_active = false
            }
        },
        async handleDayCheckboxClick() {
            if (this.day.is_active && ! this.hoursLength) {
                this.addHour()
            }

            if (! this.day.is_active && this.hoursLength) {
                let ids = []
                this.day.hours.map(hour => {
                    ids.push(hour.id)
                })
                ids = ids.sort()

                await axios.put('/dictionaries/work-schedules/multiple/delete/work-hours', { ids })
                    .then(() => this.day.hours = [])
            }
        }
    },
    computed: {
        hoursLength() {
            return this.day.hours.length
        }
    }
}
</script>

<style scoped>

</style>
