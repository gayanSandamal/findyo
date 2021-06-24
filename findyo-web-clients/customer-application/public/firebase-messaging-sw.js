importScripts('http://www.gstatic.com/firebasejs/7.10.0/firebase-app.js')
importScripts('http://www.gstatic.com/firebasejs/7.10.0/firebase-messaging.js')

const config = {
  apiKey: "AIzaSyDkwSysVahS_1DAkXCawbjMC-kM0-6E5go",
  authDomain: "findyo-staging.firebaseapp.com",
  databaseURL: "https://findyo-staging.firebaseio.com",
  projectId: "findyo-staging",
  storageBucket: "findyo-staging.appspot.com",
  messagingSenderId: "554549914414",
  appId: "1:554549914414:web:d882f705ebf33726"
}

firebase.initializeApp(config)

const messaging = firebase.messaging()

messaging.setBackgroundMessageHandler(payload => {
  const title = 'yo yo'
  const options = {
    body: payload.data.status
  }
  return self.registration.showNotification(title, options)
})