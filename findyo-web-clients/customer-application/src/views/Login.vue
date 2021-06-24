<template>
  <div class="page login">
    <div id="firebaseui-auth-container"></div>
      <!-- <div class="login-box box-nue">
        <div class="text-wrapper">
          <label>Email</label>
          <input class="text-input" type="email" placeholder="Enter your email here"/>
        </div>
        <div class="text-wrapper">
          <label>Password</label>
          <input class="text-input" type="password" placeholder="Enter your password here"/>
        </div>
        <div class="text-wrapper">
          <Button/>
        </div>
      </div> -->
  </div>
</template>

<script>
import firebase from "firebase/app";
import "firebase/auth";
import * as firebaseui from "firebaseui";

export default {
  name: "login",
  components: {
    // Button: () => import ('@/components/inputs/Button')
  },
  mounted() {
    var uiConfig = {
      signInSuccessUrl: "/",
      signInOptions: [
        {
          provider: firebase.auth.GoogleAuthProvider.PROVIDER_ID,
          scopes: ["https://www.googleapis.com/auth/contacts.readonly"],
          customParameters: {
            prompt: "select_account"
          }
        },
        {
          provider: firebase.auth.FacebookAuthProvider.PROVIDER_ID,
          scopes: ["public_profile", "email", "user_likes", "user_friends"],
          customParameters: {
            // Forces password re-entry.
            auth_type: "reauthenticate"
          },

        },
        firebase.auth.TwitterAuthProvider.PROVIDER_ID,
        firebase.auth.PhoneAuthProvider.PROVIDER_ID,
        {
          provider: firebase.auth.EmailAuthProvider.PROVIDER_ID,
          requireDisplayName: false
        }
      ]
    };
    var ui = new firebaseui.auth.AuthUI(firebase.auth());
    ui.start("#firebaseui-auth-container", uiConfig);
  }
};
</script>

<style scoped>
@import "./../assets/styles/firebaseui.css";
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
</style>
