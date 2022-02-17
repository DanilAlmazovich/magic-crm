<template>
    <div class="-mt-6">
        <h2 class="text-xl text-gray-900 font-medium mb-5">Добавить курьера</h2>
        <form @submit.prevent="submitForm">
            <div class="flex mb-4">
                <div class="w-1/3 pr-4 flex flex-col">
                    <label for="name" class="text-gray-900 text-xl font-medium mb-3">ФИО</label>
                    <input id="name" type="text"
                           placeholder="ФИО"
                           required
                           v-model="form.full_name"
                           class="px-3 py-2 rounded-lg focus:ring-0 focus:border-gray-150 focus:outline-none
                                        border-2 border-gray-150 placeholder-gray-150 text-gray-900">
                    <span v-if="form.hasErrors && form.errors.full_name" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">{{ form.errors.full_name }}</span>
                </div>
                <div class="w-1/3 flex flex-col px-2">
                    <div class="flex flex-col w-full">
                        <span class="text-gray-900 text-xl font-medium mb-3">Телефон</span>
                        <the-mask class="px-3 py-2 focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                  mask="+###############"
                                  v-model="form.phone"
                                  placeholder="+"/>
                        <span v-if="form.hasErrors && form.errors.phone" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">{{ form.errors.phone }}</span>
                    </div>
                </div>
                <div class="w-1/3 pl-4 flex flex-col">
                    <span class="text-gray-900 text-xl font-medium mb-3">Статус</span>
                    <multiselect v-model="form.is_active"
                                 class="border-2 border-gray-150 rounded-lg"
                                 placeholder="Статус"
                                 track-by="value"
                                 label="label"
                                 :allow-empty="false"
                                 :options="activeStatuses">
                    </multiselect>
                    <span v-if="form.hasErrors && form.errors.is_active" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">{{ form.errors.is_active }}</span>
                </div>
            </div>
            <div class="flex mb-4">
                <div class="w-1/3 pr-4 flex flex-col">
                    <span class="text-gray-900 text-xl font-medium mb-3">Тип курьера</span>
                    <div>
                        <multiselect v-model="form.type"
                                     class="border-2 border-gray-150 rounded-lg w-full"
                                     placeholder="Тип курьера"
                                     track-by="value"
                                     label="label"
                                     :allow-empty="false"
                                     :options="courierTypes">
                        </multiselect>
                    </div>
                    <span v-if="form.hasErrors && form.errors.type" class="bottom-0 left-0 mt-0.5 leading-none pl-10 text-red-400 text-sm">{{ form.errors.type }}</span>
                </div>
                <div class="w-1/3 px-2 flex flex-col">
                    <label for="car"
                           class="text-gray-900 text-xl font-medium mb-3">Тип машины</label>
                    <input id="car"
                           type="text"
                           v-model="form.transport.model"
                           placeholder="Марка машины"
                           :required="type"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 focus:ring-0 focus:border-gray-150
                                       focus:outline-none placeholder-gray-150 text-gray-900">
                    <span v-if="form.hasErrors && form.errors.transport && form.errors.transport.model" class="bottom-0 left-0 mt-0.5 leading-none pl-10 text-red-400 text-sm">
                        {{ form.errors.transport.model }}
                    </span>
                </div>
                <div class="w-1/3 pl-4 flex flex-col">
                    <label for="state-number"
                           class="text-gray-900 text-xl font-medium mb-3">Гос номер</label>
                    <input id="state-number"
                           v-model="form.transport.license_plate"
                           type="text"
                           :required="type"
                           placeholder="Гос номер"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                    <span v-if="form.hasErrors && form.errors.transport && form.errors.transport.license_plate" class="bottom-0 left-0 mt-0.5 leading-none pl-10 text-red-400 text-sm">
                        {{ errors.transport.license_plate }}
                    </span>
                </div>
            </div>
            <div class="flex mb-4">
                <div class="w-1/3 pr-4 relative flex flex-col">
                    <span class="text-gray-900 text-xl font-medium mb-3">Статус взноса</span>
                    <multiselect v-model="form.is_contributed"
                                 class="border-2 border-gray-150 rounded-lg"
                                 placeholder="Статус"
                                 track-by="value"
                                 label="label"
                                 :allow-empty="false"
                                 :options="contributionStatuses">
                    </multiselect>
                    <span v-if="form.hasErrors && form.errors.is_contributed" class="absolute bottom-0 left-0 leading-none pl-10 text-red-400 text-sm">
                        {{ form.errors.is_contributed }}
                    </span>
                </div>
                <div class="w-1/3 px-2 flex flex-col">
                    <div class="flex flex-col w-full">
                        <label for="car-year"
                               class="text-gray-900 text-xl font-medium mb-3">Год машины</label>
                        <v-date-picker v-model="form.transport.year_issue"
                                       mode="date" is-expanded
                                       ref="datepicker" :masks="masks">
                            <template v-slot="{ inputValue, togglePopover }">
                                <div class="flex flex-col w-full relative">
                                    <input class="pl-3 pr-12 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                        @click="togglePopover"
                                        type="text"
                                        id="car-year"
                                        :value="inputValue"
                                        placeholder="Не указано">
                                    <button class="w-12 h-full absolute top-0 right-0 flex justify-center items-center" @click="togglePopover">
                                        <icon name="calendar" class="h-5 w-5 fill-current text-orange-400"></icon>
                                    </button>
                                </div>
                            </template>
                        </v-date-picker>
                    </div>
                </div>
                <div class="w-1/3 pl-4 flex flex-col">
                    <label for="car-color"
                           class="text-gray-900 text-xl font-medium mb-3">Цвет машины</label>
                    <input id="car-color"
                           type="text"
                           placeholder="Ввод"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 placeholder-gray-150 focus:ring-0
                                        focus:border-gray-150 focus:outline-none text-gray-900">
                    <span class="bottom-0 left-0 mt-0.5 leading-none pl-10 text-red-400 text-sm"></span>
                </div>
            </div>
            <div class="flex mb-4">
                <div class="w-1/3 pr-4 flex flex-col">
                    <span class="text-gray-900 text-xl font-medium mb-3">График работы</span>
                    <multiselect
                                 class="border-2 border-gray-150 rounded-lg"
                                 placeholder="Статус"
                                 track-by="id"
                                 label="name"
                                 v-model="selectedSchedule"
                                 @select="scheduleSelected"
                                 :allow-empty="false"
                                 :options="schedules">
                    </multiselect>
                </div>
                <div class="w-1/3 px-2">
                    <div class="mb-3">
                        <span class="text-gray-900 text-xl font-medium">Тип расчета</span>
                    </div>
                    <div class="flex items-center rounded-lg border-2 border-gray-150 px-3 py-2 relative input-radio">
                        <input type="radio"
                               id="salary"
                               v-model="form.calculation_type"
                               :checked="form.calculation_type === 'salary' ? 'checked' : ''"
                               @change="changeCalculationType()"
                               value="salary"
                               class="hidden" />
                        <label for="salary" class="pr-5 text-gray-500 cursor-pointer">Зарплатный</label>
                    </div>
                </div>
                <div class="w-1/3 relative pl-4 flex items-end">
                    <div class="flex items-center rounded-lg w-full border-2 border-gray-150 px-3 py-2 relative input-radio">
                        <input type="radio"
                               id="dynamic"
                               v-model="form.calculation_type"
                               :checked="form.calculation_type === 'dynamic' ? 'checked' : ''"
                               @change="changeCalculationType('dynamic')"
                               value="dynamic"
                               name="calculation_type"
                               class="hidden" />
                        <label for="dynamic" class="pr-5 text-gray-500 cursor-pointer">Динамический</label>
                    </div>
                </div>
            </div>
            <div class="flex justify-between mb-4">
                <div class="w-1/3 pr-4 flex flex-col">
                    <span class="text-gray-900 text-xl font-medium mb-3">Спец одежда</span>
                    <multiselect v-model="form.is_set_outfit_kit"
                                 class="border-2 border-gray-150 rounded-lg"
                                 placeholder="Статус"
                                 track-by="value"
                                 label="label"
                                 :allow-empty="false"
                                 :options="workWearIssueStatuses">
                    </multiselect>
                    <span v-if="form.hasErrors && form.errors.is_set_outfit_kit" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">
                        {{ form.errors.is_set_outfit_kit }}
                    </span>
                </div>
                <div class="w-1/3 px-2 flex flex-col">
                    <span class="text-gray-900 text-xl font-medium mb-3">Схема расчета</span>
                    <multiselect v-model="calculationType"
                                 class="border-2 border-gray-150 rounded-lg"
                                 placeholder="Статус"
                                 track-by="id"
                                 label="title"
                                 :allow-empty="false"
                                 @input="changedCalculationType"
                                 :options="CalculationTypes">
                    </multiselect>
                    <span v-if="form.hasErrors && form.errors.constant_courier_payment_scheme_id" class="mt-1 bottom-0 left-0 leading-none text-red-400 text-sm">
                        {{ form.errors.constant_courier_payment_scheme_id }}
                    </span>
                    <span v-if="form.hasErrors && form.errors.dynamic_commission_calculation_id" class="mt-1 bottom-0 left-0 leading-none text-red-400 text-sm">
                        {{ form.errors.dynamic_commission_calculation_id }}
                    </span>
                </div>
                <div class="w-1/3 relative pl-4 flex flex-col">
                    <label for="passport-id"
                           class="text-gray-900 text-xl font-medium mb-3">Номер паспорта</label>
                    <input id="passport-id"
                           type="text"
                           required
                           v-model="form.passport.number"
                           placeholder="Номер папорта"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 focus:ring-0 focus:border-gray-150
                                       focus:outline-none placeholder-gray-150 text-gray-900">
                    <!--                    <span v-if="form.hasErrors && form.errors.passport.number" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">{{ form.errors.passport.number }}</span>-->
                </div>
            </div>
            <div class="flex mb-4">
                <div class="w-1/3 pr-4 flex flex-col">
                    <div class="flex relative flex-col w-full">
                        <label for="date-of-issue"
                               class="text-gray-900 text-xl font-medium mb-3">Дата выдачи паспорта</label>
                        <v-date-picker v-model="form.passport.issued_at"
                                       mode="date" is-expanded
                                       ref="datepicker" :masks="masks">
                            <template v-slot="{ inputValue, togglePopover }">
                                <div class="flex flex-col w-full relative">
                                    <input class="pl-3 pr-12 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                        @click="togglePopover"
                                        type="text"
                                        id="date-of-issue"
                                        :value="inputValue"
                                        placeholder="Дата выдачи">
                                    <button class="w-12 h-full absolute top-0 right-0 flex justify-center items-center" @click="togglePopover">
                                        <icon name="calendar" class="h-5 w-5 fill-current text-orange-400"></icon>
                                    </button>
                                </div>
                            </template>
                        </v-date-picker>
                        <span v-if="form.errors.passport && form.errors.passport.issued_at" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">{{ form.errors.passport['issued_at'] }}</span>
                    </div>
                </div>
                <div class="w-1/3 px-2 flex flex-col">
                    <div class="flex relative flex-col w-full">
                        <label for="passport-term"
                               class="text-gray-900 text-xl font-medium mb-3">Срок паспорта</label>
                        <v-date-picker v-model="form.passport.expires_at"
                                       mode="date" is-expanded
                                       ref="datepicker" :masks="masks">
                            <template v-slot="{ inputValue, togglePopover }">
                                <div class="flex flex-col w-full relative">
                                    <input class="pl-3 pr-12 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                        @click="togglePopover"
                                        type="text"
                                        id="passport-term"
                                        :value="inputValue"
                                        placeholder="Срок паспорта">
                                    <button class="w-12 h-full absolute top-0 right-0 flex justify-center items-center" @click="togglePopover">
                                        <icon name="calendar" class="h-5 w-5 fill-current text-orange-400"></icon>
                                    </button>
                                </div>
                            </template>
                        </v-date-picker>
                        <span v-if="form.errors.passport && form.errors.passport.expires_at" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">{{ form.errors.passport['expires_at'] }}</span>
                    </div>
                </div>
                <div class="w-1/3 pl-4 relative flex flex-col">
                    <label for="authority"
                           class="text-gray-900 text-xl font-medium mb-3">Кем выдан</label>
                    <input type="text"
                           id="authority"
                           required
                           v-model="form.passport.authority"
                           placeholder="Ввод"
                           class="px-3 py-2 rounded-lg border-2 border-gray-150 focus:ring-0 focus:border-gray-150
                                       focus:outline-none placeholder-gray-150 text-gray-900">
                    <span v-if="form.errors.passport && form.errors.passport.authority" class="bottom-0 left-0 mt-0.5 leading-none text-red-400 text-sm">{{ form.errors.passport.authority }}</span>
                </div>
            </div>
            <div class="flex items-end justify-between pb-10">
                <div class="w-2/3 flex justify-between pl-2">
                    <div class="w-6/12">
                        <span class="inline-block text-gray-900 text-xl font-medium mb-3">Паспорт</span>
                        <div class="flex items-center pt-2 mb-3">
                            <icon name="add" class="h-auto w-4 h-4 mr-4 fill-current text-orange-400 mr-2"></icon>
                            <label for="file" class="text-orange-400 cursor-pointer">Выбрать файл</label>
                            <input type="file"
                                   class="hidden"
                                   id="file"
                                   multiple="multiple"
                                   @change="onFilePicked"/>
                        </div>
                    </div>
                    <div class="flex flex-wrap w-8/12 pt-3">
                        <div v-for="(item, index) in images" class="flex items-center mb-3 mr-3 max-w-full" >
                            <icon name="file" class="h-auto w-4 h-4 fill-current text-orange-400 mr-2"></icon>
                            <span class="text-base text-orange-400 mr-2 whitespace-nowrap overflow-hidden overflow-ellipsis w-10/12">{{ item.name }}</span>
                            <button type="button" class="w-4 h-4" @click="remove(index)">
                                <icon name="remove" class="h-full w-full fill-current mr-2"></icon>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="submit" :disabled="form.processing"
                        class="text-white text-base px-10 py-4 rounded-lg bg-orange-400">Сохранить</button>
            </div>
        </form>
    </div>
