<template>
  <v-row class="mt-5">
    <v-col cols="12" lg="6" class="left-side">
      <h2 v-if="!state.activeTreeNode" class="mb-5 pb-5">Add new location</h2>
      <h2 v-else class="mb-5 pb-5">Update / Delete Locations</h2>
      <v-form ref="form" v-model="state.valid" class="pt-3">
        <v-row class="px-3">
          <v-text-field
            v-model="locationName"
            :label="!state.activeTreeNode ? 'Add Location' : 'Update Location'"
            placeholder="Enter loaction name here"
            :rules="state.locationNameRules"
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
            label="Parent Location (optional)"
            placeholder="Select a parent location"
            outlined
          ></v-combobox>
        </v-row>
        <v-row class="px-3">
          <v-combobox
            v-model="state.modalLocationLevelComboBox"
            item-text="name"
            item-value="id"
            class="mt-5"
            append-icon="mdi-arrow-down"
            :items="state.locationLevels"
            label="Location Level (optional)"
            placeholder="Select a location level"
            outlined
          ></v-combobox>
        </v-row>
        <v-row class="btn-group">
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
              @click="updateLocation"
            >
              Update
            </v-btn>
            <v-btn
              :disabled="!state.valid"
              class="mt-4"
              block
              color="error"
              @click="deleteLocation"
            >
              Delete
            </v-btn>
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
  reactive,
  onMounted,
  useContext,
  ref
} from '@nuxtjs/composition-api'

import type {
  ILocation,
  ILocationLevel,
  ILocationTreeItem
} from '@/interfaces/location'

export default defineComponent({
  setup(_, context: any) {
    const { $axios } = useContext()
    const state = reactive({
      locations: [] as ILocation[],
      attrs: {
        class: '',
        boilerplate: true,
        elevation: 2
      },
      modalParentComboBox: {
        id: -1,
        name: 'No Parent',
        parent: null,
        locationLevelId: null,
        disabled: false
      } as null | undefined | ILocation,
      modalLocationLevelComboBox: {
        id: -1,
        name: 'No Location Level',
        cid: null,
        disabled: false
      } as null | undefined | ILocationLevel,
      valid: false,
      activeTreeNode: null as null | number,
      parentItems: [] as ILocation[],
      locationLevels: [] as null | undefined | ILocationLevel[],
      items: null as null | ILocationTreeItem[],
      locationNameRules: [(v: string) => !!v || 'Category name is required']
    })

    const selectedLevel = ref(null)
    const locationName = ref('')

    const getAllLocations = async () => {
      try {
        const response = await $axios.get('locations')
        const { status, data } = response
        if (status === 200) {
          state.locations = data.map((d: any) => {
            return {
              id: d.id,
              originalName: d.name,
              parent: d.parent,
              cid: d.cid,
              locationLevelId: d.location_level_id,
              name: setNameWithLocationLevel(d.name, d.location_level_id)
            }
          })
          const mappedData: ILocation[] = data.map((d: ILocation) => {
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
          state.items = nest(state.locations)
        }
      } catch (error) {
        console.error(error)
      }
    }

    const setNameWithLocationLevel = (
      name: string,
      levelId: number
    ): string => {
      if (!levelId) {
        if (name) {
          return name
        } else {
          return ''
        }
      }
      let nameWithLevel = name
      let selectedLevel = null

      if (state.locationLevels) {
        selectedLevel = state.locationLevels.find(l => l.id === levelId)
      }
      if (selectedLevel) {
        nameWithLevel = `${name} ${selectedLevel.name}`
      }

      return nameWithLevel
    }

    const getAllLevels = async () => {
      try {
        const response = await $axios.get('admin/Locationlevel')
        const { status, data } = response
        if (status === 200) {
          state.locationLevels = data.map((d: ILocationLevel) => {
            return {
              ...d,
              disabled: false
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
      items: ILocation[],
      id: number | null = null,
      link: string = 'parent'
    ): ILocationTreeItem[] =>
      items
        .filter((item: any) => item[link] === id)
        .map((item: ILocation) => ({ ...item, children: nest(items, item.id) }))

    const validate = () => {
      context.refs.form.validate()
      if (state.valid) {
        saveLocation()
      }
    }

    const onTreeActive = (val: number[]) => {
      state.activeTreeNode = val.length > 0 ? val[0] : null
      if (state.activeTreeNode) {
        const activeNode = state.locations.find(
          c => c.id === state.activeTreeNode
        )
        if (activeNode) {
          const selectedLevel = state.locationLevels?.find(
            lev => lev.id === activeNode.locationLevelId
          )
          if (selectedLevel) {
            state.modalLocationLevelComboBox = selectedLevel
          }

          const parentNode = state.locations.find(
            category => category.id === activeNode.parent
          )
          locationName.value = activeNode.name
          if (parentNode) {
            state.modalParentComboBox = parentNode
          } else {
            state.modalParentComboBox = state.parentItems.find(c => c.id === -1)
          }
        }
        return
      }
      state.modalParentComboBox = null
      state.modalLocationLevelComboBox = null
      locationName.value = ''
    }

    const saveLocation = async () => {
      const postData = {
        name: locationName.value,
        parent:
          state.modalParentComboBox === null ||
          state.modalParentComboBox === undefined ||
          (state.modalParentComboBox && state.modalParentComboBox.id === -1)
            ? null
            : state.modalParentComboBox.id,
        location_level_id:
          state.modalLocationLevelComboBox === null ||
          state.modalLocationLevelComboBox === undefined ||
          (state.modalLocationLevelComboBox &&
            state.modalLocationLevelComboBox.id === -1)
            ? null
            : state.modalLocationLevelComboBox.id
      }
      try {
        const response = await $axios.post('admin/location', postData)
        const { status } = response
        if (status === 200) {
          locationName.value = ''
          state.modalLocationLevelComboBox = null
          state.modalParentComboBox = null
          await getAllLocations()
        }
      } catch (error) {
        console.error(error)
      }
    }

    const updateLocation = async () => {
      context.refs.form.validate()
      if (!state.valid || !state.activeTreeNode) {
        return
      }
      const activeNode = state.locations.find(
        c => c.id === state.activeTreeNode
      )

      if (!activeNode) {
        return
      }
      const putData = {
        name: locationName.value,
        cid: activeNode.cid,
        id: activeNode.id,
        parent: activeNode.parent,
        location_level_id: activeNode.locationLevelId
      }

      try {
        const response = await $axios.put('admin/location', putData)
        const { status } = response
        if (status === 200) {
          await getAllLocations()
        }
      } catch (error) {
        console.error(error)
      }
    }

    const deleteLocation = async () => {
      context.refs.form.validate()
      if (!state.valid || !state.activeTreeNode) {
        return
      }
      const activeNode = state.locations.find(
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
          url: 'admin/location',
          method: 'delete',
          data: deleteData
        }
        // @ts-ignore
        const response = await $axios.request(req)
        const { status } = response
        if (status === 200) {
          locationName.value = ''
          state.activeTreeNode = null
          await getAllLocations()
        }
      } catch (error) {
        console.error(error)
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
      onSelectLevel,
      locationName,
      validate,
      onTreeActive,
      deleteLocation,
      updateLocation
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
  min-height: 65vh;
}
</style>
