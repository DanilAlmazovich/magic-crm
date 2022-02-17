<template>
    <form @submit.prevent="submitForm()" @input="form.clearErrors($event.target.id)">
        <div>
            <div class="flex flex-wrap">
                <div class="w-1/4 pr-2 pb-8 relative">
                    <div class="flex flex-col">
                        <label class="font-medium text-lg mb-2">Данные партнера</label>
                        <input class="px-2 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                            type="text"
                            placeholder="ФИО"
                            v-model="form.full_name"
                            id="full_name"
                            :disabled="isView"
                        >
                    </div>
                    <span v-if="form.errors.full_name" class="absolute bottom-0 left-0 leading-none text-red-400 text-sm">{{ errors.full_name }}</span>
                </div>
                <div class="w-1/4 flex items-end px-1 pb-8 relative">
                    <div class="flex flex-col w-full">
                        <the-mask class="px-3 py-2 focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                mask="+###############"
                                placeholder="+"
                                v-model="form.phone"
                                id="phone"
                                :disabled="isView"
                        />
                        <span v-if="form.errors.phone" class="absolute bottom-0 left-0 leading-none text-red-400 text-sm px-1">{{ errors.phone }}</span>
                    </div>
                </div>
                <div class="w-1/4 flex items-end pl-2 pb-8 relative">
                    <div class="flex flex-col w-full">
                        <input class="px-2 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                            type="email"
                            placeholder="E-mail"
                            v-model="form.email"
                            id="email"
                            :disabled="isView"
                        >
                        <span v-if="form.errors.email" class="absolute bottom-0 left-0 leading-none text-red-400 text-sm pl-2">{{ errors.email }}</span>
                    </div>
                </div>
                <div class="w-1/4 pl-10 pb-8 relative">
                    <div class="flex flex-col">
                        <label class="font-medium text-lg mb-2">Название заведения</label>
                        <input class="px-2 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                            type="text"
                            placeholder="Ввод"
                            v-model="form.name"
                            id="name"
                            :disabled="isView"
                        >
                        <span v-if="form.errors.name" class="absolute bottom-0 left-0 leading-none pl-10 text-red-400 text-sm">{{ errors.name }}</span>
                    </div>
                </div>
            </div>
            <div>
                <div class="pr-2 flex">
                    <div class="w-4/12 flex flex-col mr-5 pb-6 relative">
                        <label class="font-medium text-lg mb-2">Партнерское вознаграждение</label>
                        <input class="w-56 px-2 py-2 focus:border-gray-150 rounded-lg border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                            type="number"
                            placeholder="В %"
                            v-model="form.remuneration"
                            id="remuneration"
                            :disabled="isView"
                            min="0"
                            max="100"
                        >
                        <span v-if="form.errors['remuneration']" class="absolute bottom-0 left-0 leading-none text-red-400 text-sm">{{ errors.remuneration }}</span>
                    </div>
                    <div class="w-3/12 relative flex flex-col pr-6 pb-6">
                        <span class="font-medium text-lg mb-2">Тип</span>
                        <multiselect v-model="form.type"
                                    class="border-2 border-gray-150 rounded-lg"
                                    placeholder="Тип"
                                    track-by="value"
                                    label="label"
                                    :options="types">
                        </multiselect>
                        <span v-if="form.errors.type" class="absolute bottom-0 left-0 leading-none text-red-400 text-sm">{{ errors.type }}</span>
                    </div>
                </div>
            </div>
            <div>
                <label class="font-medium text-lg mb-2">Правовые документы</label>
                <div class="flex">
                    <div class="file-container flex flex-wrap w-auto">
                        <div v-for="(item, index) in documentation" class="flex items-center mb-3 mr-3" >
                            <icon name="file" class="h-auto w-4 h-4 fill-current text-orange-400 mr-2"></icon>
                            <span class="text-base text-orange-400 mr-2">{{ item.name}}</span>
                            <button type="button" class="w-4 h-4" @click="removeDocument(index)">
                                <icon name="remove" class="h-auto fill-current mr-2"></icon>
                            </button>
                        </div>
                    </div>
                    <div class="w-6/12 pt-1">
                        <div class="flex items-center mb-3">
                            <icon name="add" class="h-auto w-4 h-4 mr-4 fill-current text-orange-400 mr-2"></icon>
                            <label for="document-input" class="text-orange-400 cursor-pointer">Добавить файл</label>
                            <input type="file"
                                class="hidden"
                                id="document-input"
                                accept=".xlsx, .xls, .doc, .docx,.ppt, .pptx, .txt, .pdf, .jpg, .jpeg, .tiff, .bmp"
                                multiple="multiple"
                                @change="onFilePicked"/>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="mb-3">
                    <span class="text-lg font-medium">Филиалы заведений</span>
                </div>
                <div ref="branches" class="branchesContainer">
                    <branch-data-form
                        v-for="(branch, index) in form.branches"
                        :branch="branch"
                        :key="index"
                        :schemes="schemes"
                        :is-view="isView"
                        :number="index"
                        id="branches"
                    >
                    </branch-data-form>
                </div>
            </div>
            <div class="pt-10 pb-6 flex justify-between">
                <div class="flex items-center">
                    <button type="button"
                            @click="addBranch"
                            :disabled="isView"
                            :class="`px-4 py-3 rounded-lg border-2 border-orange-400 text-orange-400 mr-4 flex items-center ${isView ? 'cursor-not-allowed' : ''}`">
                        <span class="w-3 h-3 transform rotate-45 mr-5">
                            <icon name="cross" class="fill-current text-orange-400"></icon>
                        </span>
                        <span  class="leading-none text-base">Добавить филиал</span>
                    </button>
                    <span v-if="form.errors['branches']" class="text-red-400 text-base font-medium">{{ errors.branches }}</span>
                </div>
                <button type="submit" :disabled="isView || form.processing"
                        :class="`px-6 py-3 rounded-lg bg-orange-400 text-white ${isView || form.processing ? 'cursor-not-allowed' : ''}`">Сохранить</button>
            </div>
        </div>
    </form>
