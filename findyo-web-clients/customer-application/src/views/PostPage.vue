<template>
  <div class="page post">
    <div class="page_post-wrapper">
      <Post v-if="post" :data="post" :user="user" :postTypes="postTypes" :eventBus="eventBus" :isExpand="false" :db="db" :auth="auth"/>
      <content-placeholders class="box" v-else>
        <content-placeholders-text :lines="3" />
        <content-placeholders-heading  :rounded="true" :img="true" />
      </content-placeholders>
    </div>
  </div>
</template>

<script>
/*eslint-disable*/
export default {
  name: 'post-page',
  data() {
    return {
      post: undefined
    }
  },
  props: {
    eventBus: undefined,
    user: {
      type: Object,
      default () {
        return {
        }
      }
    },
    db: undefined,
    auth: undefined,
    postTypes: {
      type: Array,
      default () {
        return []
      }
    }
  },
  components: {
    Post: () => import ('@/components/feed/Post')
  },
  methods: {
    loadPost() {
      let self = this
      this.db
      .collection("User_Posts")
      .doc(this.$route.params.id)
      .onSnapshot(querySnapshot => {
        this.post = querySnapshot.data()
      })
    },
  },
  mounted() {
    this.loadPost()
  }
}
</script>
