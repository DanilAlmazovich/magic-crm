require('./bootstrap')

import Firebase from './firebase'

// Import modules...
import Vue from 'vue'
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue'
import PortalVue from 'portal-vue'
import Icon from './Components/Icon.vue'
import Modal from './Plugins/Modal/plugin'
import Multiselect from 'vue-multiselect'
import VueTheMask from 'vue-the-mask'
import moment from 'moment'
import RadioButton from '@/Components/RadioButton'
import Checkbox from '@/Jetstream/Checkbox'
import Counter from '@/Components/Counter'
import AppLayoutMap from './Layouts/AppLayoutMap'
import Pagination from '@/Components/Pagination'

Vue.mixin({
    methods: {
        route,
        back() {
            window.history.back()
        },
        moment
    },
})
Vue.use(InertiaPlugin)
Vue.use(PortalVue)
Vue.component('icon', Icon)
Vue.use(Modal)
Vue.component('multiselect', Multiselect)
Vue.use(VueTheMask)
Vue.component('radio-button', RadioButton)
Vue.component('checkbox', Checkbox)
Vue.component('counter', Counter)
Vue.component('app-layout-map', AppLayoutMap)
Vue.component('Pagination', Pagination)

const app = document.getElementById('app')

Vue.prototype.$firebase = new Firebase

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
    mounted() {
        this.$firebase.initializeMessaging()
    },
}).$mount(app);
