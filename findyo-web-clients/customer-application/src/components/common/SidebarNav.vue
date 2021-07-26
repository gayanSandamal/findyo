<template>
  <nav class="sidebar-nav">
    <ul class="sidebar-ul">
      <li>
        <router-link to="/">
          <span class="sidebar-icon news"></span>
        </router-link>
      </li>
      <li v-if="user">
        <router-link :to="'/' + findyoName(displayName) + '/timeline'">
          <span class="sidebar-icon profile"></span>
        </router-link>
      </li>
      <li v-if="user">
        <router-link :to="'/notifications'">
          <span class="sidebar-icon bell"></span>
        </router-link>
        <!-- <router-link to="login" class="login login-navigate-container">Login</router-link></li> -->
      </li>
      <li v-if="user">
        <router-link :to="'/chat'">
          <span class="sidebar-icon chat"></span>
        </router-link>
      </li>
    </ul>
  </nav>
</template>

<script>
import { findyoName } from "./../../func/usables";
export default {
  name: "sidebar-nav",
  props: {},
  components: {
    // Logo: () => import ('@/components/common/Logo')
  },
  computed: {
    userData() {
      return this.$store.state.user.userData;
    },
    user() {
      return this.$store.state.user.user;
    },
    displayName() {
      let displayName = "";
      if (this.userData) {
        displayName = this.userData.displayName;
      }
      return displayName;
    }
  },
  methods: {
    findyoName(str) {
      return findyoName(str);
    }
  },
  mounted() {}
};
</script>

<style lang="scss" scoped>
.sidebar-icon.news {
  background-image: url(./../../assets/img/svg/news.svg);
}
a.router-link-exact-active .sidebar-icon.news {
  background-image: url(./../../assets/img/svg/news-active.svg);
}
.sidebar-icon.profile {
  background-image: url(./../../assets/img/svg/profile.svg);
}
a.router-link-active .sidebar-icon.profile {
  background-image: url(./../../assets/img/svg/profile-active.svg);
  opacity: 1;
}
.sidebar-icon.chat {
  background-image: url(./../../assets/img/svg/chat.svg);
}
a.router-link-active .sidebar-icon.chat {
  background-image: url(./../../assets/img/svg/chat-active.svg);
  opacity: 1;
}
.sidebar-icon.bell {
  background-image: url(./../../assets/img/svg/bell.svg);
}
a.router-link-active .sidebar-icon.bell {
  background-image: url(./../../assets/img/svg/bell-active.svg);
  opacity: 1;
}
</style>
