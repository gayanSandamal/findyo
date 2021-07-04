<template>
  <div class="list-wrapper expansion-list-wrapper">
    <ul>
      <li v-for="(item, idx) in list" :key="idx" :class="{'expand': item.expand}">
        <div class="list-label-wrapper flex v-center">
          <span v-if="item.locations.length > 0 && item.locations ? true : false" class="list-expand-icon" @click.stop="expand(item)"></span>
          <span v-if="!isParent && item.locations.length === 0" class="blank-space"></span>
          <label class="list-label" :class="{'added': added(item)}" @click.stop="select(item)">{{title(item)}}</label>
        </div>
        <location-list v-if="item.expand" :list="item.locations" :languageId="languageId" :addedFilters="addedFilters"/>
      </li>
    </ul>
  </div>
</template>

<script>
/* eslint-disable */
import {languageLabel} from './../../../func/usables'
export default {
  name: 'location-list',
  template: 'location-list',
  data () {
    return {}
  },
  props: {
    list: {
      type: Array
    },
    languageId: {
      type: Number,
      default: 0
    },
    addedFilters: {
      type: Array,
      default () {
        return []
      }
    },
    isParent: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    title(obj) {
      return languageLabel(this.languageId, obj)
    },
    expand(loc) {
      if (loc.expand) {
        this.$set(loc, 'expand', false)
        let idx = this.addedFilters.indexOf(loc)
        if (idx > -1) {
          // this.addedFilters.splice(idx, 1)
        }
      } else {
        this.$set(loc, 'expand', true)
        // this.addedFilters.push(loc)
      }
    },
    added (loc) {
      let val = false
      let idx = this.addedFilters.indexOf(loc)
      if (idx > -1) {
        val = true
      } else {
        val = false
      }
      return val
    },
    select (loc) {
      let idx = this.addedFilters.indexOf(loc)
      if (idx > -1) {
        this.addedFilters.splice(idx, 1)
      } else {
        this.addedFilters.push(loc)
      }
    }
  }
}
</script>

<style scoped lang="scss">
.expansion-list-wrapper {
  ul > li {
    .list-expand-icon {
      background-image: url(./../../../assets/img/svg/chevron-right.svg);
    }
  }
}
.blank-space {
  display: inline-block;
  width: 24px;
}
</style>
