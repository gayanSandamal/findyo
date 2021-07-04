<template>
  <button class="button" @click.stop="click" :disabled="disabled">
    {{!loading ? label : ''}}
    <svg v-if="loading" class="spinner" width="30px" height="30px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
      <rect class="spinner__rect" x="0" y="0" width="100" height="100" fill="none"></rect>
      <circle class="spinner__circle" cx="50" cy="50" r="40" stroke="#FFF" fill="none" stroke-width="6" stroke-linecap="round"></circle>
      </svg>
  </button>
</template>

<script>
export default {
  name: 'button-element',
  data () {
    return {}
  },
  props: {
    action: undefined,
    label: {
      type: String,
      default: 'ok'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    click () {
      if (this.action && !this.disabled && !this.loading) {
        return this.action()
      }
    }
  }
}
</script>

<style scoped>
  button {
    border: none;
    min-width: 80px;
  }
  @keyframes spinning-circle {
    0% {
      stroke-dashoffset: 0;
      stroke-dasharray: 150.6 100.4;
    }
    50% {
      stroke-dasharray: 1 250;
    }
    100% {
      stroke-dashoffset: 502;
      stroke-dasharray: 150.6 100.4;
    }
  }
  .spinner__circle {
    animation: 2s linear spinning-circle infinite;
  }
  button[disabled="disabled"] {
    cursor: default;
    opacity: 0.5;
  }
</style>
