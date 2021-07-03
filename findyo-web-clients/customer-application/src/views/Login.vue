<template>
  <div class="page login">
    <div class="login-box box-nue">
      <div class="text-wrapper">
        <label>Email</label>
        <input
          v-model="loginBinds.email"
          class="text-input"
          type="email"
          placeholder="Enter your email here"
        />
      </div>
      <div class="text-wrapper">
        <label>Password</label>
        <input
          v-model="loginBinds.password"
          class="text-input"
          type="password"
          placeholder="Enter your password here"
        />
      </div>
      <div class="text-wrapper" v-if="loginBinds.type === 'register'">
        <label>Confirm Password</label>
        <input
          v-model="loginBinds.c_password"
          class="text-input"
          type="password"
          placeholder="Enter your password again here"
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
          v-if="loginBinds.type === 'login'"
          @click="onOptionClick('register')"
          class="sub-title categories-list-label options"
        >
          Don't have an account? Register here
        </p>
        <p
          v-else
          @click="onOptionClick('login')"
          class="sub-title categories-list-label options"
        >
          Already have an account? Login here
        </p>
      </div>
      <pre>{{ user }}</pre>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import common from '../assets/javascript/common/common';
import login from '@/assets/javascript/api/login/login';
export default {
  mixins: [login],
  name: 'login',
  components: {
    Button: () => import('@/components/inputs/Button'),
  },
  data() {
    return {
      button: {
        label: 'Login',
        disabled: false,
        loading: false,
      },
      loginBinds: {
        type: 'login',
        email: '',
        password: '',
        c_password: '',
      },
    };
  },
  computed: {
    user() {
      return this.$store.state.user;
    },
  },
  methods: {
    onOptionClick(option) {
      if (option === 'login') {
        this.loginBinds.type = 'login';
        this.loginBinds.c_password = '';
        this.button.label = 'Login';
      } else {
        this.loginBinds.type = 'register';
        this.button.label = 'Register';
      }
    },
    async handleSubmit(loginBinds) {
      debugger
      if (loginBinds.type === 'register') {
        const userObj = await {
          displayName: '',
          token: '',
          roles: null,
          userId: 0,
        };
        try {
          await this.register(
            {
              url: 'emailregister',
              data: {
                email: loginBinds.email,
                password: loginBinds.password,
                c_password: loginBinds.c_password,
              },
              method: 'POST',
            },
            async (response) => {
              const responseData = await response;
              if (responseData.state === 500) {
                console.error('something went wrong...');
              } else if (responseData.status === 202) {
                this.showValidation(responseData);
              } else if (responseData.status === 200) {
                console.log('registration success');
                userObj.token = responseData.data.token;
                userObj.roles = Array.isArray(responseData.data.userrole)
                  ? [...responseData.data.userrole]
                  : responseData.data.userrole;
                userObj.userId = responseData.data.cid;
                this.loginToStore(userObj);
              }
            },
            (error) => {
              console.log('registration faild ', error);
            }
          );
        } catch (error) {
          console.error('registration failed ', error);
        }
      } else {
        // handle login
      }
    },
    loginToStore(user) {
      this.$store.commit('login', user);
      this.$router.push({ name: 'home' });
    },
    logout() {
      this.$store.commit('logout');
    },
    showValidation(responseData) {
      if (
        responseData.data.c_password &&
        responseData.data.c_password.length > 0
      ) {
        responseData.data.c_password.forEach((err) => {
          console.error(err);
        });
      }
      if (responseData.data.email && responseData.data.email.length > 0) {
        responseData.data.email.forEach((err) => {
          console.error(err);
        });
      }
      if (responseData.data.password && responseData.data.password.length > 0) {
        responseData.data.password.forEach((err) => {
          console.error(err);
        });
      }
    },
  },
  mounted() {
    //   signInSuccessUrl: "/"
  },
};
</script>

<style scoped>
@import './../assets/styles/firebaseui.css';
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
