<template>
  <div class="page login">
    <!-- <div id="firebaseui-auth-container"></div> -->
     <div class="login-box box-nue">
      <div class="text-wrapper">
        <label>Email</label>
        <input
          v-model="loginBinds.email"
          class="text-input"
          type="email"
          placeholder="Enter your email here"
          v-on:keyup.enter="handleSubmit(loginBinds)"
        />
      </div>
      <div class="text-wrapper">
        <label>Password</label>
        <input
          v-model="loginBinds.password"
          class="text-input"
          type="password"
          placeholder="Enter your password here"
          v-on:keyup.enter="handleSubmit(loginBinds)"
        />
      </div>
      <div class="text-wrapper">
        <Button
          :label="button.label"
          :disabled="button.disabled"
          :loading="button.loading"
          @click="handleSubmit(loginBinds)"
        />
      </div>
      <div class="text-wrapper p-l-1">
        <p
          @click="goToRegister"
          class="sub-title categories-list-label options"
        >Don't have an account? Register here</p>
      </div>
      <!-- <pre>{{ user }}</pre> -->
    </div>
  </div>
</template>

<script>
// import firebase from 'firebase/app'
// import 'firebase/auth'
// import * as firebaseui from 'firebaseui'
import user from '@/assets/javascript/api/user'
export default {
  mixins: [user],
  name: 'login',
  data () {
    return {
      button: {
        label: 'Login',
        disabled: false,
        loading: false
      },
      loginBinds: {
        email: '',
        password: ''
      }
    }
  },
  mounted () {
    // var uiConfig = {
    //   signInSuccessUrl: '/',
    //   signInOptions: [
    //     {
    //       provider: firebase.auth.GoogleAuthProvider.PROVIDER_ID,
    //       scopes: ['https://www.googleapis.com/auth/contacts.readonly'],
    //       customParameters: {
    //         prompt: 'select_account'
    //       }
    //     },
    //     {
    //       provider: firebase.auth.FacebookAuthProvider.PROVIDER_ID,
    //       scopes: ['public_profile', 'email', 'user_likes', 'user_friends'],
    //       customParameters: {
    //         // Forces password re-entry.
    //         auth_type: 'reauthenticate'
    //       }

    //     },
    //     firebase.auth.TwitterAuthProvider.PROVIDER_ID,
    //     firebase.auth.PhoneAuthProvider.PROVIDER_ID,
    //     {
    //       provider: firebase.auth.EmailAuthProvider.PROVIDER_ID,
    //       requireDisplayName: false
    //     }
    //   ]
    // }
    // var ui = new firebaseui.auth.AuthUI(firebase.auth())
    // ui.start('#firebaseui-auth-container', uiConfig)
  }
}
</script>

<style scoped>
@import "./../assets/styles/firebaseui.css";
.page.login {
  max-width: calc(100% - 90px);
}
.page.login {
  width: calc(100% - 0px);
  height: calc(100vh - 60px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0px 0px 0px 0px;
}
.login-box {
  width: 100%;
  max-width: 300px;
}
.options:hover {
  text-decoration: underline !important;
  letter-spacing: 1px;
}
</style>