</template>
<script>
import BranchDataForm from '@/Components/Partners/BranchDataForm'
import Label from '../../Jetstream/Label';

export default {
    components: {
        Label,
        BranchDataForm
    },
    props: {
        schemes: {
            type: Array,
            default: () => [],
        },
        partner: {
            type: Object,
            default: () => ({}),
        },
        errors:  Object,
        errorBags: Array,
        isView: {
            default: false,
            type: Boolean,
        },
        partnerTypes: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.partner?.name,
                email: this.partner?.email,
                full_name:  this.partner?.full_name,
                phone: this.partner?.phone,
                remuneration: this.partner?.remuneration * 100,
                type: this.partner?.type ?? null,
                branches: this.partner?.branches || [],
            }),
            documentation: [],
            photo: '-',
        }
    },
    methods: {
        async submitForm() {
            const method = this.partner.id ? 'put' : 'post';
            const url = method === 'post' ? 'partners' : `partners/${this.partner.id}`

            await this.form.transform((data) => ({
                ...data,
                type: this.form.type.value,
            }))
                [method](url, {
                onSuccess: () => {
                    this.form.reset()
                    method === 'post' ? this.$modal.close('addPartner') : this.$modal.close('partnerEditing')
                }
            })
        },
        addBranch() {
            this.form.branches.push({
                name: null,
                full_name: null,
                phone: null,
                address: null,
                longitude: 77.9765,
                latitude: 77.9765,
                free_delivery_value: null,
                id: null,
                partner_id: null,
                selectedSchemeId: null,
                selectedWorkSchedule: null,
            })
        },
        onFilePicked(event) {
            let fileList = Array.prototype.slice.call(event.target.files);
            fileList.forEach((item) => {
                this.documentation.push(item)
            })
        },
        removeDocument(index) {
            this.documentation.splice(index, 1)
        },
        getFileName (event) {
            let files = Array.prototype.slice.call(event.target.files);
            if (files.length > 0) {
                this.photo = files[0].name
            }
        },
    },
    computed: {
        types() {
            return Object.keys(this.partnerTypes).map(value => ({
                label: this.partnerTypes[value],
                value
            }))
        }
    },
    created() {
        this.form.type = this.types.find(type => type.value === this.form.type)
    }
}
</script>
<style scoped>
    .file-container {
        max-width: 80%;
    }
</style>
