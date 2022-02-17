<template>
    <nav class="sidebar relative z-30 bg-gray-125"  :class="{'close': !sidebarOpen}">
        <div class="max-h-screen">
            <div class="flex flex-col justify-between w-56 pr-4 pl-6 py-4 h-full box">
                <div>
                    <div class="mb-12 -ml-2">
                        <inertia-link href="/" class="w-16 h-auto block">
                            <icon name="logo"></icon>
                        </inertia-link>
                    </div>
                    <div class="flex items-center mb-5">
                        <button class="h-4 pl-1 transform transition duration-300 ease-in-out" :class="{'rotate-180 ': sidebarOpen}"  @click="toggle">
                            <icon name="right-arrow" class="text-yellow-400"></icon>
                        </button>
                    </div>
                    <div class="mb-12">
                        <inertia-link :href="route('orders.create.standard')" class="flex items-center h-10">
                            <icon name="plus" class="rounded-full absolute z-40 bg-yellow-400 mr-5 w-10 p-3 h-auto text-white fill-current"></icon>
                            <span class="text-gray-900 transition duration-200 ease-in-out opacity-1 ml-14 leading-[12px]"
                                  :class="{'opacity-0': !sidebarOpen}">
                            Новый заказ
                        </span>
                        </inertia-link>
                    </div>
                    <div class="mb-12">
                        <div class="mb-12">
                            <div class="border-b border-solid border-gray-200 pb-4 pl-3" :class="{'border-b-0': !sidebarOpen}">
                                <sidebar-link v-for="link in menuLinks"
                                              v-if="linkAccess(link.section)"
                                              :name="link.name"
                                              :href="link.href"
                                              :icon="link.icon"
                                              :key="link.name"
                                              :sidebarOpen="sidebarOpen">
                                </sidebar-link>
                            </div>
                            <div class="py-6 pl-3">
                                <sidebar-link v-for="link in aboutLinks"
                                              v-if="linkAccess(link.section)"
                                              :name="link.name"
                                              :href="link.href"
                                              :icon="link.icon"
                                              :key="link.name"
                                              :sidebarOpen="sidebarOpen">
                                </sidebar-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-8 pl-3">
                    <div class="mb-6 flex flex-col transition duration-300 ease-in-out opacity-1" :class="{'opacity-0': !sidebarOpen}">
                        <span class="text-gray-200 text-sm">{{ $page.props.auth.user.roleLabel }}:</span>
                        <span class="text text-gray-900 ">{{ $page.props.auth.user.name }}</span>
                    </div>
                    <div>
                        <button class="flex items-center text-gray-200 hover:text-gray-900 duration-200 transition"
                                @click="$modal.question().then(() => logout())">
                            <icon name="exit" class="w-5 h-auto mr-8 fill-current"></icon>
                            <span class="text-lg transition duration-300 ease-in-out opacity-1" :class="{'opacity-0': !sidebarOpen}">Выйти</span>
                        </button>
                    </div>
                </div>
                <modal class="confirmation" name="question" close-text>
                    <div class="flex flex-col items-center px-10">
                        <div class="w-12 h-12 mb-5">
                            <icon name="remove" class="h-auto fill-current mr-2"></icon>
                        </div>
                        <p class="text-base text-gray-900 flex flex-col items-center mb-4">
                            <span class="leading-none">Вы уверены</span>
                            <span class="leading-none">что хотите выполнить это действие?</span>
                        </p>
                    </div>
                    <div class="flex justify-center items-center px-5">
                        <button class="text-orange-400 text-base mr-10" @click="$modal.confirm()">Да</button>
                        <button @click="$modal.close('question')" class="text-orange-400 text-base ml-10">Нет</button>
                    </div>
                </modal>
            </div>
        </div>
    </nav>
</template>
<script>
import Icon from './Icon.vue'
import SidebarLink from './SidebarLink'
export default {
        components: {
            SidebarLink,
            Icon,
        },
        data() {
            return {
                sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen')),
                menuLinks: [
                    {
                        section: 'main',
                        name: 'Главная',
                        href: '/orders',
                        icon: 'main',
                    },
                    {
                        section: 'clients',
                        name: 'Клиенты',
                        href: '/clients',
                        icon: 'client',
                    },
                    {
                        section: 'partners',
                        name: 'Партнеры',
                        href: route('partners.index'),
                        icon: 'partners',
                    }
                ],
                aboutLinks: [
                    {
                        section: 'directory',
                        name: 'Справочник',
                        href: route('dictionaries.operators.index'),
                        icon: 'directory',
                    },
                    {
                        section: 'reports',
                        name: 'Отчеты',
                        href: this.$page.props.auth.user.isAdmin
                            ? '/billing/partner-report'
                            : '/billing/detailed-report',
                        icon: 'reports',
                    }
                ]
            }
        },
        mounted() {
            document.querySelector('html').classList.remove('overflow-hidden')
        },
        methods: {
            toggle() {
              this.sidebarOpen = !this.sidebarOpen,
              this.$emit('toggle', {
                  sidebarOpen: this.sidebarOpen,
              })
            },
            logout() {
                this.$inertia.post(route('logout'));
            },
            linkAccess(section) {
                return this.$page.props.auth.user.isAdmin
                    || (this.$page.props.auth.user.isCourierOperator
                        && ['main', 'clients', 'reports'].includes(section))
                    || (this.$page.props.auth.user.isOrderOperator
                        && ['main', 'clients', 'reports'].includes(section))
            }
        },
        watch: {
            sidebarOpen(val) {
                localStorage.sidebarOpen = val;
            }
        }
    }
</script>
<style>
    .sidebar {
        width: 233px;
        transition: .3s ease-in-out;
    }
    .close {
        overflow: hidden;
        width: 85px;
    }
    .close .icon {
        margin-right: 0;
    }
    .close .name {
        display: none;
    }
    .box {
        width: 233px;
    }
    .close .box {
        width: auto;
        overflow: hidden;
    }
</style>
