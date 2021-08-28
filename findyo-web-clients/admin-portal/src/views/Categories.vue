<template>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Categories</h5>
      <h6 class="card-subtitle mb-5 text-muted">Add, edit or remove categories</h6>
      <h6 class="card-subtitle mb-2 text-muted" v-text="name_en ? 'Selected categoty: '+name_en : 'There are no selected categories'"></h6>
      <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="cat_name_en">Category name in English</label>
                <input type="text" :disabled="!isInputTextEnable" id="cat_name_en" class="form-control col-md-12" v-model="name_en" />
            </div>
            <div class="form-group">
                <label for="cat_name_sn">Category name in Sinhala</label>
                <input type="text" :disabled="!isInputTextEnable" id="cat_name_sn" class="form-control col-md-12" v-model="name_sn" />
            </div>
            <div class="form-group">
                <label for="cat_name_tm">Category name in Tamil</label>
                <input type="text" :disabled="!isInputTextEnable" id="cat_name_tm" class="form-control col-md-12" v-model="name_tm" />
            </div>
            <div class="row mt-3">
                <div class="col-md">
                    <button type="button" class="btn btn-secondary btn-lg col-md-12" @click="clear">Clear</button>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary btn-lg col-md-12" :disable="name_en.trim() === ''" :class="{'disable': name_en.trim() === ''}" @click="save" v-text="currentItemId ? 'Update' : 'Save'"></button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
              <h6 class="card-subtitle mb-2">Category list</h6>
            </div>
            <div class="row">
              <ul class="list-group col-12" v-if="nestedCats.length > 0">
                <li class="list-group-item list-group-item-action clickable" :class="{'active': currentItemId === category.id}" v-for="(category, categoryIdx) in nestedCats" :key="categoryIdx" @click.stop="selectCategory(category)">
                  <div class="flex v-center h-between">
                    <label v-text="category.name_en"></label>
                    <button type="button" class="btn btn-danger" @click.stop="getDeletePrompt(category)">Delete</button>
                  </div>
                  <ul class="list-group col-12 m-t-1 p-r-0" v-if="category.childs.length > 0">
                    <li class="list-group-item list-group-item-action clickable" v-for="(childCategory, childCategoryIdx) in category.childs" :key="childCategoryIdx">
                      <div class="flex v-center h-between">
                        <label v-text="childCategory.name_en"></label>
                        <button type="button" class="btn btn-danger">Delete</button>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
        </div>
        <div class="col-md-4" v-if="name_en.trim() !== ''">
            <div class="row">
              <h6 class="card-subtitle mb-2">Parent category list</h6>
            </div>
            <div class="row">
              <ul class="list-group col-12" v-if="categories.length > 0">
                <li class="list-group-item list-group-item-action" :class="{'disabled': name_en === category.name_en, 'active': currentParentItemId === category.id}" v-for="(category, categoryIdx) in categories" :key="categoryIdx" @click.stop="selectParentCategory(category)">{{category.name_en}}</li>
              </ul>
            </div>
        </div>
      </div>
    </div>
    <YesNoModal v-if="categoryDeleteModal" :content="{title: 'Delete category permanently', msg: 'Do you want to delete this category permanently?<br><span>Once you delete cannot undo', yes: 'Delete', no: 'Cancel', type: 'danger'}" @ok-delete="deleteCategory" @cancel="cancelDeleteCategory"/>
  </div>
</template>

