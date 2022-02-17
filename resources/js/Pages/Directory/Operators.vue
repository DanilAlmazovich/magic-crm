<template>
    <directory-layout :name="name" content-width="w-10/12">
        <div class="flex justify-between pt-6 mb-4">
            <div class="pt-2">
                <h1 class="text-3xl font-semibold text-gray-900">База данных операторов</h1>
            </div>
            <button v-if="$page.props.auth.user.isAdmin"
                    type="button" class="px-4 py-3 rounded-lg border-none flex items-center"
                    @click="$modal.open('addOperator')">
                    <span class="w-3 h-3 transform rotate-45 mr-3">
                        <icon name="cross" class="fill-current text-orange-400"></icon>
                    </span>
                <span class="leading-none text-orange-400 text-base">Добавить оператора</span>
            </button>
        </div>
        <operators-table :roles="$page.props.roles"
                         :operators="$page.props.operators"
                         @edit="setEditForm"
                         @delete="deleteOperator">
        </operators-table>
        <div slot="modals" class="modal directory-modal">
            <modal name="addOperator" close-text>
                <div class="-mt-6">
                    <h2 class="text-xl text-gray-900 font-medium mb-5">Добавить оператора</h2>
                    <form @input="createForm.clearErrors($event.target.id)" @submit.prevent="storeForm">
                        <div class="flex mb-3">
                            <image-input @change="createPhoto"></image-input>
                            <div class="w-1/4 pl-4">
                                <div class="flex mb-10">
                                    <div class="w-full flex flex-col">
                                        <div class="flex flex-col">
                                            <label class="font-medium text-lg mb-2">ФИО</label>
                                            <input v-model="createForm.name"
                                                   id="name"
                                                   class="px-2 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                                   :class="createForm.errors['name'] ? ['border-red-600'] : []"
                                                   type="text" placeholder="Ввод">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="w-full mr-1 flex flex-col">
                                        <span class="font-medium text-lg mb-2">Тип оператора</span>
                                        <multiselect v-model="selectedRole"
                                                     id="role"
                                                     class="border-2 border-gray-150 rounded-lg"
                                                     :class="createForm.errors['role'] ? ['border-red-600'] : []"
                                                     placeholder="Тип"
                                                     track-by="value"
                                                     label="label"
                                                     @input="createForm.clearErrors('role')"
                                                     :options="roleOptions">
                                        </multiselect>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-1/4 flex flex-col pr-4">
                                <div class="flex flex-col">
                                    <label class="font-medium text-lg mb-2">Логин</label>
                                    <input v-model="createForm.email"
                                           id="email"
                                           class="px-2 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                           :class="createForm.errors['email'] ? ['border-red-600'] : []"
                                           type="text" placeholder="Ввод">
                                </div>
                            </div>
                            <div class="w-1/4 pl-4">
                                <div class="flex flex-col">
                                    <label class="font-medium text-lg mb-2">Пароль</label>
                                    <input v-model="createForm.password"
                                           id="password"
                                           class="px-2 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                           :class="createForm.errors['role'] ? ['border-red-600'] : []"
                                           type="password" placeholder="Ввод">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-6">
                            <button type="submit"
                                    class="text-white text-base px-10 py-3 rounded-lg bg-orange-400"
                            >Сохранить</button>
                        </div>
                    </form>
                </div>
            </modal>
            <modal name="editOperator" close-text>
                <div class="-mt-6">
                    <h2 class="text-xl text-gray-900 font-medium mb-5">Редактировать данные</h2>
                    <form @input="editForm.clearErrors($event.target.id.split('-')[1])" @submit.prevent="updateForm">
                        <div class="flex mb-3">
                            <image-input :src="photoSrc" @change="editPhoto"></image-input>
                            <div class="w-1/4 pl-4">
                                <div class="flex mb-10">
                                    <div class="w-full flex flex-col">
                                        <div class="flex flex-col">
                                            <label class="font-medium text-lg mb-2">ФИО</label>
                                            <input v-model="editForm.name"
                                                   id="edit-name"
                                                   class="px-2 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                                   :class="editForm.errors['name'] ? ['border-red-600'] : []"
                                                   type="text" placeholder="Ввод">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="w-full mr-1 flex flex-col">
                                        <span class="font-medium text-lg mb-2">Тип оператора</span>
                                        <multiselect v-model="selectedRole"
                                                     id="edit-role"
                                                     class="border-2 border-gray-150 rounded-lg"
                                                     :class="editForm.errors['role'] ? ['border-red-600'] : []"
                                                     placeholder="Тип"
                                                     track-by="value"
                                                     label="label"
                                                     @input="editForm.clearErrors('role')"
                                                     :options="roleOptions">
                                        </multiselect>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-1/4 flex flex-col pr-4">
                                <div class="flex flex-col">
                                    <label class="font-medium text-lg mb-2">Логин</label>
                                    <input v-model="editForm.email"
                                           id="edit-email"
                                           class="px-2 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                           :class="editForm.errors['email'] ? ['border-red-600'] : []"
                                           type="text" placeholder="Ввод">
                                </div>
                            </div>
                            <div class="w-1/4 pl-4">
                                <div class="flex flex-col">
                                    <label class="font-medium text-lg mb-2">Пароль</label>
                                    <input v-model="editForm.password"
                                           id="edit-password"
                                           class="px-2 py-2 rounded-lg focus:border-gray-150 border-2 border-gray-150 placeholder-gray-200 text-gray-900"
                                           :class="editForm.errors['role'] ? ['border-red-600'] : []"
                                           type="password" placeholder="Ввод">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-6">
                            <button type="submit"
                                    class="text-white text-base px-10 py-3 rounded-lg bg-orange-400"
                            >Сохранить</button>
                        </div>
                    </form>
                </div>
            </modal>
        </div>
    </directory-layout>
