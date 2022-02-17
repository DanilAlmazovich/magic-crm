<template>
    <header>
        <div class="flex justify-between items-center py-6">
            <div class="flex flex-wrap">
                <inertia-link
                    v-for="(link, index) in links"
                    v-if="linkAccess(link.name)"
                    :key="`billing-header-link-${index}`"
                    :href="link.href"
                    :class="route().current(link.name) ? 'text-orange-400 border-b-2 border-orange-400' : 'text-gray-900'"
                    class="nav-link font-medium text-gray-900 leading-relaxed mb-5 mr-8">
                    {{ link.label }}
                </inertia-link>
            </div>
        </div>
    </header>
</template>
<script>
export default {
    name: 'BillingHeader',
    data() {
        return {
            links: [
                {
                    label: 'Операции',
                    href: '/billing/operations',
                    name: 'billing.operations.index'
                },
                {
                    label: 'Состояние счетов',
                    href: '/billing/wallets',
                    name: 'billing.wallets.index'
                },
                // {
                //     label: 'Пополнения через системы',
                //     href: '#'
                // },
                {
                    label: 'Отчет по партнерам',
                    href: '/billing/partner-report',
                    name: 'billing.partner-report'
                },
                {
                    label: 'Отчет по продуктам партнеров',
                    href: '/billing/product-report',
                    name: '.product-report'
                },
                {
                    label: this.$page.props.auth.user.isOrderOperator
                        ? 'Детальный отчет'
                        : 'Детальный отчет по операторам',
                    href: '/billing/detailed-report',
                    name: 'billing.detailed-report'
                },
                {
                    label: 'Сводный отчет по операторам',
                    href: '/billing/consolidated-report',
                    name: 'billing.consolidated-report'
                }
            ]
        }
    },
    methods: {
        linkAccess(name) {
            return this.$page.props.auth.user.isAdmin
                || (this.$page.props.auth.user.isCourierOperator
                    && ['billing.detailed-report', 'billing.couriers-report'].includes(name))
                || (this.$page.props.auth.user.isOrderOperator
                    && ['billing.detailed-report', 'billing.consolidated-report'].includes(name))
        }
    }
}
</script>
