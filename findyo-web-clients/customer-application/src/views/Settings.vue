<template>
  <div class="page settings">
    <!-- <settings-header></settings-header> -->
    <div class="row box settings-inner-wrapper">
      <div class="settings-list">
        <div class="search-settings">
          <search-chat></search-chat>
            <div class="settings-single-item-list-wrapper">
              <router-link :to="'/settings/profile'"><span class="settings-icon profile-settings"></span>Profile Settings</router-link>
            </div>
            <!-- <div class="settings-single-item-list-wrapper">
              <router-link :to="'/settings/privacy'">Privacy Settings</router-link>
            </div> -->
            <div class="settings-single-item-list-wrapper">
              <router-link :to="'/settings/security'"><span class="settings-icon security-settings"></span>Security Settings</router-link>
            </div>
            <!-- <div class="settings-single-item-list-wrapper">
              <router-link :to="'/settings/account'">Account Settings</router-link>
            </div> -->
        </div>
      </div>
      <router-view class="settings-sub-wrapper"/>
      <!-- <component class="settings-item-details-wrapper" v-bind:is="currentTabComponent"></component> -->
    </div>
  </div>
</template>

<script>
import {findyoName} from './../func/usables'
export default {
  name: 'settings',
  data () {
    return {
    }
  },
  props: {
    user: undefined
  },
  components: {
  },
  methods: {
    findyoName (str) {
      return findyoName(str)
    },
    initRedirect () {
      if (this.$route.name === 'settings') {
        this.$router.push({path: '/settings/profile'})
      }
    }
  },
  created() {
    this.initRedirect()
  }
}
</script>

<style lang="scss" scoped>
@import "@/assets/styles/variables.scss";
.page.settings {
  max-width: calc(100% - 0px);
  position: relative;
}
.settings-inner-wrapper {
  display: inline-block;
  width: 100%;
}
.settings-sub-wrapper {
  width: calc(100% - 296px);
  display: inline-block;
  box-sizing: border-box;
  padding: 0px 40px 0px 60px;
}
.settings-list {
  width: 295px;
  font-size: 10px;
  border-right: 1px solid $gray-nue;
  float: left;
}
.search-settings .search-block {
  background-color: $white;
  width: 100%;
  display: flex;
  flex-direction: row-reverse;
}
.settings-single-item-list-wrapper > a {
  position: relative;
  display: inline-flex;
  align-items: center;
  width: 100%;
  margin: 0px 0px 0px 0px;
  padding: 0px 40px;
  box-sizing: border-box;
  transition: 0.2s;
  height: 60px;
  font-size: 12px;
  color: $primary-color;
  font-weight: bold;
  letter-spacing: 1px;
  text-decoration: none;
}
.profile-settings {
  background-image: url(./../assets/img/svg/profile-settings.svg);
}
.security-settings {
  background-image: url(./../assets/img/svg/security-settings.svg);
}
</style>
