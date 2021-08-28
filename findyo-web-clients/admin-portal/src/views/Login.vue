<template>
  <div class="page login">
    <div id="firebaseui-auth-container"></div>
  </div>
</template>

<script>
import firebase from 'firebase/app'
import 'firebase/auth'
import * as firebaseui from 'firebaseui'

export default {
  name: 'login',
  mounted () {
    var uiConfig = {
      signInSuccessUrl: '/',
      signInOptions: [
        {
          provider: firebase.auth.GoogleAuthProvider.PROVIDER_ID,
          scopes: ['https://www.googleapis.com/auth/contacts.readonly'],
          customParameters: {
            prompt: 'select_account'
          }
        },
        {
          provider: firebase.auth.FacebookAuthProvider.PROVIDER_ID,
          scopes: ['public_profile', 'email', 'user_likes', 'user_friends'],
          customParameters: {
            // Forces password re-entry.
            auth_type: 'reauthenticate'
          }

        },
        firebase.auth.TwitterAuthProvider.PROVIDER_ID,
        firebase.auth.PhoneAuthProvider.PROVIDER_ID,
        {
          provider: firebase.auth.EmailAuthProvider.PROVIDER_ID,
          requireDisplayName: false
        }
      ]
    }
    var ui = new firebaseui.auth.AuthUI(firebase.auth())
    ui.start('#firebaseui-auth-container', uiConfig)
  }
}
</script>

<style scoped>
@import "./../assets/css/firebaseui.css";
.page.login {
  max-width: calc(100% - 90px);
}
</style>
