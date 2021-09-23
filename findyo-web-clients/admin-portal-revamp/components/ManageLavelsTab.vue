<template>
  <div>
    <v-row class="mt-5">
      <v-col cols="12" lg="6" class="left-side">
        <v-form ref="form" v-model="state.valid" class="pt-3">
          <v-text-field
            v-model="locationLevelName"
            label="Location Level Name"
            placeholder="Enter loaction name here"
            :rules="state.locationNameRules"
            outlined
          >
          </v-text-field>
          <v-row class="btn-group">
            <div v-if="!state.selectedItem" style="width: 100%">
              <v-btn
                :disabled="!state.valid"
                class="mt-4"
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
                @click="updateLocationLevel"
              >
                Update
              </v-btn>
              <v-btn
                :disabled="!state.valid"
                class="mt-4"
                block
                color="error"
                @click="deleteLocationLevel"
              >
                Delete
              </v-btn>
            </div>
          </v-row>
        </v-form>
      </v-col>
      <v-col cols="12" lg="6">
        <v-card class="py-4 pl-5 pr-3 my-card">
          <v-list v-if="state.locationLevels" flat>
            <v-subheader>Location Levels</v-subheader>
            <v-list-item-group v-model="state.selectedItem" color="primary">
              <v-list-item v-for="(item, i) in state.locationLevels" :key="i">
                <v-list-item-icon>
                  <v-icon v-text="'mdi-apple-keyboard-caps'"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title v-text="item.name"></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script lang="ts">
import {
  onMounted,
  useContext,
  defineComponent,
  reactive,
  ref
} from '@nuxtjs/composition-api'
import { ILocationLevel } from '~/interfaces/location'

export default defineComponent({
  setup(_, context: any) {
    const state = reactive({
      locationNameRules: [
        (v: string) => !!v || 'Location level name is required'
      ],
      valid: false,
      selectedItem: null as null | number,
      locationLevels: null as null | ILocationLevel[]
    })

    const { $axios } = useContext()

    const locationLevelName = ref('')

    const validate = () => {
      context.refs.form.validate()
      if (state.valid) {
        saveLocationLevel()
      }
    }

    const saveLocationLevel = async () => {}
    const updateLocationLevel = async () => {}
    const deleteLocationLevel = async () => {}

    const getAllLocationLevels = async () => {
      try {
        const response = await $axios.get('admin/Locationlevel')
        const {
          status,
          data: { data }
        } = response
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

    const onMount = async () => {
      await getAllLocationLevels()
    }

    onMounted(onMount)

    return {
      state,
      locationLevelName,
      validate,
      deleteLocationLevel,
      updateLocationLevel
    }
  }
})
</script>

<style lang="scss" scoped>
.my-card {
  min-height: 400px;
}
.btn-group {
  margin-top: 10px;
  padding-left: 10px;
  padding-right: 10px;
}
.left-side {
  margin: 0;
  padding: 30px 40px 30px 30px;
  min-height: 65vh;
}
</style>
