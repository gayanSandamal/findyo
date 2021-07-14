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
import login from "@/assets/javascript/api/login/login";
export default {
  mixins: [login],
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
      },
      InfoError: {
        state: true,
        type: 2,
        title: "Sorry",
        message: `Something went wrong`
      },
      InfoSuccess: {
        state: true,
        type: 2,
        title: "Success",
        message: `Success`
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
        emailVerified: false
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
          if (responseData.state === 500) {
            this.eventBus.$emit("set-info", this.InfoError);
          } else if (responseData.status === 200) {
            userObj.token = responseData.data.token;
            userObj.roles = Array.isArray(responseData.data.userrole)
              ? [...responseData.data.userrole]
              : responseData.data.userrole;
            userObj.userId = responseData.data.cid;
            userObj.id = responseData.data.id;
            userObj.email = loginBinds.email;
            this.loginToStore(userObj);
          }
        },
        error => {
          this.showValidation(error.response);
          this.eventBus.$emit("set-info", this.InfoError);
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
      let message = "";
      if (responseData.data.email && responseData.data.email.length > 0) {
        responseData.data.email.forEach((err, index, array) => {
          message += `${err}${array.length - 1 !== index ? "<br/>" : ""}`;
        });
      }
      if (responseData.data.password && responseData.data.password.length > 0) {
        responseData.data.password.forEach((err, index, array) => {
          message += `${array.length > 0 ? "<br/>" : ""}${err}${
            array.length - 1 !== index ? "<br/>" : ""
          }`;
        });
      }
      this.InfoError.title = "Try again";
      this.InfoError.message = message;
      this.eventBus.$emit("set-info", this.InfoError);
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