</template>
<script>
import DirectoryLayout from '@/Layouts/DirectoryLayout'
import OperatorsTable from '@/Components/Directory/OperatorsTable'
import ImageInput from '@/Components/ImageInput'

export default {
    components: {
        DirectoryLayout,
        OperatorsTable,
        ImageInput
    },
    props: ['roles', 'operators'],
    data() {
        return {
            name: 'directory',
            photoSrc: null,
            selectedRole: null,
            createForm: this.$inertia.form({
                photo: null,
                name: null,
                email: null,
                password: null,
                role: null
            }),
            editForm: this.$inertia.form({
                _method: 'put',
                id: null,
                photo: null,
                name: null,
                email: null,
                password: null,
                role: null
            })
        }
    },
    computed: {
        roleOptions() {
            return Object.entries(this.roles).map(([value, label]) => ({value, label}))
        }
    },
    methods: {
        createPhoto(file) {
            this.createForm.photo = file
        },
        editPhoto(file) {
            this.editForm.photo = file
        },
        storeForm() {
            this.createForm.role = this.selectedRole ? this.selectedRole.value : null
            this.createForm.post('/dictionaries/operators', {
                onSuccess: () => {
                    this.$modal.close('addOperator')
                    this.selectedRole = null
                    this.createForm.reset()
                }
            })
        },
        setEditForm(operator) {
            this.photoSrc = operator.profile_photo_url
            this.selectedRole = this.roleOptions.find(option => {
                return option.value == operator.role
            })

            this.editForm.id = operator.id
            this.editForm.name = operator.name
            this.editForm.email = operator.email
            this.editForm.role = operator.role

            this.$modal.open('editOperator')
        },
        updateForm() {
            this.editForm.role = this.selectedRole ? this.selectedRole.value : null
            this.editForm.post(`/dictionaries/operators/${this.editForm.id}`, {
                onSuccess: () => {
                    this.$modal.close('editOperator')
                    this.selectedRole = null
                    this.editForm.reset()
                }
            })
        },
        deleteOperator(operator) {
            this.$inertia.delete(`/dictionaries/operators/${operator.id}`, {
                onBefore: () => confirm('Вы уверены, что хотите удалить этого оператора?'),
            })
        }
    },
}
</script>