<script>
export default {
  name: 'locations',
  data () {
    return {
      currentItemId: null,
      currentParentItemId: null,
      name_en: '',
      name_sn: '',
      name_tm: '',
      categories: [],
      childCategories: [],
      isInputTextEnable: true,
      aboutToBeDelete: undefined,
      categoryDeleteModal: false
    }
  },
  components: {
    YesNoModal: () => import('@/components/Modals/YesNoModal')
  },
  props: {
    db: {
      type: Object,
      default () {
        return {}
      }
    },
    alertSettings: {
      type: Object
    }
  },
  computed: {
    nestedCats () {
      const list = []
      if (this.categories.length > 0) {
        this.categories.map(o => {
          const childs = []
          this.childCategories.map(p => {
            if (o.id === p.parent_id) {
              childs.push(p)
            }
          })
          o.childs = childs
          list.push(o)
        })
      }
      return list
    }
  },
  methods: {
    clear () {
      this.currentItemId = null
      this.currentParentItemId = null
      this.name_en = ''
      this.name_sn = ''
      this.name_tm = ''
    },
    save () {
      if (this.currentItemId) {
        this.updateCategory()
      } else {
        this.saveCategory()
      }
    },
    getCategories () {
      this.db.collection('Categories').get().then(snapShot => {
        this.categories = []
        this.childCategories = []
        snapShot.forEach(o => {
          if (o.data().parent_id && o.data().parent_id.trim() !== '') {
            const childObj = {
              id: o.id,
              name_en: o.data().name_en,
              name_sn: o.data().name_sn,
              name_tm: o.data().name_tm,
              parent_id: o.data().parent_id
            }
            this.childCategories.push(childObj)
          }
          const obj = {
            id: o.id,
            name_en: o.data().name_en,
            name_sn: o.data().name_sn,
            name_tm: o.data().name_tm
          }
          this.categories.push(obj)
        })
      }).catch(error => {
        console.log(error)
      })
    },
    saveCategory () {
      if (this.name_en.trim() === '' || this.name_sn.trim() === '' || this.name_tm.trim() === '') {
        this.$set(this.alertSettings, 'show', true)
        this.$set(this.alertSettings, 'msg', 'Category name cannot be empty')
        this.$set(this.alertSettings, 'type', 'alert-warning')
      } else {
        this.db.collection('Categories').add({
          name_en: this.name_en.trim(),
          name_sn: this.name_sn.trim(),
          name_tm: this.name_tm.trim(),
          parent_id: this.currentParentItemId ? this.currentParentItemId : ''
        }).then(() => {
          this.clear()
          this.getCategories()
          this.$set(this.alertSettings, 'show', true)
          this.$set(this.alertSettings, 'msg', 'Category successully added')
          this.$set(this.alertSettings, 'type', 'alert-success')
        }).catch((error) => {
          this.$set(this.alertSettings, 'show', true)
          this.$set(this.alertSettings, 'msg', error)
          this.$set(this.alertSettings, 'type', 'alert-warning')
        })
      }
    },
    selectCategory (obj) {
      this.currentItemId = obj.id
      this.name_en = obj.name_en ? obj.name_en : ''
      this.name_sn = obj.name_sn ? obj.name_sn : ''
      this.name_tm = obj.name_tm ? obj.name_tm : ''
    },
    selectParentCategory (obj) {
      this.currentParentItemId = obj.id
    },
    updateCategory () {
      if (this.name_en.trim() === '' || this.name_sn.trim() === '' || this.name_tm.trim() === '') {
        this.$set(this.alertSettings, 'show', true)
        this.$set(this.alertSettings, 'msg', 'Category name cannot be empty')
        this.$set(this.alertSettings, 'type', 'alert-warning')
      } else {
        this.db.collection('Categories').doc(this.currentItemId).set({
          name_en: this.name_en,
          name_sn: this.name_sn,
          name_tm: this.name_tm,
          parent_id: this.currentParentItemId ? this.currentParentItemId : ''
        })
        this.clear()
        this.getCategories()
        this.$set(this.alertSettings, 'show', true)
        this.$set(this.alertSettings, 'msg', 'Category successully added')
        this.$set(this.alertSettings, 'type', 'alert-success')
      }
    },
    /*eslint-disable*/
    getDeletePrompt(category) {
      this.categoryDeleteModal = true
      this.aboutToBeDelete = category
    },
    deleteCategory() {
      this.db
      .collection("Categories")
      .doc(this.aboutToBeDelete.id)
      .delete()
      .then(() => {
        this.cancelDeleteCategory()
        this.getCategories()
        this.$set(this.alertSettings, 'show', true)
        this.$set(this.alertSettings, 'msg', 'Category successully deleted')
        this.$set(this.alertSettings, 'type', 'alert-success')
      }).catch(error => {
        this.cancelDeleteCategory()
        this.$set(this.alertSettings, 'show', true)
        this.$set(this.alertSettings, 'msg', 'Something went wrong from our end. Try again shortly')
        this.$set(this.alertSettings, 'type', 'alert-warning')
      })
    },
    cancelDeleteCategory() {
      this.categoryDeleteModal = false
      this.aboutToBeDelete = undefined
    }
  },
  mounted() {
    this.getCategories()
  }
}
</script>
