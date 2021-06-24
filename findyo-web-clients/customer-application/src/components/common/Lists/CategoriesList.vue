<template>
  <div class="categories-list-wrapper" v-if="list && list.length > 0">
    <ul>
      <li v-for="(item, idx) in list" :key="idx" :class="{'expand': item.expand}">
        <div class="categories-list-label-wrapper switch-nue flex v-center h-start" :class="{'added selected': item.selected}" @click.stop="select(item)">
          <label class="categories-list-label">{{item.name_en}}</label>
        </div>
        <categories-list v-if="item.selected" :list="item.childs"/>
      </li>
    </ul>
  </div>
</template>

<script>
/* eslint-disable */
export default {
  name: 'categories-list',
  template: 'categories-list',
  data () {
    return {
      childs: []
    }
  },
  props: {
    list: {
      type: Array
    },
    languageId: {
      type: Number,
      default: 0
    }
  },
  methods: {
    select (loc) {
      this.list.map(o => {
        this.$set(o, 'selected', false)
        if (o.childs && o.childs.length > 0) {
          o.childs.map(p => {
            this.$set(p, 'selected', false)
          })
        }
      })
      this.list.map(o => {
        if (o.id === loc.id) {
          this.$set(o, 'selected', true)
        } else {
          this.$set(o, 'selected', false)
        }
      })
    }
  }
}
</script>

<style scoped lang="scss">
.list-label-wrapper {
  display: inline-block;
  padding: 2px 10px 2px 0px;
}
.categories-list-label-wrapper {
  position: relative;
  overflow: hidden;
}
.list-label {
  padding: 2px 5px;
  box-sizing: border-box;
  height: 18px;
  cursor: pointer;
}
.list-wrapper .list-wrapper {
  position: relative;
  ul {
    padding: 0px 0px 0px 0px;
    margin: 0px 0px 0px 10px;
    &:empty {
      display: none;
    }
  }
}
</style>
