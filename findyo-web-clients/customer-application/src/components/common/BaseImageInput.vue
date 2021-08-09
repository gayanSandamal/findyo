<template>
  <div class="base-image-input" :style="{ 'background-image': `url(${imageData})` }">
    <span v-if="!imageData" class="placeholder"></span>
    <div class="base-image-action-wrapper">
      <span v-if="imageData" @click="remove" class="base-image-remove"></span>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      imageData: null
    }
  },
  props: {
    file: null,
    files: {
      type: Array,
      default () {
        return []
      }
    }
  },
  methods: {
    remove () {
      const item = this.files.find(o => {
        return o === this.file
      })
      const index = this.files.indexOf(item)
      if (index > -1) {
        this.files.splice(index, 1)
      }
    },
    setFile () {
      if (this.file) {
        const reader = new FileReader()
        reader.onload = e => {
          this.imageData = e.target.result
        }
        reader.readAsDataURL(this.file)
      }
    }
  },
  watch: {
    file: {
      immediate: true,
      deep: true,
      handler: function (val) {
        if (val !== null) {
          this.setFile()
        }
      }
    }
  }
}
</script>

<style scoped>
.base-image-input {
  position: relative;
  display: block;
  width: 200px;
  height: 200px;
  cursor: pointer;
  background-size: cover;
  background-position: center center;
}
.placeholder {
  background: #F0F0F0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #333;
  font-size: 18px;
  font-family: Helvetica;
}
.placeholder:hover {
  background: #E0E0E0;
}
.base-image-action-wrapper {
  position: absolute;
  top: 0px;
  left: 0px;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(255,255,255,0.5);
  cursor: default;
}
.base-image-remove {
  position: absolute;
  top: 5px;
  right: 5px;
  display: inline-block;
  width: 14px;
  height: 14px;
  background-size: 14px;
  background-position: center;
  background-repeat: no-repeat;
  cursor: pointer;
  background-image: url(./../../assets/img/svg/close.svg);
}
</style>
