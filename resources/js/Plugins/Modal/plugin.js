import modals from './modals'
import Component from './Component.vue'

let plugin = {
    install(Vue) {
        Vue.component('modal',  Component)

        Vue.prototype.$modal = {
            open(name, data = null) {
                modals.$emit('open', name, data)
                document.querySelector('html').classList.add('overflow-hidden')
            },
            close(name) {
                modals.$emit('close', name)
                document.querySelector('html').classList.remove('overflow-hidden')
            },
            question() {
                return new Promise(resolve => {
                    this.open('question')

                    modals.$on('confirm', () => {
                        resolve()

                        this.close('question')
                    })
                })
            },
            confirm() {
                modals.$emit('confirm')
            },
        }
    }
}
export default plugin
