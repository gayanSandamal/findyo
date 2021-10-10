<template>
  <div>
    <v-data-table
      :headers="state.headers"
      :items="tableData"
      sort-by="calories"
      class="elevation-1"
      :loading="setLoading"
      loading-text="Loading... Please wait"
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
          <v-dialog v-model="state.dialogPreview" max-width="600px">
            <v-card>
              <v-card-title> Post Details </v-card-title>
              <v-card-text>
                <img
                  style="width: 100%"
                  class="mt-3 pointer"
                  :src="setImage(item)"
                  @click="openPostPreview(item)"
                />
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn
                    rounded
                    medium
                    color="success"
                    class="mr-3"
                    @click="approvePostFromPreview"
                  >
                    Approve
                  </v-btn>
                  <v-btn
                    rounded
                    medium
                    color="error"
                    @click="rejectPostFromPreview"
                  >
                    Reject
                  </v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card-text>
              <v-card-actions>
                <v-btn color="primary" text @click="closePostPreview">
                  Close
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template #item.actions="{ item }">
        <v-btn
          :disabled="item.verified ? true : false"
          rounded
          depressed
          small
          color="success"
          class="mr-3"
          @click="approvePost(item)"
        >
          Approve
        </v-btn>
        <v-btn
          :disabled="!item.verified ? true : false"
          rounded
          depressed
          small
          color="error"
          @click="rejectPost(item)"
        >
          Reject
        </v-btn>
      </template>
      <template #no-data>
        <v-btn color="primary" @click="getPosts"> Reset </v-btn>
      </template>
      <template #item.postImage="{ item }">
        <img
          style="cursor: pointer; width: 125px"
          class="mt-3 pointer"
          :src="setImage(item)"
          @click="openPostPreview(item)"
        />
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
  computed
} from '@nuxtjs/composition-api'
import { IPost } from '~/interfaces/post'

export default defineComponent({
  setup() {
    const state = reactive({
      dialog: false,
      dialogApprove: false,
      dialogReject: false,
      dialogPreview: false,
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
        {
          text: 'Post Content',
          value: 'postContentShort',
          sortable: false,
          width: '400'
        },
        { text: 'Actions', value: 'actions', sortable: false, width: '250' }
      ],
      desserts: [] as any,
      tableData: [] as any,
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
      posts: null as null | undefined | IPost[],
      selectedItem: null as any
    })

    const { $axios } = useContext()

    const approveItemConfirm = async () => {
      if (state.selectedItem) {
        const apporveData = {
          id: state.selectedItem.id,
          cid: state.selectedItem.cid,
          verified: true,
          user_id: state.selectedItem.userId,
          like_count: state.selectedItem.likeCount,
          likes: state.selectedItem.likes,
          share_count: state.selectedItem.shareCount,
          shares: state.selectedItem.shares,
          post_type: state.selectedItem.postType
        }

        try {
          const response = await $axios.put('posts', apporveData)
          const { status } = response
          if (status === 200 && state.posts) {
            state.posts.forEach((post: IPost) => {
              if (post.id === state.selectedItem.id) {
                post.verified = true
              }
            })
          }
        } catch (error) {
          console.error(error)
        }
      }
      closePostPreview()
      closeApprove()
    }

    const rejectItemConfirm = async () => {
      if (state.selectedItem) {
        const apporveData = {
          id: state.selectedItem.id,
          cid: state.selectedItem.cid,
          verified: false,
          user_id: state.selectedItem.userId,
          like_count: state.selectedItem.likeCount,
          likes: state.selectedItem.likes,
          share_count: state.selectedItem.shareCount,
          shares: state.selectedItem.shares,
          post_type: state.selectedItem.postType
        }

        try {
          const response = await $axios.put('posts', apporveData)
          const { status } = response
          if (status === 200 && state.posts) {
            state.posts.forEach((post: IPost) => {
              if (post.id === state.selectedItem.id) {
                post.verified = false
              }
            })
          }
        } catch (error) {
          console.error(error)
        }
      }
      closePostPreview()
      closeReject()
    }

    const closeApprove = () => {
      state.dialogApprove = false
    }

    const closeReject = () => {
      state.dialogReject = false
    }

    const approvePost = (item: any) => {
      state.selectedItem = item
      state.dialogApprove = true
    }

    const rejectPost = (item: any) => {
      state.selectedItem = item
      state.dialogReject = true
    }

    const setImage = (_: any) => {
      const image = 'https://picsum.photos/1280/720'
      return image
    }

    const openPostPreview = (item: any) => {
      state.selectedItem = item
      state.dialogPreview = true
    }

    const closePostPreview = () => {
      state.dialogPreview = false
    }

    const approvePostFromPreview = () => {
      state.dialogApprove = true
    }

    const rejectPostFromPreview = () => {
      state.dialogReject = true
    }

    const setLoading = computed(() => {
      let loading = true

      if (tableData && tableData.value.length > 0) {
        loading = false
      }

      return loading
    })

    const tableData = computed(() => {
      let data: any = []
      if (state.posts && state.posts.length > 0) {
        data = state.posts.map((post: IPost) => {
          return {
            ...post,
            postContentShort: post.postContent?.substring(0, 150),
            location: 'location',
            category: 'category',
            varifiedText: post.verified ? 'Verified' : 'Not verified'
          }
        })
      }
      return data
    })

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
      approvePost,
      setLoading,
      tableData,
      setImage,
      openPostPreview,
      closePostPreview,
      rejectPostFromPreview,
      approvePostFromPreview
    }
  }
})
</script>
