<template>
  <div class="message-wrapper" v-if="showMessage" :class="[setType, {'on': isAnimate}]">
    <div class="message-inner">
      <div class="message-icon"></div>
      <div class="message-text" v-html="content.msg"></div>
      <div class="message-close" @click.stop="closeMessage"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Message',
  data () {
    return {
      showMessage: false,
      isAnimate: false,
      content: {
        msg: '',
        type: 0,
        timeout: undefined
      }
    }
  },
  props: {
    eventBus: undefined
  },
  computed: {
    setType () {
      let type = ''
      if (this.content.type === 0) {
        type = 'success'
      } else if (this.content.type === 1) {
        type = 'error'
      } else if (this.content.type === 2) {
        type = 'info'
      } else if (this.content.type === 3) {
        type = 'processing'
      } else {
        type = 'success'
      }
      return type
    }
  },
  methods: {
    toggleMessage (state) {
      // if the state is true show message
      if (state) {
        this.showMessage = state
        setTimeout(() => {
          this.isAnimate = state
          // if the timeout is not defined auto close the message
          if (this.content.timeout === undefined) {
            // change the timeout depends on the character length
            let interval = this.content.msg.length < 50 ? 3000 : 5000
            setTimeout(() => {
              this.toggleMessage(false)
            }, interval)
          } else {
            setTimeout(() => {
              this.toggleMessage(false)
            }, this.content.timeout)
          }
        }, 50)
      } else {
        this.isAnimate = state
        setTimeout(() => {
          // set to the default values after closing
          this.content.msg = ''
          this.content.type = 0
          this.content.timeout = undefined
          this.showMessage = state
        }, 500)
      }
    },
    closeMessage () {
      this.toggleMessage(false)
    },
    getMessage (obj) {
      this.content.msg = obj.msg
      this.content.type = obj.type
      this.content.timeout = obj.timeout
      this.toggleMessage(true)
    }
  },
  mounted () {
    this.eventBus.$on('message', this.getMessage)
  },
  beforeDestroy () {
    this.eventBus.$off('message', this.getMessage)
  }
};
</script>

<style scoped lang="scss">
@import "./../../assets/styles/variables.scss";
.message-wrapper {
  position: fixed;
  top: 50px;
  left: 0px;
  width: 100%;
  display: flex;
  justify-content: center;
  z-index: 99999;
  transition: 0.5s;
  transform: translateY(-200%);
  opacity: 0;
}
.message-wrapper.on {
  transition: 0.5s;
  transform: translateY(0%);
  opacity: 1;
}
.message-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: auto;
  min-width: 280px;
  padding: 10px;
  min-height: 38px;
  border-radius: 4px;
  box-shadow: var(--theme-shadow-1);
  margin-top: 5px;
  line-height: 1;
  box-sizing: border-box;
}
.message-text {
  color: $white;
  letter-spacing: 1px;
  font-weight: 300;
  font-size: 14px;
  padding: 0px 10px;
  box-sizing: border-box;
}
.message-close {
  width: 16px;
  height: 16px;
  background-size: 16px;
  background-repeat: no-repeat;
  background-position: center;
  background-image: url(./../../assets/img/icons/cancel.svg);
  cursor: pointer;
}
.message-icon {
  width: 18px;
  height: 18px;
  background-size: 18px;
  background-repeat: no-repeat;
  background-position: center;
}

// success begins
.message-wrapper.success .message-inner {
  background-color: $green;
}
.message-wrapper.success .message-icon {
  background-image: url(./../../assets/img/icons/tick.svg);
}
// success ends
// error begins
.message-wrapper.error .message-inner {
  background-color: #ec644b;
}
.message-wrapper.error .message-icon {
  width: 20px;
  height: 20px;
  background-size: 20px;
  background-image: url(./../../assets/img/icons/warning.svg);
}
// error ends
// info begins
.message-wrapper.info .message-inner {
  background-color: #1e8bc3;
}
.message-wrapper.info .message-icon {
  width: 20px;
  height: 20px;
  background-size: 20px;
  background-image: url(./../../assets/img/icons/information.svg);
}
// info ends
// process begins
.message-wrapper.processing .message-inner {
  background-color: var(--theme-color-3);
}
.message-wrapper.processing .message-icon {
  background-image: url(./../../assets/img/icons/loading.svg);
  filter: contrast(0%) brightness(0%) invert(1);
  width: 24px;
  background-size: 24px;
}
// process ends
</style>
