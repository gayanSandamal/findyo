<template>
  <div>
    <div class="setting-item-header">
      <div class="setting-item-name">
        Profile Settings
      </div>
      <div class="search-settings">
        <!-- <search-chat></search-chat> -->
      </div>
    </div>
    <div class="inner-settings-content-wrapper">
      <div class="inner-settings-item">
        <div class="title">Username</div>
        <div class="value">g.sandamal</div>
        <a href="" class="inner-settings-button-edit">Edit</a>
      </div>
      <div class="inner-settings-item">
        <div class="title">First name</div>
        <div class="value">Gayan</div>
        <a href="" class="inner-settings-button-edit">Edit</a>
      </div>
      <div class="inner-settings-item">
        <div class="title">Last name</div>
        <div class="value">Sandamal</div>
        <a href="" class="inner-settings-button-edit">Edit</a>
      </div>
      <div class="inner-settings-item">
        <div class="title">Email</div>
        <div class="value">gayansandamal94@gmail.com</div>
        <a href="" class="inner-settings-button-edit">Edit</a>
      </div>
      <div class="inner-settings-button-wrapper">
        <Button class="deactivate-button pull-right" :label="'DEACTIVATE MY ACCOUNT'" :action="getDeactivateConfirmationModal" />
      </div>
      <div class="inner-settings-button-wrapper">
        <!-- <Button class="hollow pull-right" style="margin-right: 10px;" :label="'Cancel'" :action="'post'" /> -->
        <Button class="pull-right" :label="'Save'" :action="'Save'" />
      </div>
    </div>
    
    <stateleess-modal class="deactivate-modal" v-if="isDeactivateConfirmationModal" :ok="deactivateAccount" :okLabel="'Yes, Deactivate'" :cancelLabel="'Cancel'" :close="hideDeactivateConfirmationModal">
      <div>
        <h3 style="font-size: 18px; margin-bottom: 20px;">Do you want to deactivate your account?</h3>
        <p>Deactivating your account will not delete your personal data and your activities, you will be able to activate your account anytime</p>
      </div>
    </stateleess-modal>
  </div>
</template>

<script>
export default {
  name: 'profile-settings',
  data() {
    return {
      isDeactivateConfirmationModal: false
    }
  },
  components: {
    Button: () => import("@/components/inputs/Button"),
    'stateleess-modal': () => import ('@/components/modals/statelessModal')
  },
  methods: {
    getDeactivateConfirmationModal() {
      this.isDeactivateConfirmationModal = true
    },
    hideDeactivateConfirmationModal() {
      this.isDeactivateConfirmationModal = false
    },
    deactivateAccount() {

    }
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
.deactivate-button {
  background-color: #c52727;
}
</style>