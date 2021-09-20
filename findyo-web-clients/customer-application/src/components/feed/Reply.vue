<template>
  <div class="add-post box">
    <div class="header-row">
      <div class="post-edition flex v-center h-center" v-if="isMine" @click.stop="setPostEditionState(true)">
        <svg @click.stop="setPostEditionState(true)" id="Capa_1" class="post-edition-trigger" enable-background="new 0 0 515.555 515.555" height="512" viewBox="0 0 515.555 515.555" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m496.679 212.208c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138 65.971-25.167 91.138 0"/><path d="m303.347 212.208c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138 65.971-25.167 91.138 0"/><path d="m110.014 212.208c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138 65.971-25.167 91.138 0"/></svg>
        <div v-if="isPostEditionMenu" class="overlay" @click.stop="setPostEditionState(false)"></div>
        <ul v-if="isPostEditionMenu" class="box">
          <li @click.stop="setPostDeleteMode">Delete</li>
          <li @click.stop="setPostEditionState(false)">Cancel</li>
        </ul>
      </div>
      <UserImage :userImage="data.photoURL" :displayName="data.displayName"/>
      <div class="user-head-block">
        <div class="reply-post-content-wrapper">
          <router-link :to="'/'+findyoName(data.displayName)+'/timeline'" class="user-title">{{data.displayName}}</router-link>
          <p class="comment-content"><router-link :to="'admin/'+findyoName(data.mention)" class="reply-to-user green-text" v-if="data.mention">@{{data.mention}}</router-link>{{data.body}}</p>
          <div class="comment-footer-wrapper">
            <post-action :likeAction="like" :isLike="isLiked" :likesCount="data.likes.length" :replyAction="reply" :isMine="isMine" :isLoggedIn="isLoggedIn"/>
            <p class="posted-time">{{postedTime}}</p>
          </div>
        </div>
      </div>
    </div>
    <stateleess-modal v-if="isDeleteModal" :ok="okDelete" :okLabel="'Yes, delete'" :cancelLabel="'Cancel'" :close="closeDelete">You want to delete this comment?</stateleess-modal>
  </div>
</template>

<script>
/*eslint-disable*/
import {findyoName, fromNow} from './../../func/usables'
import axios from 'axios'

export default {
  name: "reply",
  data() {
    return {
      isPostEditionMenu: false,
      isDeleteModal: false
    }
  },
  components: {
    UserImage: () => import ('@/components/common/UserImage'),
    'post-action': () => import ('@/components/common/PostAction'),
    'stateleess-modal': () => import ('@/components/modals/statelessModal')
  },
  props: {
    eventBus: undefined,
    replyAction: undefined,
    data: {
      type: Object,
      default: function () {
        return {}
      }
    },
    user: {
      type: Object,
      default () {
        return {
        }
      }
    },
    db: undefined,
    auth: undefined
  },
  computed: {
    postedTime () {
      return fromNow(this.data.postedTime)
    },
    isLiked () {
      return this.data.likes.indexOf(this.user.uid) > -1 ? true : false
    },
    isLoggedIn () {
      return Object.entries(this.user).length > 0 ? true : false
    },
    isMine() {
      return this.isLoggedIn && (this.data.userId === this.user.uid)
    }
  },
  methods: {
    findyoName (str) {
      return findyoName(str)
    },
    setPostEditionState(state) {
      this.isPostEditionMenu = state
    },
    setPostDeleteMode () {
      this.isDeleteModal = true
    },
    closeDelete () {
      this.isDeleteModal = false
    },
    okDelete () {
      
      axios.delete(process.env.VUE_APP_ROOT_API + '/deleteComment',  {
        data: {
          PostID: this.data.postId,
          CommentID: this.data.id
        }
      })
      .then(response => {
        this.isDeleteModal = false
        this.setPostEditionState(false)
        this.closeDelete()
        this.eventBus.$emit('message', {msg: 'Comment is successfully deleted', type: 0})
      })
      .catch(error => {
        this.eventBus.$emit('message', {msg: error, type: 1})
      });

    },
    reply () {
      if (this.replyAction) {
        return this.replyAction({userId: this.data.userId, displayName: this.data.displayName})
      }
    },
    like () {
      if (this.isLoggedIn) {
        let likedIndex = this.data.likes.indexOf(this.user.uid)
        if (likedIndex > -1) {
          // remove like
          this.data.likes.splice(likedIndex, 1)
          // this.removeLikeDB()
          this.setLike(false)
        } else {
          // add like
          this.data.likes.push(this.user.uid)
          // this.addLikeDB()
          this.setLike(true)
        }
      } else {
        let obj = {
          state: true,
          type: 2,
          title: 'Sorry',
          message: `You are not allowed to perform this action<br/>
          Do you wish to continue?<br/>
          Please sign-in`
        }
        this.eventBus.$emit('set-info', obj)
      }
    },
    setLike(state) {
      this.data
      let user = this.auth.currentUser
      axios.post(process.env.VUE_APP_ROOT_API + '/commentLike', {
        like: state,
        postID: this.data.postId,
        commentID: this.data.id,
        userID: user.uid
      })
      .then(() => {
      })
      .catch(error => {
        this.eventBus.$emit('message', {msg: error, type: 1})
      });
    }
  },
  mounted () {
  }
};
</script>

<style scoped>
.body-row {
  display: block;
  margin-bottom: 20px;
}
.foot-row {
  margin-top: 0px;
}
.user-title {
  font-weight: 600;
}
.user-head-block {
  width: 100%;
}
.user-title {
  float: left;
  margin-right: 5px;
  line-height: normal;
}
.comment-content {
  line-height: normal;
  letter-spacing: 0.5px;
  word-break: break-word;
  width: calc(100% - 40px);
}
.reply-post-content-wrapper >>> .post-action-wrapper {
  margin: 5px 0px 0px 0px;
}
.comment-footer-wrapper {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.posted-time {
  margin: 0px 40px 0px 0px;
}
</style>