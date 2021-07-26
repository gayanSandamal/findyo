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
      this.fillVueXStore(user);
    },
    async fillVueXStore(user) {
      await this.getUser(
        {
          url: `GetUser/${user.id}`,
          method: "GET",
          data: {
            token: user.token
          }
        },
        async response => {
          const { data } = await response;
          const profileData = {};
          const userData = {};
          // setProfile Data
          profileData.email = user.email;
          profileData.last_name = data[0].lastname;
          profileData.displayName = data[0].displayname;
          profileData.first_name = data[0].firstname;
          profileData.username = data[0].username;
          profileData.district = data[0].district;
          profileData.creationTime = data[0].created_at;
          profileData.phoneNumber = data[0].phone;
          profileData.postal_code = data[0].postal_code;
          profileData.address = data[0].address;
          profileData.emailVerified = data[0].email_verified_at
            ? data[0].email_verified_at
            : false;
          profileData.job_title = data[0].job_title;
          profileData.country = data[0].country;
          profileData.province = data[0].province;
          profileData.city = data[0].city;
          profileData.id = user.id;

          //set userData
          userData.creationTime = data[0].created_at;
          userData.job_title = data[0].job_title;
          userData.address = data[0].address;
          userData.postal_code = data[0].postal_code;
          userData.displayName = data[0].displayname;
          userData.username = data[0].username;
          userData.phoneNumber = data[0].phone;
          userData.district = data[0].district;
          userData.email = user.email;
          userData.country = data[0].country;
          userData.last_name = data[0].lastname;
          userData.province = data[0].province;
          userData.emailVerified = data[0].email_verified_at
            ? data[0].email_verified_at
            : false;
          userData.first_name = data[0].firstname;
          userData.city = data[0].city;
          userData.id = user.id;

          const updatedUser = {
            ...user,
            displayName: data[0].displayname,
            phoneNumber: data[0].phone,
            emailVerified: data[0].email_verified_at
              ? data[0].email_verified_at
              : false,
            userId: data[0].cid,
            username: data[0].username
          };
          // save to store for later use inside components
          this.$store.commit("updateUser", updatedUser);
          this.$store.commit("setUserData", userData);
          this.$store.commit("setProfileData", profileData);
          setTimeout(() => {
            this.$router.push({ name: "home" });
          }, 1000);
        },
        error => {
          console.error(error);
        }
      );
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
    },
    checkAlreadyLogin() {
      if (this.user.user) {
        this.$router.push({ name: "home" });
      }
    }
  },
  mounted() {
    this.checkAlreadyLogin();
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
