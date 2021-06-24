<template>
  <router-link :to="displayName ? '/'+findyoName(displayName)+'/timeline' : ''" class="freelancer-img-block">
    <div v-if="userImage" class="freelancer-img" v-bind:style="{ backgroundImage: 'url('+userImage+')' }"
    ></div>
    <div v-else class="user-icon-placeholder"></div>
  </router-link>
</template>

<script>
import {findyoName} from './../../func/usables'
export default {
  name: "UserImageOwn",
  data() {
    return {
    };
  },
  props: {
    userImage: {
      type: String
    },
    displayName: {
      type: String
    }
  },
  methods: {
    findyoName (str) {
      return findyoName(str)
    }
  }
};
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";
.freelancer-img-block,
.freelancer-title-block {
  display: inline-block;
}
.user-widget-container .freelancer-img-block {
  margin-right: 10px;
}
.freelancer-img {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  box-shadow: 0px 0px 10px 0px rgba(255, 255, 255, 0.5);
  background-color: $gray-nue;
}
.user-icon-placeholder {
  background-image: url(./../../assets/img/svg/user.svg);
  width: 36px;
  height: 36px;
  background-size: 20px;
  background-color: $gray-nue;
  border-radius: 50%;
  background-position: center;
  background-repeat: no-repeat;
  box-shadow: 0px 0px 10px 0px rgba(255, 255, 255, 0.5);
}
</style>