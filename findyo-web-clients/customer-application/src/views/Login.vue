<template>
  <div class="page login">
    <div class="login-box box-nue">
      <div class="text-wrapper">
        <label>Email</label>
        <input v-model="loginBinds.email" class="text-input" type="email" placeholder="Enter your email here"/>
      </div>
      <div class="text-wrapper">
        <label>Password</label>
        <input v-model="loginBinds.password" class="text-input" type="password" placeholder="Enter your password here" />
      </div>
      <div class="text-wrapper" v-if="loginBinds.type === 'register'">
        <label>Confirm Password</label>
        <input v-model="loginBinds.c_password" class="text-input" type="password" placeholder="Enter your password again here" />
      </div>
      <div class="text-wrapper">
        <!-- <Button
          :label="button.label"
          :disabled="button.disabled"
          :loading="button.loading"
          @onClick="handleSubmit"
        /> -->
        <button @click="handleSubmit">{{button.label}}</button>
      </div>
      <div class="text-wrapper p-l-1">
        <p v-if="loginBinds.type === 'login'" @click="onOptionClick('register')" class="sub-title categories-list-label options">Don't have an account? Register here</p>
        <p v-else @click="onOptionClick('login')" class="sub-title categories-list-label options">Already have an account? Login here</p>
      </div>
      <!-- <pre>user: {{ user }}</pre>
      <br>
      <pre> count: {{ count }}</pre> -->
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: "login",
  components: {
    // Button: () => import("@/components/inputs/Button")
  },
  data() {
    return {
      button: {
        label: "Login",
        disabled: false,
        loading: false
      },
      loginBinds: {
        type: 'login',
        email: '',
        password: '',
        c_password: ''
      }
    };
  },
  computed: mapState({
    user: state => state.user,
    count: state => state.count
  }),
  methods: {
    onOptionClick (option) {
      if (option === 'login') {
        this.loginBinds.type = 'login'
        this.loginBinds.c_password = ''
        this.button.label = 'Login'
      } else {
        this.loginBinds.type = 'register'
        this.button.label = 'Register'
      }
    },
    handleSubmit () {
      const user = {
        name: 'thenuka',
        token: 'fesdfgvEFVGefaseF21341',
        roles: [1],
        userId: 1
      }
      this.$store.commit('increment')
      console.log(this.user)
      if (!this.user) {
        this.login(user)
      }
    },
    login (user) {
      this.$store.commit('login', user)
    },
    logout () {
      this.$store.commit('logout')
    }
  },
  mounted() {
    //   signInSuccessUrl: "/"
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
.options:hover {
  text-decoration: underline !important;
  letter-spacing: 1px;
}
</style>
