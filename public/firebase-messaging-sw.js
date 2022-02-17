importScripts('https://www.gstatic.com/firebasejs/8.9.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.9.1/firebase-messaging.js');

const firebaseConfig = {
    apiKey: 'AIzaSyCacKJVKfHWAhorw9fClyjHIOUYW197EqU',
    authDomain: 'koversamolet-d2951.firebaseapp.com',
    databaseURL: 'https://koversamolet-d2951-default-rtdb.europe-west1.firebasedatabase.app',
    projectId: 'koversamolet-d2951',
    storageBucket: 'koversamolet-d2951.appspot.com',
    messagingSenderId: '1064478832559',
    appId: '1:1064478832559:web:106b706381e167aa6fa15a',
    measurementId: 'G-MPJY46DJEM',
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    const notification = JSON.parse(payload);
    const notificationOption = {
        body: notification.body,
        icon: notification.image,
        requireInteraction: true,
    };

    return self.registration.showNotification(payload.notification.title, notificationOption);
});
