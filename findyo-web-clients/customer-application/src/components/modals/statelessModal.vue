<template>
  <div class="info-container modal-container overlay" @click.stop="close">
    <div class="info-wrapper box" @click.stop="nullEvent">
      <div class="info-header" v-if="title">
        <h3>{{title}}</h3>
      </div>
      <div class="info-body">
        <slot></slot>
      </div>
      <div class="info-footer">
        <div class="info-footer-button-wrapper">
          <Button v-if="ok" class="info-footer-button" :label="okLabel" :action="okAction"/>
          <Button class="info-footer-button hollow" :label="cancelLabel" :action="cancelAction"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'stateless-modal',
  components: {
    Button: () => import('@/components/inputs/Button')
  },
  props: {
    ok: undefined,
    close: undefined,
    title: {
      type: String
    },
    okLabel: {
      type: String,
      default: 'Ok'
    },
    cancelLabel: {
      type: String,
      default: 'Cancel'
    }
  },
  computed: {
  },
  methods: {
    okAction () {
      if (this.ok) {
        return this.ok()
      }
    },
    cancelAction () {
      if (this.close) {
        return this.close()
      }
    },
    nullEvent () {}
  }
}
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
.info-header {
  color:
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
