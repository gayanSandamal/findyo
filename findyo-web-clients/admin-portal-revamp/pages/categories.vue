<template>
  <v-row class="mt-5">
    <v-col cols="12" lg="6" class="left-side">
      <v-form ref="form" v-model="state.valid" lazy-validation>
        <v-row class="px-3">
          <v-text-field
            v-model="categoryName"
            label="Add Category"
            placeholder="Enter new category name here"
            autofocus
            :rules="state.categoryNameRules"
          >
          </v-text-field>
        </v-row>
        <v-row class="px-3">
          <v-select
            v-model="state.modalParentComboBox"
            item-text="name"
            item-value="id"
            class="mt-5"
            append-icon="mdi-arrow-down"
            :items="state.parentItems"
            label="Parent Category"
            placeholder="Select a parent category"
            @change="onParentSelect"
          ></v-select>
        </v-row>
        <v-row class="btn-group">
          <div v-if="!state.activeTreeNode" style="width: 100%">
            <v-btn
              class="mt-4"
              block
              clearable
              color="success"
              @click="validate"
              >Add</v-btn
            >
          </div>
          <div v-else style="width: 100%">
            <v-btn class="mt-4" block clearable color="primary">Update</v-btn>
            <v-btn class="mt-4" block clearable color="error">Delete</v-btn>
          </div>
        </v-row>
      </v-form>
    </v-col>
    <v-col cols="12" lg="6">
      <v-card class="py-4 px-3 my-card">
        <v-treeview
          class="ml-5"
          hoverable
          :items="state.items"
          open-all
          transition
          activatable
          color="warning"
          shaped
          @update:active="onTreeActive"
        ></v-treeview>
      </v-card>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import {
  defineComponent,
  onMounted,
  reactive,
  useContext,
  ref
} from '@nuxtjs/composition-api'
import { ICategory, ICategoryTreeItem } from '@/interfaces/category'
export default defineComponent({
  setup(_, context: any) {
    const state = reactive({
      valid: true,
      categories: [] as ICategory[],
      categoryNameRules: [(v: string) => !!v || 'Category name is required'],
      items: [] as ICategoryTreeItem[],
      parentItems: [] as ICategory[],
      activeTreeNode: null as null | number,
      selectedParent: null as ICategory | null | undefined,
      modalParentComboBox: null as null | number
    })

    const categoryName = ref('')

    const { $axios } = useContext()

    const onTreeActive = (val: number[]) => {
      state.activeTreeNode = val.length > 0 ? val[0] : null
      if (state.activeTreeNode) {
        const activeNode = state.categories.find(
          c => c.id === state?.activeTreeNode
        )
        categoryName.value = activeNode ? activeNode.name : ''
        return
      }
      categoryName.value = ''
    }

    const getAllCategories = async () => {
      try {
        const response: any = await $axios.get('postcategory')
        const {
          status,
          data: { data }
        } = response
        if (status === 200) {
          state.categories = [...data]
          const mappedData: ICategory[] = data.map((d: ICategory) => {
            return {
              ...d,
              disabled: false
            }
          })
          state.parentItems = [...mappedData]
          state.parentItems.push({
            id: -1,
            name: 'No Parent',
            parent: null,
            disabled: false
          })
          state.items = nest(state.categories)
        }
      } catch (error) {
        console.error(error)
      }
    }

    const nest = (
      items: ICategory[],
      id: number | null = null,
      link: string = 'parent'
    ): ICategoryTreeItem[] =>
      items
        .filter((item: any) => item[link] === id)
        .map((item: ICategory) => ({ ...item, children: nest(items, item.id) }))

    const onParentSelect = (val: number) => {
      if (!val) {
        return
      }
      state.selectedParent = state.parentItems.find(item => item.id === val)
    }

    const validate = () => {
      context.refs.form.validate()
      if (state.valid) {
        saveCategory()
      }
    }

    const saveCategory = async () => {
      const postData = {
        name: categoryName.value,
        parent:
          state.modalParentComboBox === -1 ||
          state.modalParentComboBox === null ||
          state.modalParentComboBox === undefined
            ? null
            : state.modalParentComboBox
      }
      try {
        const response = await $axios.post('admin/PostCategory', postData)
        console.log(response)
        const { status } = response
        if (status === 200) {
          categoryName.value = ''
          await getAllCategories()
        }
      } catch (error) {
        console.error(error)
      }
    }

    const mounted = async () => {
      await getAllCategories()
    }

    onMounted(mounted)

    return { state, onParentSelect, onTreeActive, categoryName, validate }
  }
})
</script>

<style lang="scss" scoped>
.my-card {
  min-height: 340px;
}
.btn-group {
  margin-top: 40px;
  padding-left: 10px;
  padding-right: 10px;
}
.left-side {
  margin: 25px 0 0 0;
  padding: 30px 40px 30px 30px;
}
</style>
