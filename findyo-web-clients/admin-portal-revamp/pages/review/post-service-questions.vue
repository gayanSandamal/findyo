<template>
  <v-row justify="center" align="center" class="mt-0">
    <v-col cols="12">
      <v-row>
        <v-col cols="8">
          <v-switch
            v-model="state.isEditable"
            class="mt-0"
            :label="`Edit mode ${state.isEditable ? 'on' : 'off'}`"
          ></v-switch>
        </v-col>
        <v-col cols="4" class="d-flex justify-end">
          <v-dialog v-model="state.addNewDialog" width="500">
            <template #activator="{ on, attrs }">
              <v-btn color="primary mr-2" depressed v-bind="attrs" v-on="on">
                Add new
              </v-btn>
            </template>
            <v-card>
              <v-card-title class="text-h5 grey lighten-2">
                Add new review question
              </v-card-title>
              <v-card-text class="pt-4">
                <v-row>
                  <v-col cols="6">
                    <v-text-field
                      v-model="state.new.order"
                      :hide-details="true"
                      type="number"
                      label="Order"
                      filled
                      disabled
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col>
                    <v-text-field
                      v-model="state.new.weight"
                      :hide-details="true"
                      type="number"
                      label="Weight"
                      filled
                      dense
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-textarea
                      v-model="state.new.question"
                      :hide-details="true"
                      label="Question"
                      filled
                    ></v-textarea>
                  </v-col>
                </v-row>
              </v-card-text>
              <v-divider></v-divider>
              <v-card-actions class="py-4">
                <v-spacer></v-spacer>
                <div class="d-flex justify-space-between w-100 px-2">
                  <v-btn depressed @click="closeDialog"> Cancel </v-btn>
                  <v-btn depressed color="primary" @click="addNewQuestion">
                    Add
                  </v-btn>
                </div>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-btn depressed color="success"> Update </v-btn>
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12">
      <client-only>
        <v-data-table
          :headers="state.headers"
          :items="state.questions"
          :items-per-page="10"
          sort-by="order"
          class="elevation-1 w-100"
          :loading="setLoading"
        >
          <template #[`item.order`]="{ item }">
            <v-text-field
              v-if="state.isEditable"
              v-model="item.order"
              :hide-details="true"
              type="number"
              dense
            ></v-text-field>
            <p v-else class="mb-0" v-text="item.order"></p>
          </template>
          <template #[`item.question`]="{ item }">
            <v-text-field
              v-if="state.isEditable"
              v-model="item.question"
              :hide-details="true"
              dense
            ></v-text-field>
            <p v-else class="mb-0" v-text="item.question"></p>
          </template>
          <template #[`item.weight`]="{ item }">
            <v-text-field
              v-if="state.isEditable"
              v-model="item.weight"
              :hide-details="true"
              type="number"
              dense
            ></v-text-field>
            <p v-else class="mb-0" v-text="item.weight"></p>
          </template>
          <template #[`item.active`]="{ item }">
            <v-switch
              v-model="item.active"
              :disabled="!state.isEditable"
            ></v-switch>
          </template>
          <template #[`item.action`]="{ item }">
            <v-btn
              class="mx-2"
              fab
              dark
              small
              color="error"
              @click="deleteQuestion(item)"
            >
              <v-icon dark>
                mdi-delete
              </v-icon>
            </v-btn>
          </template>
        </v-data-table>
        <v-dialog v-model="state.dialogDelete" max-width="550px">
          <v-card>
            <v-card-title class="text-h5">
              Are you sure you want to delete this question?
            </v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">
                No
              </v-btn>
              <v-btn color="blue darken-1" text @click="deleteConfirm">
                Yes
              </v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </client-only>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import {
  defineComponent,
  reactive,
  onMounted,
  useContext,
  computed
} from '@nuxtjs/composition-api'
import { IRating, IRatingHeader } from '@/interfaces/rating'

export default defineComponent({
  setup() {
    const state = reactive({
      isEditable: false,
      addNewDialog: false,
      new: {
        id: -1,
        order: 0,
        weight: 1,
        question: '',
        active: 1
      } as IRating,
      headers: [
        { text: 'Order', value: 'order', align: 'start', width: '10%' },
        { text: 'Question', value: 'question', sortable: false, width: '60%' },
        { text: 'Weight', value: 'weight', sortable: false, width: '10%' },
        { text: 'Turn on / off', value: 'active', width: '10%' },
        { text: 'Actions', value: 'action', width: '10%' }
      ] as IRatingHeader[],
      questions: [] as IRating[],
      selectedItem: null as IRating | null,
      dialogDelete: false
    })

    const { $axios } = useContext()

    const setLoading = computed(() => {
      let loading = true

      if (state.questions && state.questions.length > 0) {
        loading = false
      }

      return loading
    })

    const closeDialog = () => {
      state.addNewDialog = false
    }

    const getMaxOfArray = (numArray: Array<number>) : number => {
      return Math.max.apply(null, numArray)
    }

    const deleteQuestion = (question: IRating) => {
      state.selectedItem = question
      state.dialogDelete = true
    }

    const closeDelete = () => {
      state.selectedItem = null
      state.dialogDelete = false
    }

    const deleteConfirm = async () => {
      try {
        if (!state.selectedItem) {
          state.dialogDelete = false
          return
        }
        const req = {
          url: 'admin/adminrating',
          method: 'delete',
          data: {
            cid: state.selectedItem?.cid,
            id: state.selectedItem?.id
          }
        }
        // @ts-ignore
        const response = await $axios.request(req)
        const { status } = response
        if (status === 200) {
          state.questions = state.questions.filter(q => q.id !== state.selectedItem?.id)
          state.selectedItem = null
          state.dialogDelete = false
        }
      } catch (error) {
        console.error(error)
      }
    }

    const addNewQuestion = async () => {
      try {
        state.new.order = getMaxOfArray(state.questions.map(({ order }) => order)) + 1
        const response: any = await $axios.post('admin/adminrating', state.new)
        const { status, data } = response
        if (status === 200) {
          state.new.weight = (Math.round(parseFloat(state.new.weight.toString()) * 100) / 100).toFixed(2)
          state.new.cid = data.cid
          state.new.id = data.id
          state.questions.push(state.new)
        }
      } catch (error) {
        console.error(error)
      }
      closeDialog()
    }

    const getAllRatings = async () => {
      try {
        const response: any = await $axios.get('admin/adminrating')
        const { data, status } = response
        if (status === 200) {
          state.questions = [...data]
        }
      } catch (error) {
        console.error(error)
      }
    }

    const mounted = async () => {
      await getAllRatings()
    }

    onMounted(mounted)

    return {
      state,
      addNewQuestion,
      closeDialog,
      setLoading,
      deleteQuestion,
      closeDelete,
      deleteConfirm
    }
  }
})
</script>