</template>

<script>
import VDatePicker from 'v-calendar/lib/components/date-picker.umd'
import moment from 'moment'

export default {
    name: 'CourierEditForm',
    components: {
        VDatePicker,
    },
    props: {
        transports: {
            default: () => ({}),
            type: Object,
        },
        courier: {
            default: () => ({}),
            type: Object,
        },
        calculationTypes: {
            default: () => ({}),
            type: Object,
        },
        dynamicCalculationTypes: Array,
        constantCourierPayments: Array,
    },
    data() {
        return {
            masks: {
                input: 'YYYY-MM-DD',
            },
            workWearIssueStatuses: [
                {
                    label: 'Выдано',
                    value: true,
                },
                {
                    label: 'Не выдано',
                    value: false,
                },
            ],
            activeStatuses: [
                {
                    label: 'Активен',
                    value: true,
                },
                {
                    label: 'Не активен',
                    value: false,
                },
            ],
            contributionStatuses: [
                {
                    label: 'Оплачен',
                    value: true,
                },
                {
                    label: 'Не оплачен',
                    value: false,
                },
            ],
            images: [],
            CalculationTypes: [],
            calculationType: null,
            isTransportRequired: false,
            schedules: [],
            selectedSchedule: null,
            form: this.$inertia.form({
                full_name: this.courier?.full_name ?? null,
                address: this.courier?.address || 'address',
                birthday: this.courier?.birthday || '2016-08-09',
                calculation_type: this.courier?.calculation_type || 'salary',
                commission: this.courier?.commission ?? 10,
                is_contributed: this.courier.is_contributed ?? null,
                is_set_outfit_kit: this.courier.is_set_outfit_kit ?? null,
                is_active: this.courier?.is_active || true,
                latitude: this.courier?.latitude || 78.003,
                longitude: this.courier?.longitude || 75.732,
                phone: this.courier?.phone,
                type: this.courier?.type ?? null,
                passport: {
                    number: this.courier?.passport?.number ?? null,
                    issued_at: this.courier?.passport?.issued_at ?? null,
                    expires_at: this.courier?.passport?.expires_at ?? null,
                    authority: this.courier?.passport?.authority ?? null,
                },
                transport: {
                    model: this.courier.transport?.model,
                    year_issue: '2020-06-12',
                    color: 'red',
                    license_plate: this.courier.transport?.license_plate,
                },
                constant_courier_payment_scheme_id: this.courier.constant_courier_payment_scheme_id,
                dynamic_commission_calculation_id:  this.courier.dynamic_commission_calculation_id,
                selectedScheduleId: null,
            })
        }
    },
    methods: {
        onFilePicked(event) {
            let fileList = Array.prototype.slice.call(event.target.files);
            fileList.forEach((item) => {
                this.images.push(item)
            })
        },
        remove(index) {
            this.images.splice(index, 1)
        },
        submitForm() {
            const method = this.courier.id ? 'put' : 'post'
            const url = method === 'post' ? 'couriers' : `/couriers/${this.courier.id}`

            const issued_at = moment(this.form.passport.issued_at).format('YYYY-MM-DD')
            const expires_at = moment(this.form.passport.expires_at).format('YYYY-MM-DD')

            this.form.transform((data) => ({
                ...data,
                is_active: this.form.is_active.value,
                is_set_outfit_kit: this.form.is_set_outfit_kit.value,
                is_contributed: this.form.is_contributed.value,
                type: this.form.type ? this.form.type.value : null,
                passport: {
                    ...this.form.passport,
                    issued_at,
                    expires_at,
                },
                phone: this.form.phone,
            }))

            this.form[method](url, {
                onSuccess: () => {
                    this.form.reset()
                    method === 'post' ? this.$modal.close('addCourier') : this.$modal.close('editCourierForm')
                }
            })
        },
        changedCalculationType(val, id) {
            this.form.calculation_type === 'salary' ?
                this.form.constant_courier_payment_scheme_id = val.id :
                this.form.dynamic_commission_calculation_id = val.id
        },
        setDefaultData() {
            this.form.type = this.courierTypes.find(type => type.value === this.form.type)
            this.form.is_active = this.activeStatuses.find(status => status.value === this.form.is_active)
            this.form.is_contributed = this.contributionStatuses.find(status => status.value === this.form.is_contributed)
            this.form.is_set_outfit_kit = this.workWearIssueStatuses.find(status => status.value === this.form.is_set_outfit_kit)


            if (this.courier.id) {
                if (this.form.calculation_type === 'salary') {
                    this.setCalculationTypes()
                    this.filterCalculationType(this.constantCourierPayments, 'constant_courier_payment_scheme_id')
                }

                if (this.form.calculation_type === 'dynamic') {
                    this.setCalculationTypes(false)
                    this.filterCalculationType(this.dynamicCalculationTypes, 'dynamic_commission_calculation_id')
                }

                if (this.courier?.workSchedule) {
                    this.selectedSchedule = this.courier?.workSchedule
                }
            } else {
                this.setCalculationTypes()
            }
        },
        changeCalculationType(type = 'salary') {
            type === 'salary' ? this.setCalculationTypes() : this.setCalculationTypes(false)
            this.calculationType = null
            this.form.constant_courier_payment_scheme_id = null
            this.form.dynamic_commission_calculation_id = null
        },
        filterCalculationType(types, type) {
            this.calculationType = types.find(payment => payment.id === this.courier[type])
        },
        setCalculationTypes(isConstant = true) {
            this.CalculationTypes = isConstant ? this.constantCourierPayments : this.dynamicCalculationTypes
        },
        async getSchedules() {
            await axios.get('/dictionaries/work-schedules').then(({data}) => {
                this.schedules = data.data
            })
        },
        scheduleSelected(val) {
            this.form.selectedScheduleId = val.id
        },
        setActiveWorkSchedule() {
            if (this.courier?.work_schedule) {
                this.selectedSchedule = this.courier.work_schedule
            }
        },
    },
    computed: {
        courierTypes() {
            return Object.keys(this.transports).map(value => ({
                label: this.transports[value],
                value
            }))
        },
        type() {
            return this.form.type && this.form.type.value === 'car'
        }
    },
    created() {
        this.setDefaultData()
        this.setActiveWorkSchedule()
        this.getSchedules()
    },
}
</script>
<style scoped>
    .input-radio input ~ label:after {
        position: absolute;
        content: '';
        right: 12px;
        border-radius: 100%;
        border: 2px solid #FFBE17;
        top: calc(50% - 12px);
        width: 24px;
        height: 24px;
    }
    .input-radio input:checked ~ label:before {
        position: absolute;
        content: "";
        border-radius: 100%;
        background: #FFBE17;
        width: 14px;
        height: 14px;
        top: calc(50% - 7px);
        right: 17px;
    }
</style>
