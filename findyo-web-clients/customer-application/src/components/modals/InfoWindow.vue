<template>
  <div class="info-container overlay" @click.stop="close">
    <div class="info-wrapper box" @click.stop="nullEvent">
      <div class="info-header" :class="icon">
        <h3>{{info.title}}</h3>
      </div>
      <div class="info-body" :class="icon">
        <div class="info-icon" :class="icon"></div>
        <div v-html="info.message"></div>
      </div>
      <div class="info-footer">
        <div class="info-footer-button-wrapper">
          <Button
            v-if="info.ok"
            class="info-footer-button"
            :label="'Login'"
            :action="okAction"
            @click="okAction"
          />
          <Button
            class="info-footer-button hollow"
            :label="'Cancel'"
            :action="close"
            @click="close"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'info-window',
  components: {
    Button: () => import('@/components/inputs/Button')
  },
  props: {
    info: {
      type: Object
    }
  },
  computed: {
    icon () {
      let iconClass
      if (this.info.type === 1) {
        iconClass = 'success'
      } else if (this.info.type === 2) {
        iconClass = 'error'
      } else if (this.info.type === 3) {
        iconClass = 'warn'
      }
      return iconClass
    }
  },
  methods: {
    okAction () {
      /* eslint-disable */
      console.log(this.info);
    },
    close() {
      this.$set(this.info, "state", false);
    },
    nullEvent() {}
  }
};
</script>

<style scoped>
.info-container {
  position: fixed;
  top: 0px;
  left: 0px;
  width: 100%;
  height: 100%;
  z-index: 99999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0px 15px;
  box-sizing: border-box;
}
.info-wrapper {
  width: 100%;
  max-width: 500px;
  padding: 0px;
  box-sizing: border-box;
  overflow: hidden;
}
.info-body {
  margin-bottom: 20px;
  padding: 0px 30px;
  box-sizing: border-box;
}
.info-footer-button-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
}
.info-footer-button:not(:last-child) {
  margin-right: 15px;
}
.info-icon {
  display: inline-block;
  width: 64px;
  height: 64px;
  background-size: 64px;
  background-position: center;
  background-repeat: no-repeat;
  margin-bottom: 20px;
}
.info-icon.error {
  background-image: url(./../../assets/img/svg/emoji-sad-face.svg);
}
.info-footer {
  padding: 0px 30px;
  box-sizing: border-box;
  padding: 20px 0px 20px 0px !important;
  display: inline-block;
  width: 100%;
}
</style>
