<template>
  <v-row class="mt-5">
    <v-col cols="12" lg="6">
      <v-row>
        <v-col>
          <v-text-field
            label="Add Category"
            placeholder="Enter new category name here"
            autofocus
          >
          </v-text-field>
        </v-col>
        <v-col cols="3">
          <v-btn class="mt-4" block clearable color="success">Add</v-btn>
        </v-col>
      </v-row>
      <v-select
        v-model="selectedParent"
        class="mt-3"
        append-icon="mdi-arrow-down"
        :items="state.parentItems"
        label="Parent Category"
        placeholder="Select a parent category"
        :change="onParentSelect"
      ></v-select>
    </v-col>
    <v-col cols="12" lg="6">
      <v-card class="py-4 px-3">
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
import {
  ICategory,
  ICategoryTreeItem,
  ICategoryComboItem
} from '@/interfaces/category'
export default defineComponent({
  setup() {
    const state = reactive({
      categories: [] as ICategory[],
      items: [
        {
          id: 5,
          name: 'Documents :',
          children: [
            {
              id: 6,
              name: 'vuetify :',
              children: [
                {
                  id: 7,
                  name: 'src :',
                  children: [
                    { id: 8, name: 'index : ts' },
                    { id: 9, name: 'bootstrap : ts' }
                  ]
                }
              ]
            },
            {
              id: 10,
              name: 'material2 :',
              children: [
                {
                  id: 11,
                  name: 'src :',
                  children: [
                    { id: 12, name: 'v-btn : ts' },
                    { id: 13, name: 'v-card : ts' },
                    { id: 14, name: 'v-window : ts' }
                  ]
                }
              ]
            }
          ]
        }
      ] as ICategoryTreeItem[],
      parentItems: [
        {
          id: -1,
          name: 'No Parent',
          parent: null,
          disabled: false,
          text: 'No Parent',
          value: -1
        }
      ] as ICategoryComboItem[]
    })

    const selectedParent = ref(null)

    const { $axios } = useContext()

    const onTreeActive = (val: number[]) => {
      // console.log(val[0])
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
          const mappedData: ICategoryComboItem[] = data.map((d: ICategory) => {
            return {
              ...d,
              disabled: false,
              text: d.name,
              value: d.id
            }
          })
          state.parentItems = [...state.parentItems, ...mappedData]

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
      //
    }

    const mounted = async () => {
      await getAllCategories()
    }

    onMounted(mounted)

    return { state, onParentSelect, selectedParent, onTreeActive }
  }
})
</script>
