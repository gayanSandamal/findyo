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
              <v-card-text>
                <v-radio-group v-model="state.rejectReason" column>
                  <v-radio
                    v-for="(reason, index) in state.rejectReasons"
                    :key="index"
                    :label="reason.text"
                    :value="reason.value"
                  ></v-radio>
                  <v-textarea
                    v-if="state.rejectReason && state.rejectReason === -1"
                    v-model="state.otherRejectReason"
                    solo
                    name="input-reason"
                    label="please provide reason to reject this post"
                  ></v-textarea>
                </v-radio-group>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="closeReject">
                  No
                </v-btn>
                <v-btn
                  :disabled="isRejectButtonDisabled"
                  color="blue darken-1"
                  text
                  @click="rejectItemConfirm"
                >
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
            <v-card v-if="state.selectedItem">
              <v-card-title> Post Details </v-card-title>
              <v-card-text>
                <img
                  style="width: 100%"
                  class="mt-3 pointer"
                  :src="setImage(item)"
                  @click="openPostPreview(item)"
                />
                <div class="my-5 pt-2 pl-1">
                  <section>
                    <v-row>
                      <v-col cols="4">
                        <h2 class="mt-3">Post Type:</h2>
                        <h2 class="mt-3">Varified:</h2>
                        <h2 class="mt-3">Location:</h2>
                        <h2 class="mt-3">Category:</h2>
                        <h2 class="mt-3">Post Content:</h2>
                      </v-col>
                      <v-col>
                        <h2 class="mt-3">
                          {{ state.selectedItem.postType }}
                        </h2>
                        <h2 class="mt-3">
                          {{ state.selectedItem.verifiedText }}
                        </h2>
                        <h2 class="mt-3">
                          {{ state.selectedItem.location }}
                        </h2>
                        <h2 class="mt-3">
                          {{ state.selectedItem.category }}
                        </h2>
                        <h2 class="mt-3">
                          {{ state.selectedItem.postContent }}
                        </h2>
                      </v-col>
                    </v-row>
                  </section>
                </div>
                <v-card-actions class="pt-5">
                  <v-spacer></v-spacer>
                  <v-btn
                    :disabled="state.selectedItem.verified ? true : false"
                    rounded
                    medium
                    color="success"
                    class="mr-3"
                    @click="approvePostFromPreview"
                  >
                    Approve
                  </v-btn>
                  <v-btn
                    :disabled="!state.selectedItem.verified ? true : false"
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
      <template #[`item.actions`]="{ item }">
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
      <template #[`item.postImage`]="{ item }">
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
import {
  IPost,
  IPostRejectReason,
  IPostTableItem,
  ITableHeaders
} from '@/interfaces/post'
import { ICategory } from '@/interfaces/category'
import type { ILocation, ILocationLevel } from '@/interfaces/location'

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
        { text: 'Verified', value: 'verifiedText', sortable: false },
        { text: 'Location', value: 'location', sortable: false },
        { text: 'Category', value: 'category', sortable: false },
        {
          text: 'Post Content',
          value: 'postContentShort',
          sortable: false,
          width: '400'
        },
        { text: 'Actions', value: 'actions', sortable: false, width: '250' }
      ] as ITableHeaders[],
      tableData: [] as IPostTableItem[],
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
      selectedItem: null as IPostTableItem | null | undefined,
      categories: [] as ICategory[],
      locationLevels: [] as ILocationLevel[],
      locations: [] as ILocation[],
      rejectReason: undefined as number | undefined | null,
      rejectReasons: [
        {
          value: 1,
          text: 'The description of this post doesn’t match with the given category.'
        },
        {
          value: 2,
          text: 'The description of this post doesn’t match with the image.'
        },
        {
          value: 3,
          text: 'The image of this post doesn’t match with the given category.'
        },
        { value: 4, text: 'This post contains contact numbers.' },
        { value: 5, text: 'This post contains violence.' },
        { value: 6, text: 'This post contains nudity.' },
        { value: -1, text: 'Others' }
      ] as IPostRejectReason[],
      otherRejectReason: null as string | null | undefined
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
              if (state.selectedItem && post.id === state.selectedItem.id) {
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
              if (state.selectedItem && post.id === state.selectedItem.id) {
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
      state.rejectReason = null
      state.otherRejectReason = null
    }

    const approvePost = (item: IPostTableItem) => {
      state.selectedItem = item
      state.dialogApprove = true
    }

    const rejectPost = (item: IPostTableItem) => {
      state.selectedItem = item
      state.dialogReject = true
    }

    const setImage = () => {
      // TODO: this should be fixed once we get the URL
      const image = 'https://picsum.photos/1280/720'
      return image
    }

    const openPostPreview = (item: IPostTableItem) => {
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

    const isRejectButtonDisabled = computed(() => {
      let disabled = false

      if (
        !state.rejectReason ||
        (state.rejectReason &&
          state.rejectReason === -1 &&
          (!state.otherRejectReason ||
            (state.otherRejectReason && state.otherRejectReason.trim() === '')))
      ) {
        disabled = true
      }

      return disabled
    })

    const tableData = computed(() => {
      let data: any = []
      if (state.posts && state.posts.length > 0) {
        data = state.posts.map((post: IPost) => {
          const location = state.locations.find(l => l.id === post.locationId)
          const category = state.categories.find(c => c.id === post.categoryId)
          return {
            ...post,
            postContentShort: post.postContent?.substring(0, 150),
            location: location ? location.name : 'no data',
            category: category ? category.name : 'no data',
            verifiedText: post.verified ? 'Verified' : 'Not verified'
          }
        })
      }
      return data
    })

    const getCategories = async () => {
      try {
        const response: any = await $axios.get('postcategory')
        const { status, data } = response
        if (status === 200) {
          state.categories = [...data]
        }
      } catch (error) {
        console.error(error)
      }
    }

    const getAllLevels = async () => {
      try {
        const response = await $axios.get('admin/Locationlevel')
        const { status, data } = response
        if (status === 200) {
          state.locationLevels = data
        }
      } catch (error) {
        console.error(error)
      }
    }

    const getLocations = async () => {
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
      await getAllLevels()
      await getLocations()
      await getCategories()
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
      approvePostFromPreview,
      isRejectButtonDisabled
    }
  }
})
</script>
