<template>
  <div class="logo-wrapper">
    <router-link to="/">
      <div class="logo-img-wrapper">
        <img class="logo-img" src="./../../assets/img/logo.png"/>
      </div>
      <div class="logo-text-wrapper" v-if="!isMobile && !isLogoOnly">
        <span class="logo-name">Findyo.lk</span>
        <span class="logo-tagline">Find your own way</span>
      </div>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'Logo',
  data () {
    return {}
  },
  props: {
    isMobile: {
      type: Boolean,
      default: false
    },
    isLogoOnly: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";
.logo-img {
  max-width: 100%;
  width: 40px;
}
.logo-img-wrapper, .logo-text-wrapper {
    float: left;
}
.logo-img-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
}
.logo-name, .logo-tagline {
  display: block;
  color: $white;
}
.logo-name {
  font-size: 18px;
  font-weight: 300;
  letter-spacing: 5px;
}
.logo-tagline {
  font-size: 10px;
  text-transform: uppercase;
  font-weight: 100;
  letter-spacing: 0.5px;
  line-height: 1;
  margin: 5px 0px 0px 0px;
}
.logo-wrapper a {
  float: left;
}
</style>
