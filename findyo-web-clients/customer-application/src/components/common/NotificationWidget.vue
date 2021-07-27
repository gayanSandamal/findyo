<template>
  <div class="user-widget-wrapper">
    <!-- <router-link to="/"> -->
    <router-link to="/" class="user-widget-inner-wrapper">
      <UserImageOwn :userImage="photoUrl" :displayName="displayName"/>
      <div class="user-title-wrapper">
        <h3><router-link to="/">{{displayName}}</router-link> <span>{{activity}}</span></h3>
        <h5>{{email}}</h5>
      </div>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'notification-widget',
  data () {
    return {}
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
    },
    activity: {
      type: String
    }
  },
  components: {
    UserImageOwn: () => import('@/components/common/UserImageOwn')
  }
}
</script>

<style scoped>
.user-widget-wrapper > .user-widget-inner-wrapper {
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
  font-weight: 500;
}
.user-title-wrapper h3 > span {
  font-weight: 400;
}
.user-title-wrapper h5 {
  font-size: 10px;
  letter-spacing: 0.5px;
}
</style>
