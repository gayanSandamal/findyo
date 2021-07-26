<template>
  <div class="page notifications">
    <ul class="notifications-ul box">
      <li>
        <div class="notifications-ul-wrapper">
          <NotificationWidget :displayName="'Rajmal Ariyapperuma'" :email="'2 mins ago'" :photoUrl="''" :activity="'is asking for your service'" class="chat-name-list"/>
        </div>
      </li>
      <li>
        <div class="notifications-ul-wrapper">
          <NotificationWidget :displayName="'Rajmal Ariyapperuma'" :email="'2 mins ago'" :photoUrl="''" :activity="'is asking for your service'" class="chat-name-list"/>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'notifications',
  components: {
    NotificationWidget: () => import('@/components/common/NotificationWidget')
  },
  data () {
    return {}
  },
  props: {}
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";
.notifications-ul {
  display: inline-block;
  width: 100%;
  overflow: hidden;
}
.notifications-ul > li {
  position: relative;
  display: inline-block;
  width: 100%;
}
.notifications-ul > li:not(:last-child):after {
  content: '';
  position: absolute;
  width: 2px;
  height: 100%;
  background-color: $gray-nue;
  top: 100%;
  left: 17px;
  z-index: 0;
}
.notifications-ul > li:not(:last-child) {
  margin: 0px 0px 20px 0px;
}
.notifications-ul-wrapper >>> .user-widget-wrapper .freelancer-img-block {
  margin: 0px 10px 0px 0px;
}
.notifications-ul-wrapper >>> .user-widget-wrapper > .user-widget-inner-wrapper {
  justify-content: flex-start;
}
</style>
