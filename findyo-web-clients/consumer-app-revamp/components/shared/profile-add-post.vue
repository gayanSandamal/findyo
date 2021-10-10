<template>
  <div>
    <v-dialog v-model="state.postModal" width="500">
      <template #activator="{ on, attrs }">
        <v-card class="mx-auto">
          <v-card-actions>
            <v-list-item>
              <v-row class="d-flex justify-content-center">
                <v-col cols="2">
                  <v-list-item-avatar color="grey darken-3 m-0">
                    <v-img
                      class="elevation-6"
                      alt=""
                      src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
                    ></v-img>
                  </v-list-item-avatar>
                </v-col>
                <v-col cols="10" align-self="center">
                  <v-btn
                    block
                    rounded
                    depressed
                    class="text-transform-none justify-start font-weight-regular text--disabled"
                    v-bind="attrs"
                    v-on="on"
                  >
                    Publish your post
                  </v-btn>
                </v-col>
              </v-row>
            </v-list-item>
          </v-card-actions>
        </v-card>
      </template>
      <v-card class="mx-auto pt-4">
        <v-card-text class="pb-0">
          <v-textarea
            v-model="state.form.content"
            counter
            :rules="state.rules"
            :placeholder="state.form.postType === 'seller' ? 'Let the world know about your service' : 'Ask for the service you are looking for'"
            rows="4"
            filled
            auto-grow
            autofocus
          ></v-textarea>
          <p class="mb-0">
            Max {{ maxCharacters }} chatacters
          </p>
        </v-card-text>
        <v-card-actions>
          <div class="px-2 w-100">
            <p class="mb-2 grey--text">
              Select post type
            </p>
            <v-btn-toggle
              v-model="state.form.postType"
              rounded
              borderless
              color="success"
              class="px-1 w-100 mb-4"
            >
              <v-btn
                v-for="postType in state.postTypes"
                :key="postType.key"
                :value="postType.key"
                height="36"
                class="text-transform-none font-weight-regular w-50 mx-0"
              >
                {{ `I'm a ${postType.label}` }}
              </v-btn>
            </v-btn-toggle>
            <div class="mb-4">
              <p class="mb-2 grey--text">
                Select category and location
              </p>
              <v-chip
                v-if="selectedCategoryName"
                class="mr-4"
                color="light-blue lighten-4"
                close
                @click="state.categoriesModal = true"
                @click:close="removeSelectedCategory"
              >
                <v-icon left>
                  mdi-shape-plus
                </v-icon>
                {{ selectedCategoryName }}
              </v-chip>
              <v-btn
                v-else
                rounded
                depressed
                class="text-transform-none justify-start font-weight-regular text--disabled mr-4"
                :class="{'error-btn': state.isCategoryError}"
                @click="state.categoriesModal = true"
              >
                <v-icon left>
                  mdi-shape-plus
                </v-icon>
                Select category (required)
              </v-btn>
              <v-chip
                v-if="selectedLocationName"
                class="mr-4"
                color="teal lighten-4"
                close
                @click="state.locationsModal = true"
                @click:close="removeSelectedLocation"
              >
                <v-icon left>
                  mdi-map-marker
                </v-icon>
                {{ selectedLocationName }}
              </v-chip>
              <v-btn
                v-else
                rounded
                depressed
                class="text-transform-none justify-start font-weight-regular text--disabled mr-4"
                @click="state.locationsModal = true"
              >
                <v-icon left>
                  mdi-map-marker
                </v-icon>
                Select location
              </v-btn>
            </div>
            <v-list-item class="grow px-1">
              <v-list-item-avatar color="ml-0 mr-4 grey darken-3">
                <v-img
                  class="elevation-6"
                  alt=""
                  src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
                ></v-img>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title v-text="fullName"></v-list-item-title>
              </v-list-item-content>

              <v-row align="center" justify="end">
                <!-- <v-icon class="mr-1"> mdi-heart </v-icon>
                <span class="subheading mr-2">256</span>
                <span class="mr-1">·</span>
                <v-icon class="mr-1">
                  mdi-share-variant
                </v-icon>
                <span class="subheading">45</span> -->
                <v-btn
                  rounded
                  color="success"
                  dark
                  @click.stop="savePost"
                >
                  Publish
                </v-btn>
              </v-row>
            </v-list-item>
          </div>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="state.categoriesModal"
      width="400"
    >
      <v-card class="pt-4">
        <v-card-text class="flex-wrap">
          <v-text-field
            v-model="state.searchCategories"
            class="mx-2 mb-4"
            label="Search service category"
            autofocus
            hide-details
            clearable
          ></v-text-field>
          <div class="scroller categories">
            <v-treeview
              :key="state.categoryReRender"
              hoverable
              item-key="id"
              :items="state.nestedCategories"
              :search="state.searchCategories"
              :filter="categoriesFilter"
              :open="expandedCategoryIds"
              transition
              activatable
              open-on-click
              @update:active="onCategoryTreeActive"
            ></v-treeview>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="state.categoriesModal = false"
          >
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="state.locationsModal"
      width="400"
    >
      <v-card class="pt-4">
        <v-card-text class="flex-wrap">
          <v-text-field
            v-model="state.searchLocations"
            class="mx-2 mb-4"
            label="Search your location"
            autofocus
            hide-details
            clearable
          ></v-text-field>
          <div class="scroller categories">
            <v-treeview
              :key="state.locationsReRender"
              hoverable
              :items="state.nestedLocations"
              :search="state.searchLocations"
              :filter="locationsFilter"
              :open="expandedLocationIds"
              transition
              activatable
              open-on-click
              @update:active="onLocationTreeActive"
            ></v-treeview>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="state.locationsModal = false"
          >
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, useContext, onMounted, computed } from '@nuxtjs/composition-api'
import { useGetters, useActions } from 'vuex-composition-helpers'
import find from 'lodash/find'
import { ICategory, ICategoryTreeItem } from '~/interfaces/category'
import { ILocation, ILocationTreeItem } from '~/interfaces/location'
import { fullNameGenerator } from '~/utils/helperFunctions'

