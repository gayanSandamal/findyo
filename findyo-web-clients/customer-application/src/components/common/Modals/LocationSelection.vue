<template>
  <div class="category-selection-modal-container flex v-center h-center">
    <div class="post-overlay-before post-overlay" @click.stop="clearSelected"></div>
    <div class="box-nue category-selection-modal">
      <div class="full-width m-b-1">
        <h3 class="title">Select location</h3>
      </div>
      <div class="full-width m-b-1">
        <ul class="selected-breadbrumb">
          <li class="chip inline-flex v-center no-select" v-show="item.selected" v-for="(item, index) in breadcrumb" :key="index">
            <span>{{item.name_en}}</span>
            <span class="remove-tag" @click.stop="remove(item)"></span>
          </li>
        </ul>
      </div>
      <div class="full-width m-b-1">
       <LocationsList :list="list" class="first-category-selection-frame"/>
      </div>
      <div class="flex h-between full-width m-t-1">
        <Button class="info-footer-button hollow" :label="'Cancel'" :action="cancel"/>
        <Button class="info-footer-button" :label="'Add'" :action="removeModal"/>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
export default {
  name: 'category-select-modal',
  data() {
    return {
    }
  },
  components: {
    LocationsList: () => import('@/components/common/Lists/LocationsList'),
    Button: () => import("@/components/inputs/Button")
  },
  props: {
    list: {
      type: Array,
      default() {
        return []
      }
    },
    breadcrumb: {
      type: Array
    },
    isLocationSelectModal: {
      type: Object
    }
  },
  methods: {
    remove(obj) {
      this.list.map(o => {
        if (obj.id === o.id) {
          this.$set(o, 'selected', false)
        } else {
          o.locations.map(p => {
            if (obj.id === p.id) {
              this.$set(p, 'selected', false)
            }
          })
        }
      })
    },
    cancel() {
      if (this.breadcrumb.length > 0) {
        this.removeModal()
      } else {
        this.clearSelected()
      }
    },
    removeModal() {
      this.$set(this.isLocationSelectModal, 'state', false)
    },
    clearSelected() {
      this.list.map(o => {
        this.$set(o, 'selected', false)
        o.locations.map(p => {
          this.$set(p, 'selected', false)
        })
      })
      this.removeModal()
    }
  }
}
</script>

<style scoped>
.category-selection-modal-container {
  position: fixed;
  top: 0px;
  left: 0px;
  width: 100%;
  height: 100%;
  z-index: 1002;
}
.category-selection-modal {
  position: relative;
  z-index: 1000;
  width: 100%;
  max-width: 300px;
}
.category-selection-ul:not(:last-child) {
  margin: 0px 20px 0px 0px;
}
.category-selection-li:not(:last-child) {
  margin: 0px 0px 10px 0px;
}
.selected-breadbrumb > li:not(:last-child):after {
  content: url(./../../../assets/img/svg/chevron-right.svg);
  position: absolute;
  width: 10px;
  height: 14px;
  background-size: 10px;
  background-position: center;
  background-repeat: no-repeat;
  right: -20px;
  top: 5px;
}
.remove-tag {
  background-image: url(./../../../assets/img/svg/plus-white.svg);
  display: inline-block;
  width: 14px;
  height: 14px;
  background-size: 10px;
  background-position: center;
  background-repeat: no-repeat;
  transform: rotate(45deg);
  margin: 0px 0px 0px 10px;
  cursor: pointer;
}
</style>
