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
          <v-dialog
            v-model="state.addNewDialog"
            width="500"
          >
            <template #activator="{ on, attrs }">
              <v-btn
                color="primary mr-2"
                depressed
                v-bind="attrs"
                v-on="on"
              >
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
                  <v-btn
                    depressed
                    @click="closeDialog"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    depressed
                    color="primary"
                    @click="addNewQuestion"
                  >
                    Add
                  </v-btn>
                </div>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-btn depressed color="success">
            Update
          </v-btn>
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12">
      <v-data-table
        :headers="state.headers"
        :items="state.questions"
        :items-per-page="5"
        sort-by="order"
        class="elevation-1 w-100"
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
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { defineComponent, reactive } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const state = reactive({
      isEditable: false,
      addNewDialog: false,
      new: {
        id: '',
        order: 0,
        weight: 1,
        question: '',
        active: true
      },
      headers: [
        { text: 'Order', value: 'order', align: 'start', width: '10%' },
        { text: 'Question', value: 'question', sortable: false, width: '65%' },
        { text: 'Weight', value: 'weight', sortable: false, width: '10%' },
        { text: 'Turn on / off', value: 'active', width: '15%' }
      ],
      questions: [
        {
          id: '84d9cfc2f395ce883a41d7ffc1bbcf4e',
          order: 1,
          question: 'How accurate the service with the description?',
          weight: 4.0,
          active: true
        },
        {
          id: 'be1dc59314521c5658f5b67182f72d12',
          order: 2,
          question: 'Is the seller expert in the job?',
          weight: 6.5,
          active: false
        },
        {
          id: 'ad6af03f309dd0dd082d633d110fc0cd',
          order: 3,
          question: 'Is the seller professional?',
          weight: 2.0,
          active: true
        },
        {
          id: 'b807023f87e63b8ada92f79f546ff9cc',
          order: 4,
          question: 'Is the seller work on-time?',
          weight: 7.0,
          active: true
        },
        {
          id: '6b744d3016dd0c83e23be5b939dd717a',
          order: 5,
          question: 'Is the seller friendly?',
          weight: 3.5,
          active: false
        },
        {
          id: '4c35abffe1cec5e9b16189fc0ebff34e',
          order: 6,
          question: 'Did the seller help to meet your requirements?',
          weight: 5.5,
          active: true
        }
      ]
    })

    const closeDialog = () => {
      state.addNewDialog = false
    }

    const addNewQuestion = () => {
      state.questions.push(state.new)
      closeDialog()
    }

    return {
      state,
      addNewQuestion,
      closeDialog
    }
  }
})
</script>
