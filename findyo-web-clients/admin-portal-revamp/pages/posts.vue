<template>
  <div>
    <v-data-table
      :headers="state.headers"
      :items="state.tabelData"
      sort-by="calories"
      class="elevation-1"
    >
      <template #top>
        <v-toolbar flat>
          <v-toolbar-title>All Posts</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-dialog v-model="state.dialog" max-width="500px">
            <!-- <template v-slot:activator="{ on, state.attrs }">
              <v-btn
                color="primary"
                dark
                class="mb-2"
                v-bind="state.attrs"
                v-on="on"
              >
                New Item
              </v-btn>
            </template> -->
            <v-card>
              <v-card-title>
                <span class="text-h5">{{ state.formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="state.editedItem.name"
                        label="Dessert name"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="state.editedItem.calories"
                        label="Calories"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="state.editedItem.fat"
                        label="Fat (g)"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="state.editedItem.carbs"
                        label="Carbs (g)"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="state.editedItem.protein"
                        label="Protein (g)"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">
                  Cancel
                </v-btn>
                <v-btn color="blue darken-1" text @click="save"> Save </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="state.dialogDelete" max-width="500px">
            <v-card>
              <v-card-title class="text-h5">
                Are you sure you want to delete this item?
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeDelete">
                  Cancel
                </v-btn>
                <v-btn color="blue darken-1" text @click="deleteItemConfirm">
                  OK
                </v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <!-- <template v-slot:item.actions="state.item">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
        <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
      </template> -->
      <template #no-data>
        <v-btn color="primary" @click="getPosts"> Reset </v-btn>
      </template>
    </v-data-table>
  </div>
</template>

<script lang="ts">
import {
  defineComponent,
  onMounted,
  reactive,
  useContext
} from '@nuxtjs/composition-api'
import { IPost } from '~/interfaces/post'

export default defineComponent({
  setup(_, context: any) {
    const state = reactive({
      dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: 'Post Type',
          align: 'start',
          sortable: false,
          value: 'postType'
        },
        { text: 'Verified', value: 'varifiedText', sortable: false },
        { text: 'Location', value: 'location', sortable: false },
        { text: 'Category', value: 'category', sortable: false },
        { text: 'Post Content', value: 'postContent', sortable: false },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      desserts: [] as any,
      tabelData: [] as any,
      editedIndex: -1,
      editedItem: {
        name: '',
        calories: 0,
        fat: 0,
        carbs: 0,
        protein: 0
      },
      defaultItem: {
        name: '',
        calories: 0,
        fat: 0,
        carbs: 0,
        protein: 0
      },
      posts: null as null | undefined | IPost[]
    })

    const { $axios } = useContext()

    const editItem = (item: any) => {
      state.editedIndex = state.desserts.indexOf(item)
      state.editedItem = Object.assign({}, item)
      state.dialog = true
    }

    const deleteItem = (item: any) => {
      state.editedIndex = state.desserts.indexOf(item)
      state.editedItem = Object.assign({}, item)
      state.dialogDelete = true
    }

    const deleteItemConfirm = () => {
      state.desserts.splice(state.editedIndex, 1)
      closeDelete()
    }

    const close = () => {
      state.dialog = false
      context.nextTick(() => {
        state.editedItem = Object.assign({}, state.defaultItem)
        state.editedIndex = -1
      })
    }

    const closeDelete = () => {
      state.dialogDelete = false
      context.nextTick(() => {
        state.editedItem = Object.assign({}, state.defaultItem)
        state.editedIndex = -1
      })
    }

    const save = () => {
      if (state.editedIndex > -1) {
        Object.assign(state.desserts[state.editedIndex], state.editedItem)
      } else {
        state.desserts.push(state.editedItem)
      }
      close()
    }

    const getPosts = async () => {
      try {
        const response = await $axios.get('posts')
        const {
          status,
          data: { data }
        } = response

        if (status === 200) {
          state.posts = data.map((d: any) => {
            return {
              id: d.id,
              verified: d.verified,
              likeCount: d.like_count,
              likes: d.likes,
              shareCount: d.share_count,
              shares: d.shares,
              userId: d.user_id,
              categoryId: d.category_id,
              locationId: d.location_id,
              postContent: d.post_content,
              postType: d.post_type,
              cid: d.cid
            }
          })
        }

        if (state.posts && state.posts.length > 0) {
          state.tabelData = state.posts.map((post: IPost) => {
            return {
              ...post,
              location: 'location',
              category: 'category',
              varifiedText: post.verified ? 'Verified' : 'Not verified'
            }
          })
        }
      } catch (error) {
        console.error(error)
      }
    }

    const onMount = async () => {
      await getPosts()
    }

    onMounted(onMount)

    return {
      state,
      save,
      deleteItemConfirm,
      deleteItem,
      editItem,
      close,
      closeDelete
    }
  }
})
</script>
