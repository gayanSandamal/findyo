<template>
  <div>
    <div class="setting-item-header">
      <div class="setting-item-name">
        Security Settings
      </div>
      <div class="search-settings">
        <!-- <search-chat></search-chat> -->
      </div>
    </div>
    <div class="inner-settings-content-wrapper">
      <div class="inner-settings-item">
        <div class="title">Reset password</div>
        <div class="value">********</div>
        <router-link to="/reset-password" class="inner-settings-button-edit">Reset</router-link>
      </div>
      <div class="inner-settings-button-wrapper">
        <Button class="hollow pull-right" style="margin-right: 10px;" :label="'Cancel'" :action="post" />
        <Button class="pull-right" :label="'Save'" :action="post" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'profile-settings',
  data() {
    return {

    }
  },
  components: {
    Button: () => import("@/components/inputs/Button")
  }
}
</script>

<style lang="scss" scoped>
@import "@/assets/styles/variables.scss";
.setting-item-header {
  display: inline-flex;
  width: 100%;
  justify-content: space-between;
  padding: 0px;
  align-items: center;
  height: 60px;
  border-bottom: 1px solid #e7e7ed;
}
.setting-item-header .search-block {
  background-color: $white;
  display: inline-block;
}
.setting-item-name {
  margin: 0px 0px 0px 0px;
  font-size: 12px;
  color: $primary-color;
  font-weight: bold;
  letter-spacing: 1px;
}
.setting-item-header .search-settings .search-block .search-button {
  justify-content: flex-end;
}
.setting-item-header .search-settings .search-block .search-field {
  padding: 21px 20px;
}
.inner-settings-button-edit {
  text-align: right;
  cursor: pointer;
}
.inner-settings-item {
  position: relative;
  display: inline-flex;
  align-items: center;
  width: 100%;
  padding: 0px;
  box-sizing: border-box;
  transition: 0.2s;
  height: 60px;
}
.inner-settings-item .value {
  width: 70%;
  font-weight: 500;
  font-size: 12px;
  color: $primary-color;
  opacity: 0.5;
}
.inner-settings-item .title {
  width: 20%;
  font-size: 12px;
  color: $primary-color;
  font-weight: bold;
  letter-spacing: 1px;
}
.inner-settings-button-edit {
  width: auto;
  font-size: 12px;
}
.inner-settings-button {
  width: 112px;
  height: 40px;
  border: none;
  border: 1px solid $green;
  box-sizing: border-box;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  border-radius: 4px;
  font-weight: normal;
  font-size: 12px;
  line-height: 18px;
  letter-spacing: 0.07em;
}
.inner-settings-btn-save {
  color: $white;
  background: $green;
}
.inner-settings-btn-cancel {
  color: $green;
  border: 1px solid $green;
  background-color: $white;
  margin-right: 10px;
}
.search-settings .search-block .search-field::placeholder {
  color: transparent;
}
.inner-settings-button-wrapper {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  margin: 20px 0px 0px 0px;
}
</style>