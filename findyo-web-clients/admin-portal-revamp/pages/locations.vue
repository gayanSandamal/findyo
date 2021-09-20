<template>
  <div class="my-5">
    <v-card elevation="2" outlined>
      <v-tabs>
        <v-tab>Manage Locations</v-tab>
        <v-tab>Manage Levels</v-tab>
        <v-tab-item>
          <!-- <v-container fluid>
            <v-card-title>Locations</v-card-title>
            <div class="mx-3 pb-4 text-subtitle-1 font-weight-medium">
              Add, edit or remove locations
            </div>
            <div class="mx-3 mt-5">
              <v-select
                v-model="selectedLevel"
                :items="state.allLocationLevels"
                :error-messages="[]"
                label="Select an option"
                required
                @change="onSelectLevel"
              ></v-select>
            </div>
          </v-container>
        </v-tab-item>
        <v-tab-item>
          <v-container fluid>
            <v-card-title>Levels</v-card-title>
            <div class="mx-3 text-subtitle-1 font-weight-medium">
              Add, edit or remove locations
            </div>
            <div class="mx-3"></div>
          </v-container> -->
          <v-row class="mt-5">
            <v-col cols="12" lg="6" class="left-side">
              <h3 v-if="!state.activeTreeNode" class="mb-5 pb-5">
                Add new location
              </h3>
              <h3 v-else class="mb-5 pb-5">Update / Delete Locations</h3>
              <v-form
                ref="form"
                v-model="state.valid"
                lazy-validation
                class="pt-3"
              >
                <v-row class="px-3">
                  <v-text-field
                    v-model="locationName"
                    label="Add Location"
                    placeholder="Enter new category name here"
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
                    label="Parent Location"
                    placeholder="Select a parent location"
                    outlined
                  ></v-combobox>
                </v-row>
                <v-row class="px-3">
                  <v-combobox
                    v-model="state.modalParentComboBox"
                    item-text="name"
                    item-value="id"
                    class="mt-5"
                    append-icon="mdi-arrow-down"
                    :items="state.parentItems"
                    label="Location Level"
                    placeholder="Select a location level"
                    outlined
                  ></v-combobox>
                </v-row>
                <v-row class="btn-group">
                  <div v-if="!state.activeTreeNode" style="width: 100%">
                    <v-btn class="mt-1" block color="success" @click="validate">
                      Add
                    </v-btn>
                  </div>
                  <div v-else style="width: 100%">
                    <v-btn
                      class=""
                      block
                      color="primary"
                      @click="updateCategory"
                      >Update</v-btn
                    >
                    <v-btn
                      class="mt-4"
                      block
                      color="error"
                      @click="deleteCategory"
                      >Delete</v-btn
                    >
                  </div>
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
        </v-tab-item>
      </v-tabs>
    </v-card>
  </div>
</template>

<script lang="ts">
import {
  defineComponent,
  reactive,
  onMounted,
  useContext,
  ref
} from '@nuxtjs/composition-api'
import { ILocation, ILocationLevel } from '@/interfaces/location'
export default defineComponent({
  setup() {
    const { $axios } = useContext()
    const state = reactive({
      allLocations: [] as ILocation[],
      allLocationLevels: [] as ILocationLevel[],
      items: ['Item 1', 'Item 2', 'Item 3', 'Item 4']
    })

    const selectedLevel = ref(null)

    const getAllLocations = async () => {
      try {
        const response = await $axios.get('locations')
        const {
          status,
          data: { data }
        } = response
        if (status === 200) {
          state.allLocations = data.map((d: any) => {
            return {
              id: d.id,
              name: d.name,
              parent: d.parent,
              cid: d.cid,
              locationLevelId: d.location_level_id
            }
          })
        }
      } catch (error) {
        console.error(error)
      }
    }

    const getAllLevels = async () => {
      try {
        const response = await $axios.get('admin/Locationlevel')
        const {
          status,
          data: { data }
        } = response
        if (status === 200) {
          state.allLocationLevels = data.map((d: ILocationLevel) => {
            return {
              ...d,
              text: d.name,
              disabled: false,
              value: d.id
            }
          })
        }
      } catch (error) {
        console.error(error)
      }
    }

    const onSelectLevel = (_: any) => {
      console.log(selectedLevel.value)
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

    const onMount = async () => {
      await getAllLevels()
      await getAllLocations()
    }

    onMounted(onMount)

    return {
      state,
      selectedLevel,
      onSelectLevel
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