export default defineComponent({
  setup() {
    const { $axios } = useContext()
    const { user } = useGetters(['user'])
    const { showSnack } = useActions(['showSnack'])
    const maxCharacters = 100
    const state = reactive({
      rules: [
        (v: string) => !!v || 'Post description is required',
        (v: string) =>
          v.length <= maxCharacters || `Max ${maxCharacters} characters`
      ],
      postModal: false,
      categoriesModal: false,
      locationsModal: false,
      postTypes: [
        {
          key: 'seller',
          label: 'seller'
        },
        {
          key: 'consumer',
          label: 'buyer'
        }
      ],
      categories: [] as ICategory[],
      locations: [] as ILocation[],
      nestedCategories: [] as ICategoryTreeItem[],
      nestedLocations: [] as ILocationTreeItem[],
      selectedCategoryId: -1,
      selectedLocationId: -1,
      searchCategories: '',
      searchLocations: '',
      caseSensitive: false,
      categoryReRender: 0,
      locationsReRender: 0,
      isCategoryError: false,
      form: {
        content: '',
        postType: 'seller'
      }
    })

    const fullName = computed(() => fullNameGenerator(user.value))

    const categoriesFilter = computed(() => {
      return state.caseSensitive
        ? (item: any, search: any, textKey: any) => item[textKey].includes(search)
        : undefined
    })

    const locationsFilter = computed(() => {
      return state.caseSensitive
        ? (item: any, search: any, textKey: any) => item[textKey].includes(search)
        : undefined
    })

    const selectedCategoryName = computed(() => {
      const category: ICategory | undefined = find(
        state.categories,
        (category: ICategory) => category.id === state.selectedCategoryId
      )
      if (category) {
        return category.name
      }
      return false
    })

    const selectedLocationName = computed(() => {
      const location: ILocation | undefined = find(
        state.locations,
        (location: ILocation) => location.id === state.selectedLocationId
      )
      if (location) {
        return location.name
      }
      return false
    })

    const expandedCategoryIds = computed(() => {
      if (state.searchCategories.trim() !== '') {
        return state.categories.map((category: ICategory) => category.id)
      }
      return []
    })

    const expandedLocationIds = computed(() => {
      if (state.searchLocations.trim() !== '') {
        return state.locations.map((location: ILocation) => location.id)
      }
      return []
    })

    const nestCategories = (
      nestedCategories: ICategory[],
      id: number | null = null,
      link: string = 'parent'
    ): ICategoryTreeItem[] => {
      debugger
      return nestedCategories
        .filter((item: any) => item[link] === id)
        .map(
          (item: ICategory) => (
            {
              ...item,
              children: nestCategories(nestedCategories, item.id)
            }
          )
        )
    }

    const nestLocations = (
      nestedLocations: ILocation[],
      id: number | null = null,
      link: string = 'parent'
    ): ILocationTreeItem[] =>
      nestedLocations
        .filter((item: any) => item[link] === id)
        .map((item: ILocation) => ({ ...item, children: nestLocations(nestedLocations, item.id) }))

    const onCategoryTreeActive = (val: number[]) => {
      if (val.length > 0) {
        state.selectedCategoryId = val[0]
      } else {
        state.selectedCategoryId = -1
      }
      state.categoriesModal = false
    }

    const onLocationTreeActive = (val: number[]) => {
      if (val.length > 0) {
        state.selectedLocationId = val[0]
      } else {
        state.selectedLocationId = -1
      }
      state.locationsModal = false
    }

    const getAllCategories = async () => {
      try {
        const response: any = await $axios.get('postcategory')
        const { status, data } = response
        if (status === 200) {
          state.categories = data
          state.nestedCategories = nestCategories(state.categories)
        }
      } catch (error) {
        console.error(error)
      }
    }

    const getAllLocations = async () => {
      try {
        const response: any = await $axios.get('locations')
        const { status, data } = response
        if (status === 200) {
          state.locations = data
          state.nestedLocations = nestLocations(state.locations)
        }
      } catch (error) {
        console.error(error)
      }
    }

    const resetPostForm = () => {
      state.selectedCategoryId = -1
      state.selectedLocationId = -1
      state.searchCategories = ''
      state.searchLocations = ''
      state.form.content = ''
      state.form.postType = 'seller'
    }
    const savePost = async () => {
      state.isCategoryError = false
      if (!state.form.content || (state.form.content && state.form.content.trim() === '')) {
        showSnack({
          text: 'Please enter post description',
          color: 'error'
        })
      } else if (state.selectedCategoryId === -1) {
        showSnack({
          text: 'Please select a category',
          color: 'error'
        })
        state.isCategoryError = true
      } else {
        const postData = {
          verified: 0,
          user_id: user.value.id,
          location_id: state.selectedLocationId,
          category_id: state.selectedCategoryId,
          post_content: state.form.content,
          post_type: state.form.postType
        }
        try {
          const response = await $axios.post('posts', postData)
          const { status } = response
          if (status === 200) {
            showSnack({
              text: 'Your post is under review. This will be published once got approved',
              color: 'success'
            })
            state.postModal = false
            resetPostForm()
          }
        } catch (error) {
          showSnack({
            text: error,
            color: 'error'
          })
          console.error(error)
        }
      }
    }

    const removeSelectedCategory = () => {
      state.selectedCategoryId = -1
      state.nestedCategories = []
      state.nestedCategories = nestCategories(state.categories)
      state.categoryReRender = state.categoryReRender + 1
    }

    const removeSelectedLocation = () => {
      state.selectedLocationId = -1
      state.nestedLocations = []
      state.nestedLocations = nestLocations(state.locations)
      state.locationsReRender = state.locationsReRender + 1
    }

    const mounted = async () => {
      await getAllCategories()
      await getAllLocations()
    }

    onMounted(mounted)

    return {
      state,
      fullName,
      maxCharacters,
      categoriesFilter,
      locationsFilter,
      selectedCategoryName,
      selectedLocationName,
      expandedCategoryIds,
      expandedLocationIds,
      onCategoryTreeActive,
      onLocationTreeActive,
      removeSelectedCategory,
      removeSelectedLocation,
      savePost
    }
  }
})
</script>

<style lang="scss" scoped>
.trigger-add-post-btn {
  width: calc(100% - 32px);
}
</style>
