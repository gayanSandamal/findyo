<template>
  <div class="alert alert-dismissible shadow" :class="[type, {'show': show}]" role="alert">
    {{settings.msg}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click.stop="close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'alert',
  data() {
    return {
      show: false
    }
  },
  props: {
    settings: {
      type: Object,
      default() {
        return {
          show: false,
          msg: '',
          type: ''
        }
      }
    }
  },
  computed: {
    type() {
      return {
        'alert-warning': this.settings.type === 'alert-warning',
        'alert-success': this.settings.type === 'alert-success',
        'alert-danger': this.settings.type === 'alert-danger'
      }
    },
  },
  methods: {
    close() {
      setTimeout(() => {
        this.show = true
        setTimeout(() => {
          this.show = false
          setTimeout(() => {
            this.$set(this.settings, 'show', false)
          }, 500)
        }, 2000)
      }, 50)
    }
  },
  watch: {
    settings: {
      immediate: true,
      deep: true,
      handler: function(val, oldVal) {
        if (val.show) {
          this.close()
        } else {
          this.show = false
          this.$set(this.alertSettings, 'show', false)
          this.$set(this.alertSettings, 'msg', '')
          this.$set(this.alertSettings, 'type', '')
        }
      }
    }
  }
}
</script>

<style scoped>
  .alert {
    opacity:  0;
    position: absolute;
    top: 30px;
    left: auto;
    right: 30px;
    /* width: calc(100% - 60px); */
    transition: 0.5s;
  }
  .alert.show {
    opacity: 1;
    transition: 0.5s;
  }
</style>