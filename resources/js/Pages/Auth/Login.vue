<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo/>
        </template>

        <jet-validation-errors class="mb-4"/>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <jet-input id="email" type="text" class="mt-1 block w-full" v-model="form.email" required autofocus
                           placeholder="Логин"/>
            </div>

            <div class="mt-4 relative">
                <jet-input id="password" :type="showPassword ? 'text' : 'password'" class="mt-1 block w-full pr-8" v-model="form.password" required
                           autocomplete="current-password" placeholder="Пароль"/>
                <button type="button" class="absolute right-0 top-0 px-2 flex items-center h-full" @click.prevent="showPasswordHandle">
                    <img src="/images/eye.svg" alt="">
                </button>
            </div>

            <div class="flex items-center justify-end mt-4">
                <jet-button class="mt-1 text-center w-full bg-orange-400 py-2 flex justify-center hover:bg-orange-400 text-base normal-case font-normal" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                    Войти
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                }),
                showPassword: false
            }
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ...data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => {
                            this.form.reset('password')
                            this.$firebase.requestPushPermission()
                        }
                    })
            },
            showPasswordHandle() {
                this.showPassword = ! this.showPassword
            }
        }
    }
</script>
