// Initialize Firebase
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
messaging.requestPermission()
    .then(function () {
        console.log('Permission Granted.');

        return messaging.getToken();
    })
    .then(function (token) {
        console.log(token);

        notifyAppServer(token);
    })
    .catch(function () {
        console.log('Permission Pending.');
    });

messaging.onMessage(function (message) {
    console.log('onMessage:', message);
    var notification = new Notification('onMessage', message.notification);
});

function notifyAppServer(token){

}