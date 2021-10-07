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
          <v-dialog v-model="state.dialogReject" max-width="500px">
            <v-card>
              <v-card-title class="text-h5">
                Are you sure you want to reject this post?
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeReject">
                  No
                </v-btn>
                <v-btn color="blue darken-1" text @click="rejectItemConfirm">
                  Yes
                </v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="state.dialogApprove" max-width="520px">
            <v-card>
              <v-card-title class="text-h5">
                Are you sure you want to approve this post?
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeApprove">
                  No
                </v-btn>
                <v-btn color="blue darken-1" text @click="approveItemConfirm">
                  Yes
                </v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template #item.actions="{ item }">
        <v-icon small class="mr-2" @click="approvePost(item)">
          mdi-check-bold
        </v-icon>
        <v-icon small @click="rejectPost(item)"> mdi-close-thick </v-icon>
      </template>
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
  useContext,
  nextTick
} from '@nuxtjs/composition-api'
import { IPost } from '~/interfaces/post'

export default defineComponent({
  setup() {
    const state = reactive({
      dialog: false,
      dialogApprove: false,
      dialogReject: false,
      headers: [
        {
          text: 'Post Image',
          align: 'start',
          sortable: false,
          value: 'postImage'
        },
        {
          text: 'Post Type',
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

    const approveItemConfirm = () => {
      closeApprove()
    }

    const closeApprove = () => {
      state.dialogApprove = false
      nextTick(() => {
        //
      })
    }

    const rejectItemConfirm = () => {
      closeApprove()
    }

    const closeReject = () => {
      state.dialogReject = false
      nextTick(() => {
        //
      })
    }

    const approvePost = () => {
      state.dialogApprove = true
    }

    const rejectPost = () => {
      state.dialogReject = true
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
      approveItemConfirm,
      closeApprove,
      getPosts,
      closeReject,
      rejectItemConfirm,
      rejectPost,
      approvePost
    }
  }
})
</script>
