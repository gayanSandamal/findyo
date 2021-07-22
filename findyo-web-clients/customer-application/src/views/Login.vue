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
import { mapState } from "vuex";
import common from "../assets/javascript/common/common";
import user from "@/assets/javascript/api/user";
export default {
  mixins: [user],
  name: "login",
  components: {
    Button: () => import("@/components/inputs/Button")
  },
  props: {
    eventBus: undefined
  },
  data() {
    return {
      button: {
        label: "Login",
        disabled: false,
        loading: false
      },
      loginBinds: {
        email: "",
        password: ""
      }
    };
  },
  computed: {
    user() {
      return this.$store.state.user;
    }
  },
  methods: {
    async handleSubmit(loginBinds) {
      const userObj = await {
        displayName: "",
        token: "",
        roles: null,
        userId: 0,
        email: null,
        emailVerified: false,
        phoneNumber: "",
        photoURL: "",
        registerMethod: "email",
        username: null
      };

      await this.emailLogin(
        {
          url: "emaillogin",
          data: {
            email: loginBinds.email,
            password: loginBinds.password
          },
          method: "POST"
        },
        async response => {
          const responseData = await response;
          if (!responseData || !responseData.data) {
            this.eventBus.$emit("message", {
              msg: "Something went wrong!",
              type: 1
            });
            return;
          }
          if (responseData.status === 203) {
            this.eventBus.$emit("message", {
              msg: "username or password is incorrect please try again",
              type: 1
            });
          } else if (responseData.status === 200) {
            userObj.token = responseData.data.token;
            userObj.roles = Array.isArray(responseData.data.userrole)
              ? [...responseData.data.userrole]
              : responseData.data.userrole;
            userObj.userId = responseData.data.cid;
            userObj.id = responseData.data.id;
            userObj.email = loginBinds.email;
            userObj.username = responseData.data.name;
            this.loginToStore(userObj);
          }
        },
        error => {
          this.showValidation(error.response);
        }
      );
    },
    loginToStore(user) {
      this.$store.commit("login", user);
      this.$router.push({ name: "home" });
    },
    logout() {
      this.$store.commit("logout");
    },
    showValidation(responseData) {
      if (!responseData) {
        this.eventBus.$emit("message", {
          msg: "Something went wrong!",
          type: 1
        });
        return;
      }
      if (responseData.data.email && responseData.data.email.length > 0) {
        responseData.data.email.forEach((err, index, array) => {
          this.eventBus.$emit("message", {
            msg: err,
            type: 1
          });
        });
      } else if (
        responseData.data.password &&
        responseData.data.password.length > 0
      ) {
        responseData.data.password.forEach((err, index, array) => {
          this.eventBus.$emit("message", {
            msg: err,
            type: 1
          });
        });
      }
    },
    resetFields(all = false) {
      loginBinds.password = "";
      if (all) {
        loginBinds.email = "";
      }
    },
    goToRegister() {
      this.$router.push({ name: "register" });
    }
  },
  mounted() {}
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
