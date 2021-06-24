<template>
  <div>
    <categorySelectModal v-if="isMyProfile && isCategorySelectModal.state" :list="nestedCats" :breadcrumb="categoryHierarchy" :isCategorySelectModal="isCategorySelectModal"/>
    <add-post :eventBus="eventBus" v-if="isMyProfile && user ? true : false" :db="db" :auth="auth" :user="user" :fb="fb" :postTypes="postTypes" :isAddPostFocused="isAddPostFocused" :breadcrumb="categoryHierarchy" :nestedCats="nestedCats" :nestedLocs="locations.locations" :locationBreadcrumb="locationHierarchy" :isCategorySelectModal="isCategorySelectModal" :isLocationSelectModal="isLocationSelectModal" :profileData="userData" :isProfileDataLoaded="isProfileDataLoaded"/>
    <ul class="feed-main-posts-block" v-if="posts.length > 0 && !isZeroPosts">
      <li v-for="post in posts" :key="post.id">
        <Post :data="post" :user="user" :postTypes="postTypes" :isAddPostFocused="isAddPostFocused" :eventBus="eventBus" :db="db" :auth="auth"/>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'timeline',
  data () {
    return {
      posts: [],
      isZeroPosts: true,
      isAddPostFocused: {
        'state': false
      },
      pageSize: 10,
      isCategorySelectModal: {
        state: false
      },
      isLocationSelectModal: {
        state: false
      }
    }
  },
  props: {
    eventBus: undefined,
    db: undefined,
    auth: undefined,
    user: undefined,
    fb: undefined,
    postTypes: {
      type: Array,
      default () {
        return []
      }
    },
    nestedCats: {
      type: Array
    },
    locations: {
      type: Object
    },
    categoryHierarchy: {
      type: Array
    },
    profileSettings: {
      type: Object
    },
    profileData: {
      type: Object,
      default() {
        return {}
      }
    },
    userData: {
      type: Object,
      default() {
        return {}
      }
    },
    isProfileDataLoaded: {
      type: Boolean,
      default: false
    },
    isMyProfile: {
      type: Boolean,
      default: false
    },
    locationHierarchy: {
      type: Array
    }
  },
  components: {
    'add-post': () => import ('@/components/feed/AddPost'),
    Post: () => import ('@/components/feed/Post'),
    categorySelectModal: () => import("@/components/common/Modals/CategorySelection")
  },
  methods: {
    postTypeLabel (typeId) {
      let type
      if (typeId === 1) {
        type = 'consumer'
      } else if (typeId === 2) {
        type = 'seller'
      }
      return type
    },
    getPostsInitialy() {
      this.db
      .collection("User_Posts")
      .where('userId', '==', this.profileData.uid)
      .orderBy('postedTime', 'desc')
      .limit(this.pageSize)
      .onSnapshot(querySnapshot => {
        this.posts = []
        this.isMorePostsLoading = false
        this.addToPosts(querySnapshot.docs)
      })
    },
    addToPosts (arr) {
      if (arr.length > 0) {
        arr.map(o => {
          let obj = o.data()
          this.$set(obj, 'id', o.id)
          this.posts.push(obj)
          this.lastSnapshot = o
          this.isZeroPosts = this.posts.length > 0 ? false : true
        })
      }
    }
  },
  watch: {
    profileData: {
      immediate: true,
      deep: true,
      handler: function(val, oldVal) {
        if (val && oldVal && (val.uid !== oldVal.uid)) {
          this.getPostsInitialy()
        }
      }
    }
  },
  mounted() {
  }
}
</script>

<style scoped>
</style>
