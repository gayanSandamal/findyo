<template>
  <header class="header">
    <div class="full-width">
      <nav class="header-nav">
        <Logo class="logo-wrapper-container" :isMobile="isMobile" :isLogoOnly="true" />
        <div class="header-search-wrapper">
          <input class="header-search" placeholder="search" />
        </div>
        <ul class="user-widget-container pull-right">
          <li v-if="isUser" class="user-widget-li-loggedin">
            <UserWidget
              :displayName="firstLastName"
              :email="displayName"
              :photoUrl="profilePhotoUrl"
            />
            <span class="dropdown-trigger" @click.stop="dropdownTrigger"></span>
            <div class="dropdown-close-overlay" v-if="isDropdown" @click.stop="dropdownTrigger"></div>
            <ul class="dropdown" v-if="isDropdown">
              <li @click.stop="delyedCall">
                <router-link to="/settings/">Settings</router-link>
              </li>
              <li>
                <a @click.stop="logout">Logout</a>
              </li>
            </ul>
          </li>
          <li v-else>
            <router-link to="login" class="login login-navigate-container">login</router-link>
          </li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script>
import firebase from "firebase/app";
import "firebase/auth";

export default {
  name: "header-element",
  data() {
    return {
      isDropdown: false
    };
  },
  props: {
    eventBus: undefined,
    isMobile: {
      type: Boolean,
      default: false
    },
    isUser: {
      type: Boolean,
      default: false
    },
    user: {
      type: Object,
      default() {
        return {};
      }
    },
    userData: {
      type: Object,
      default() {
        return {};
      }
    },
    isProfileDataLoaded: {
      type: Boolean,
      default: false
    }
  },
  components: {
    Logo: () => import("@/components/common/Logo"),
    UserWidget: () => import("@/components/common/UserWidget")
  },
  computed: {
    profilePhotoUrl() {
      let url = "";
      if (this.isProfileDataLoaded) {
        url = this.userData.displayPicture ? this.userData.displayPicture : "";
      }
      return url;
    },
    displayName() {
      let displayName = "";
      if (this.isProfileDataLoaded) {
        displayName = this.userData.displayName
          ? this.userData.displayName
          : "";
      }
      return displayName;
    },
    firstLastName() {
      let firstLastName = "";
      if (this.isProfileDataLoaded) {
        if (this.userData.first_name && this.userData.last_name) {
          firstLastName =
            this.userData.first_name + " " + this.userData.last_name;
        } else if (this.userData.first_name) {
          firstLastName = this.userData.first_name;
        } else {
          firstLastName = this.user.providerData[0].displayName;
        }
      }
      return firstLastName;
    }
  },
  methods: {
    delyedCall() {
      setTimeout(() => this.dropdownTrigger(), 500);
    },
    dropdownTrigger() {
      this.isDropdown = !this.isDropdown;
    },
    logout() {
      this.$store.commit("logout");
    }
  }
};
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";
.login-navigate-container {
  font-size: 10px;
  text-transform: uppercase;
  color: $white;
  display: flex;
  -webkit-box-align: center;
  align-items: center;
  justify-content: center;
  margin: 0px 15px 0px 0px;
  padding: 10px 0px;
  cursor: pointer;
  text-decoration: none;
}
.logo-wrapper {
  position: relative;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.user-widget-container {
  margin: 0px 20px 0px 0px;
}
.header-search-wrapper {
  position: absolute;
  left: calc(50% - 150px);
}
.user-widget-container {
  position: relative;
}
.user-widget-container > li .dropdown {
  top: 48px;
  left: auto;
  right: 0px;
  min-width: 150px;
  width: 100%;
}
.user-widget-li-loggedin {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
