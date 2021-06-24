<template>
  <div class="page login">
    <!-- <div id="firebaseui-auth-container"></div> -->
    <div class="login-box box-nue" v-if="!isEmailSent">
      <div class="text-wrapper">
        <label>Email</label>
        <input class="text-input" type="email" placeholder="Enter your email here" v-model="email"/>
      </div>
      <div class="text-wrapper">
        <Button :label="'Send email'" :action="reset" :loading="isSending"/>
      </div>
    </div>
    <div class="login-box box-nue" v-else>
      <div class="text-wrapper">
        <label>Please login using email with<br><br>your new password</label>
      </div>
      <div class="text-wrapper">
        <Button class="pull-right" :label="'Login'" :action="goToLogin"/>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import firebase from "firebase/app";
import "firebase/auth";

export default {
  name: "reset-password",
  components: {
    Button: () => import ('@/components/inputs/Button')
  },
  data() {
    return {
      isSending: false,
      email: null,
      isEmailSent: false
    }
  },
  props: {
    eventBus: undefined
  },
  methods: {
    reset() {
      if (this.email && this.email.trim() !== '') {
        this.isSending = true
        var auth = firebase.auth()
        var emailAddress = this.email
        auth.sendPasswordResetEmail(emailAddress).then(() => {
          this.isSending = false
          this.eventBus.$emit("message", {
            msg: `Password reset link has successfully sent to your email. Please check`,
            type: 0
          })
          this.isEmailSent = true
        }).catch((error) => {
          this.isSending = false
          this.eventBus.$emit("message", {
            msg: error,
            type: 1
          })
        })
      } else {
        this.eventBus.$emit("message", {
          msg: `Cannot leave email field empty`,
          type: 1
        })
      }
    },
    goToLogin() {
      this.$router.push({path: '/login'})
    }
  }
};
</script>

<style scoped>
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
