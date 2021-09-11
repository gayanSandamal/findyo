<template>
  <div class="my-5">
    <v-card elevation="2" outlined>
      <v-tabs>
        <v-tab>Manage Locations</v-tab>
        <v-tab>Manage Levels</v-tab>
        <v-tab-item>
          <v-container fluid>
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
          </v-container>
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
  setup(_, context) {
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

    const onMount = async () => {
      await getAllLevels()
      await getAllLocations()
      // console.log('allLocations = ', state.allLocations)
      // console.log('allLocationLevels = ', state.allLocationLevels)
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
