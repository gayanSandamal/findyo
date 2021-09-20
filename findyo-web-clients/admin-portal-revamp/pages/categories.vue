<template>
  <v-row class="mt-5">
    <v-col cols="12" lg="6" class="left-side">
      <client-only>
        <h3 v-if="!state.activeTreeNode" class="mb-5 pb-5">Add new category</h3>
        <h3 v-else class="mb-5 pb-5">Update / Delete category</h3>
      </client-only>
      <v-form ref="form" v-model="state.valid" class="pt-3">
        <v-row class="px-3">
          <v-text-field
            v-model="categoryName"
            :label="!state.activeTreeNode ? 'Add Category' : 'Update Category'"
            placeholder="Enter category name here"
            :rules="state.categoryNameRules"
            outlined
          >
          </v-text-field>
        </v-row>
        <v-row class="px-3">
          <v-combobox
            v-model="state.modalParentComboBox"
            item-text="name"
            item-value="id"
            class="mt-5"
            append-icon="mdi-arrow-down"
            :items="state.parentItems"
            label="Parent Category"
            placeholder="Select a parent category"
            outlined
          ></v-combobox>
        </v-row>
        <v-row class="btn-group">
          <client-only>
            <div v-if="!state.activeTreeNode" style="width: 100%">
              <v-btn
                :disabled="!state.valid"
                class="mt-1"
                block
                color="success"
                @click="validate"
              >
                Add
              </v-btn>
            </div>
            <div v-else style="width: 100%">
              <v-btn
                :disabled="!state.valid"
                class=""
                block
                color="primary"
                @click="updateCategory"
              >
                Update
              </v-btn>
              <v-btn
                :disabled="!state.valid"
                class="mt-4"
                block
                color="error"
                @click="deleteCategory"
              >
                Delete
              </v-btn>
            </div>
          </client-only>
        </v-row>
      </v-form>
    </v-col>
    <v-col cols="12" lg="6">
      <v-card class="py-4 px-3 my-card">
        <v-skeleton-loader
          v-if="!state.items"
          v-bind="state.attrs"
          type="table-heading, list-item-two-line, image, table-tfoot"
        ></v-skeleton-loader>
        <v-treeview
          v-else
          class="ml-5"
          hoverable
          :items="state.items"
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
      items: null as null | ICategoryTreeItem[],
      parentItems: [] as ICategory[],
      activeTreeNode: null as null | number,
      selectedParent: null as ICategory | null | undefined,
      modalParentComboBox: {
        id: -1,
        name: 'No Parent',
        parent: null,
        disabled: false
      } as null | undefined | ICategory,
      attrs: {
        class: '',
        boilerplate: true,
        elevation: 2
      }
    })

    const categoryName = ref('')

    const { $axios } = useContext()

    const updateCategory = async () => {
      context.refs.form.validate()
      if (!state.valid || !state.activeTreeNode) {
        return
      }
      const activeNode = state.categories.find(
        c => c.id === state.activeTreeNode
      )

      if (!activeNode) {
        return
      }

      const putData = {
        name: categoryName.value,
        cid: activeNode?.cid,
        id: activeNode.id
      }

      try {
        const response = await $axios.put('admin/PostCategory', putData)
        const { status } = response
        if (status === 200) {
          await getAllCategories()
        }
      } catch (error) {
        console.error(error)
      }
    }

    const deleteCategory = async () => {
      context.refs.form.validate()
      if (!state.valid || !state.activeTreeNode) {
        return
      }
      const activeNode = state.categories.find(
        c => c.id === state.activeTreeNode
      )

      if (!activeNode) {
        return
      }

      const deleteData = {
        cid: activeNode?.cid,
        id: activeNode.id
      }

      try {
        const req = {
          url: 'admin/PostCategory',
          method: 'delete',
          data: deleteData
        }
        // @ts-ignore
        const response = await $axios.request(req)
        const { status } = response
        if (status === 200) {
          categoryName.value = ''
          state.activeTreeNode = null
          await getAllCategories()
        }
      } catch (error) {
        console.error(error)
      }
    }

    const onTreeActive = (val: number[]) => {
      state.activeTreeNode = val.length > 0 ? val[0] : null
      if (state.activeTreeNode) {
        const activeNode = state.categories.find(
          c => c.id === state.activeTreeNode
        )
        if (activeNode) {
          const parentNode = state.categories.find(
            category => category.id === activeNode.parent
          )
          categoryName.value = activeNode.name
          if (parentNode) {
            state.modalParentComboBox = parentNode
          } else {
            state.modalParentComboBox = state.parentItems.find(c => c.id === -1)
          }
        }
        return
      }
      state.modalParentComboBox = null
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
          state.modalParentComboBox === null ||
          state.modalParentComboBox === undefined ||
          (state.modalParentComboBox && state.modalParentComboBox.id === -1)
            ? null
            : state.modalParentComboBox.id
      }
      try {
        const response = await $axios.post('admin/PostCategory', postData)
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

    return {
      state,
      onTreeActive,
      categoryName,
      validate,
      updateCategory,
      deleteCategory
    }
  }
})
</script>

<style lang="scss" scoped>
.my-card {
  min-height: 400px;
}
.btn-group {
  margin-top: 35px;
  padding-left: 10px;
  padding-right: 10px;
}
.left-side {
  margin: 0;
  padding: 30px 40px 30px 30px;
}
</style>
