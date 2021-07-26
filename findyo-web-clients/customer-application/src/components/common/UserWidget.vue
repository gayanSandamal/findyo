<template>
  <div class="user-widget-wrapper">
    <UserImageOwn :userImage="photoUrl" :displayName="email" />
    <div class="user-title-wrapper">
      <h3 v-text="displayName"></h3>
      <h5 v-if="email" v-text="email"></h5>
    </div>
  </div>
</template>

<script>
export default {
  name: "user-widget",
  data() {
    return {};
  },
  props: {
    displayName: {
      type: String
    },
    email: {
      type: String
    },
    photoUrl: {
      type: String
    }
  },
  components: {
    UserImageOwn: () => import("@/components/common/UserImageOwn")
  }
};
</script>

<style scoped>
.user-widget-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
}
.user-title-wrapper {
  display: inline-block;
  margin: 0px 0px 0px 0px;
  font-size: 12px;
  letter-spacing: 1px;
}
.user-title-wrapper h3 {
  margin-bottom: 5px;
}
.user-title-wrapper h5 {
  font-size: 10px;
  letter-spacing: 0.5px;
}
</style>
