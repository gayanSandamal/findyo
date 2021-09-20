<template>
  <div class="progress-container flex h-left" v-if="visibility">
    <div class="progress-wrapper flex h-center" :style="{ width: percentage === null ? '0%' : percentage === 0 ? '150px' : percentage + '%' }" v-text="progressText"></div>
  </div>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'progress-bar',
  data() {
    return {
      percentage: null,
      mode: 'state',
      visibility: false
    }
  },
  props: {
    eventBus: undefined
  },
  computed: {
    progressText() {
      let msg = ''
      if (this.mode === 'percentage') {
        if (this.percentage < 100) {
          msg = `uploading... ${this.percentage}%`
        } else {
          msg = 'successfully uploaded'
        }
      } else if (this.mode === 'state') {
        if (this.percentage === null) {

        } else if (this.percentage === 0) {
          msg = `uploading...`
        } else if (this.percentage === 100) {
          msg = `uploading...`
        }
      }
      return msg
    }
  },
  methods: {
    updateProgress(value) {
      this.percentage = value.percentage
      this.mode = value.mode
    }
  },
  watch: {
    percentage: {
      immediate: true,
      handler: function(val) {
        if (this.mode === 'percentage') {
          if (val === 0) {
            this.visibility = false
          } else if (val < 100) {
            this.visibility = true
          } else if (val === 100) {
            setTimeout(() => {
              this.visibility = false
              this.percentage = 0
            }, 3000)
          }
        } else if (this.mode === 'state') {
          if (val === null) {
            this.visibility = false
          } else if (val === 0) {
            this.visibility = true
          } else if (val === 100) {
            setTimeout(() => {
              this.visibility = false
              this.percentage = null
            }, 3000)
          }
        }
      },
      deep: true
    }
  },
  mounted() {
    this.eventBus.$on('progress', this.updateProgress)
  },
  beforeDestroy() {
    this.eventBus.$off('progress', this.updateProgress)
  }
}
</script>

<style scoped>
.progress-container {
  position: fixed;
  top: 0px;
  left: 0px;
  width: 100%;
  z-index: 1004;
}
.progress-wrapper:before {
  content: '';
  position: absolute;
  top: 0px;
  left: 0px;
  width: 150px;
  height: 100%;
  background-image: linear-gradient(to right, rgba(255,255,255,0) , rgba(100, 255, 134, 0.75), rgba(255,255,255,0));
  animation: shade 2s infinite linear;
}
@keyframes shade {
  0% {
    opacity: 0;
    transform: translateX(0%);
  }
  50% {
    opacity: 1;
    transform: translateX(50%);
  }
  100% {
    opacity: 0;
    transform: translateX(100%);
  }
}
</style>