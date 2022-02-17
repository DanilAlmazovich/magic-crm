class Firebase {
    constructor() {
        const firebaseConfig = {
            apiKey: 'AIzaSyCacKJVKfHWAhorw9fClyjHIOUYW197EqU',
            authDomain: 'koversamolet-d2951.firebaseapp.com',
            databaseURL: 'https://koversamolet-d2951-default-rtdb.europe-west1.firebasedatabase.app',
            projectId: 'koversamolet-d2951',
            storageBucket: 'koversamolet-d2951.appspot.com',
            messagingSenderId: '1064478832559',
            appId: '1:1064478832559:web:106b706381e167aa6fa15a',
            measurementId: 'G-MPJY46DJEM',
        }

        firebase.initializeApp(firebaseConfig)

        this.firebase = firebase
    }

    requestPushPermission() {
        setTimeout(() => {
            this.firebase.messaging()
                .requestPermission()
                .then(() => this.firebase.messaging().getToken())
                .then((token) => this.saveToken(token))
                .catch()
        }, 2000)
    }

    saveToken(token) {
        axios.post('fcm-token', {
            token
        })
    }

    initializeMessaging() {
        this.firebase.messaging().onMessage(payload => {
            this.onMessaging(payload)
        })
    }

    onMessaging(payload) {
        const notificationOption = {
            body: payload.notification.body,
            icon: payload.notification.image,
            requireInteraction: true,
        }

        if (Notification.permission === 'granted') {
            const notification = new Notification(payload.notification.title, notificationOption)

            new Audio('sounds/notification.mp3').play()

            notification.onclick = event => {
                event.preventDefault()
                window.open(payload.data.click_action, '_blank')
                notification.close()
            }
        }
    }
}

export default Firebase
