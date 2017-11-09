importScripts('https://www.gstatic.com/firebasejs/4.6.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/4.6.1/firebase-messaging.js');

var config = {
    apiKey: "AIzaSyDZzJAnmN5pYWwRs64BQMne7k_x6_ixTsI",
    authDomain: "push-notifications-testi-eef48.firebaseapp.com",
    databaseURL: "https://push-notifications-testi-eef48.firebaseio.com",
    projectId: "push-notifications-testi-eef48",
    storageBucket: "push-notifications-testi-eef48.appspot.com",
    messagingSenderId: "1055739976473"
};

firebase.initializeApp(config);

var messaging = firebase.messaging();