<template>
  <div class="post box-nue" :class="postType">
    <div v-if="data.isVerified" class="badge-reviewed"></div>
    <div class="body-row">
      <carousel
        class="post-carousel"
        :perPage="1"
        v-if="data.ImageCount > 0 && imageUrls.length > 0"
      >
        <slide v-for="(imageUrl, idx) in imageUrls" :key="idx">
          <img :src="imageUrl" />
        </slide>
      </carousel>
      <content-placeholders v-if="data.ImageCount > 0 && imageUrls.length === 0" :rounded="true">
        <content-placeholders-img />
      </content-placeholders>
      <p class="post-content">{{data.postContent}}</p>
      <div class="flex m-t-2">
        <ul class="post-meta-tags">
          <li
            class="post-meta meta-type"
            :class="data.postType === 1 ? 'consumer' : 'seller'"
          >{{data.postType === 1 ? 'Consumer' : 'Freelancer'}}</li>
        </ul>
        <ul
          class="selected-breadbrumb none-clickable-label m-l-1"
          v-if="categoryHierarchy.length > 0"
        >
          <li
            class="inline-flex v-center"
            v-show="item.selected"
            v-for="(item, index) in categoryHierarchy"
            :key="index"
          >
            <span>{{item.name_en}}</span>
          </li>
        </ul>
      </div>
    </div>
    <div class="header-row">
      <UserImage :userImage="data.photoUrl" :displayName="data.userName" />
      <div class="user-head-block">
        <router-link
          :to="'/'+findyoName(data.userName)+'/timeline'"
          class="user-title"
        >{{data.userName}}</router-link>
        <!-- <p class="user-subtitle">UI Engineer</p> -->
      </div>
      <div class="post-details-block">
        <p class="posted-time">{{postedTime}}</p>
        <p
          class="text-right post-from"
          v-if="endLocation && endLocation.trim() !== ''"
          v-text="'from ' + endLocation"
        ></p>
      </div>
    </div>
    <div class="foot-row">
      <post-action
        :likeAction="like"
        :isLike="isLiked"
        :likesCount="data.likes.length"
        :repliesCount="data.numberOfComments"
        :replyAction="isExpand ? reply : undefined"
        :isReply="isReply"
        :shareAction="share"
        :toggleReplyAction="expand"
        :expanded="openReplies"
        :viewLikesActions="viewLikedUsers"
        :contactAction="showContactModal"
        :isMine="isMine"
        :data="data"
      />
    </div>
    <div class="textarea-wrapper" v-if="isReply || !isExpand">
      <!-- <textarea class="content" v-autosize="replyMsg" v-model="replyMsg"/> -->
      <div
        contenteditable
        ref="commentDiv"
        class="contenteditable content"
        @keydown.enter.prevent
        @keyup="updateReplyMsg"
        @paste="pasteContent($event)"
      ></div>
      <Button class="pull-right" :label="'Reply'" :action="postComment" :loading="isCommenting" />
    </div>
    <div v-if="openReplies" class="reply-main-wrapper">
      <content-placeholders class="reply-placeholder" :rounded="true" v-if="comments.length === 0">
        <content-placeholders-img
          v-for="item in data.numberOfComments > 3 ? 3 : data.numberOfComments"
          :key="item"
        />
      </content-placeholders>
      <Reply
        class="reply-wrapper"
        v-for="(comment, idx) in comments"
        :key="idx"
        :data="comment"
        :user="user"
        :eventBus="eventBus"
        :db="db"
        :auth="auth"
        :replyAction="reply"
      />
      <router-link
        :to="'post/'+data.id"
        v-if="data.numberOfComments > 0 && isExpand"
        class="load-all-trigger"
      >View all</router-link>
    </div>
    <div
      class="post-edition flex v-center h-center"
      v-if="isMine"
      @click.stop="setPostEditionState(true)"
    >
      <svg
        id="Capa_1"
        class="post-edition-trigger"
        enable-background="new 0 0 515.555 515.555"
        height="512"
        viewBox="0 0 515.555 515.555"
        width="512"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="m496.679 212.208c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138 65.971-25.167 91.138 0"
        />
        <path
          d="m303.347 212.208c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138 65.971-25.167 91.138 0"
        />
        <path
          d="m110.014 212.208c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138 65.971-25.167 91.138 0"
        />
      </svg>
      <div v-if="isPostEditionMenu" class="overlay" @click.stop="setPostEditionState(false)"></div>
      <ul v-if="isPostEditionMenu" class="box">
        <li @click.stop="setPostEditMode(true)">Edit</li>
        <li @click.stop="setPostDeleteMode">Delete</li>
        <li @click.stop="setPostEditionState(false)">Cancel</li>
      </ul>
    </div>
    <add-post
      class="post-edit-window"
      :eventBus="eventBus"
      v-if="(user ? true : false) && isEditable.state"
      :user="user"
      :db="db"
      :postTypes="postTypes"
      :isAddPostFocused="isAddPostFocused"
      :isArticle="isArticle"
      :isEditable="isEditable"
      :content="data"
      :imageUrls="imageUrls"
      :breadcrumb="breadcrumb"
      :nestedCats="nestedCats"
      :nestedLocs="nestedLocs"
      :locationBreadcrumb="locationBreadcrumb"
      :isCategorySelectModal="isCategorySelectModal"
      :isLocationSelectModal="isLocationSelectModal"
      :profileData="profileData"
    />
    <stateleess-modal
      v-if="isDeleteModal"
      :ok="okDelete"
      :okLabel="'Yes, delete'"
      :cancelLabel="'Cancel'"
      :close="closeDelete"
    >You want to delete this post?</stateleess-modal>
    <stateleess-modal
      v-if="isContactTypeModal"
      :okLabel="'Yes, delete'"
      :cancelLabel="'Cancel'"
      :close="closeContactModal"
    >
      <div>
        <h3>Select contact method</h3>
      </div>
      <div class="contact-method-container">
        <div class="contact-method contact-method-phone box-nue" v-if="data.contactOption">
          <div class="contact-method-icon phone"></div>
          <div class="contact-method-content">
            <label>Direct contact</label>
            <ul class="direct-contact-option">
              <!-- <li v-for="(phone, index) in phoneNumber" :key="index" v-text="phone"></li> -->
              <li v-text="data.contactOption"></li>
            </ul>
          </div>
        </div>
        <div class="contact-method contact-method-chat box-nue" @click="contact">
          <div class="contact-method-icon chat"></div>
          <label>Leave a message</label>
        </div>
      </div>
    </stateleess-modal>
  </div>
</template>

<script>
/* eslint-disable */
import { findyoName } from "./../../func/usables";
import moment from "moment";
import { fbIinit } from "./../../firebaseInit";
import firebase from "firebase/app";
import "firebase/firestore";
import "firebase/storage";
import { Carousel, Slide } from "vue-carousel";
import axios from "axios";
import { fromNow } from "./../../func/usables";

export default {
  name: "post",
  data() {
    return {
      replyMsg: "",
      isReply: false,
      replyToUser: undefined,
      openReplies: false,
      imageUrls: [],
      isEditable: {
        state: false
      },
      isArticle: true,
      isPostEditionMenu: false,
      isDeleteModal: false,
      isCommenting: false,
      isContactTypeModal: false,
      comments: [],
      flatternLocations: [],
      endLocation: "",
      phoneNumber: ""
    };
  },
  components: {
    UserImage: () => import("@/components/common/UserImage"),
    "post-action": () => import("@/components/common/PostAction"),
    Reply: () => import("@/components/feed/Reply"),
    Button: () => import("@/components/inputs/Button"),
    "stateleess-modal": () => import("@/components/modals/statelessModal"),
    Carousel,
    Slide,
    "add-post": () => import("@/components/feed/AddPost")
  },
  props: {
    eventBus: undefined,
    db: undefined,
    auth: undefined,
    data: {
      type: Object,
      default() {
        return {};
      }
    },
    user: {
      type: Object,
      default() {
        return {};
      }
    },
    postTypes: {
      type: Array,
      default: function() {
        return [];
      }
    },
    isAddPostFocused: {
      type: Object,
      default: function() {
        return {};
      }
    },
    isExpand: {
      type: Boolean,
      default: true
    },
    breadcrumb: {
      type: Array
    },
    locationBreadcrumb: {
      type: Array,
      required: true
    },
    isCategorySelectModal: {
      type: Object
    },
    isLocationSelectModal: {
      type: Object
    },
    nestedCats: {
      type: Array
    },
    nestedLocs: {
      type: Array
    },
    locationBreadcrumb: {
      type: Array
    },
    profileData: {
      type: Object
    }
  },
  computed: {
    postType() {
      let type;
      if (this.data.postType === 1) {
        type = "consumer";
      } else if (this.data.postType === 2) {
        type = "seller";
      }
      return type;
    },
    postedTime() {
      return fromNow(this.data.postedTime);
    },
    isLiked() {
      return this.data.likes.indexOf(this.user.uid) > -1 ? true : false;
    },
    isLoggedIn() {
      return Object.entries(this.user).length > 0 ? true : false;
    },
    isMine() {
      return this.isLoggedIn && this.data.userId === this.user.uid;
    },
    categoryHierarchy() {
      let categoryHierarchy = [];
      let temp_array = [];
      if (this.data.category) {
        temp_array.push(this.data.category);
        if (temp_array.length > 0) {
          this.mapCategoryHierarchy(temp_array, categoryHierarchy);
        }
      }
      return categoryHierarchy;
    }
  },
  methods: {
    findEndLocation(locs) {
      locs.map(o => {
        this.flatternLocations.push(o.name_en);
        if (o.locations && o.locations.length > 0) {
          this.findEndLocation(o.locations);
        }
      });
    },
    setPostDeleteMode() {
      this.isDeleteModal = true;
    },
    okDelete() {
      this.db
        .collection("User_Posts")
        .doc(this.data.id)
        .delete()
        .then(() => {
          console.log("Document successfully deleted!");
          this.isDeleteModal = false;
        })
        .catch(error => {
          console.error("Error removing document: ", error);
        });
    },
    closeDelete() {
      this.isDeleteModal = false;
    },
    setPostEditionState(state) {
      this.isPostEditionMenu = state;
    },
    setPostEditMode(state) {
      this.$set(this.isEditable, "state", state);
      this.isPostEditionMenu = false;
    },
    getImageUrl() {
      let firebaseInitCon = fbIinit.obj;
      let storageRef = firebaseInitCon.storage().ref();
      for (let x = 1; x <= this.data.ImageCount; x++) {
        storageRef
          .child(`Posts/${this.data.id}/${x}.jpg`)
          .getDownloadURL()
          .then(url => {
            this.imageUrls.push(url);
          })
          .catch(error => {});
      }
    },
    reply(obj) {
      if (obj) {
        this.isReply = true;
        this.replyToUser = obj;
        setTimeout(() => {
          this.$refs.commentDiv.innerHTML = `<span ref="replyToUser" class="reply-to-user">@${this.findyoName(
            this.replyToUser.displayName
          )}</span>`;
        }, 0);
      } else {
        this.isReply = !this.isReply;
        this.replyToUser = undefined;
        this.expand();
      }
    },
    like() {
      if (this.isLoggedIn) {
        let postId = this.data.id;
        let likedUserId = this.user.uid;
        let likedIndex = this.data.likes.indexOf(this.user.uid);
        if (likedIndex > -1) {
          // remove like
          this.data.likes.splice(likedIndex, 1);
          this.setLike(false);
        } else {
          // add like
          this.data.likes.push(this.user.uid);
          this.setLike(true);
        }
      } else {
        let obj = {
          state: true,
          type: 2,
          title: "Sorry",
          message: `You are not allowed to perform this action<br/>
          Do you wish to continue?<br/>
          Please sign-in`
        };
        this.eventBus.$emit("set-info", obj);
      }
    },
    goToLogin() {
      this.$router.push({ path: "/login" });
    },
    setLike(state) {
      let user = this.auth.currentUser;
      axios
        .post(process.env.VUE_APP_ROOT_API + "/postLike", {
          like: state,
          postID: this.data.id,
          userID: user.uid
        })
        .then(response => {})
        .catch(error => {
          this.eventBus.$emit("message", { msg: error, type: 1 });
        });
    },
    share() {
      this.isReply = !this.isReply;
    },
    expand() {
      if (!this.openReplies) {
        this.openReplies = true;
        this.loadComments();
      } else {
        this.openReplies = false;
      }
    },
    findyoName(str) {
      return findyoName(str);
    },
    nullEvent() {},
    postComment() {
      if (this.isLoggedIn) {
        let comment = this.replyMsg.trim();
        let user = this.auth.currentUser;
        if (comment.length >= 100) {
          this.eventBus.$emit("message", {
            msg: "Maximum character limit is 100",
            type: 1
          });
        } else if (!!this.replyMsg === false) {
          this.eventBus.$emit("message", {
            msg: "Type your reply before send",
            type: 1
          });
        } else {
          let self = this;
          this.isCommenting = true;
          let commentObject = this.replyToUser
            ? {
                body: comment,
                displayName: user.displayName,
                photoURL: user.photoURL,
                postId: this.isExpand ? this.data.id : this.$route.params.id,
                userId: user.uid,
                mention: this.replyToUser.displayName
              }
            : {
                body: comment,
                displayName: user.displayName,
                photoURL: user.photoURL,
                postId: this.isExpand ? this.data.id : this.$route.params.id,
                userId: user.uid
              };
          axios
            .post(process.env.VUE_APP_ROOT_API + "/postComment", commentObject)
            .then(function(response) {
              self.replyMsg = "";
              self.$refs.commentDiv.innerText = "";
              self.isCommenting = false;
            })
            .catch(error => {
              this.isCommenting = false;
              this.eventBus.$emit("message", { msg: error, type: 1 });
            });
        }
      } else {
        let obj = {
          state: true,
          type: 2,
          title: "Sorry",
          message: `You are not allowed to perform this action<br/>
          Do you wish to continue?<br/>
          Please sign-in`
        };
        this.eventBus.$emit("set-info", obj);
      }
    },
    loadComments() {
      let self = this;
      if (this.isExpand) {
        this.db
          .collection("Posts_Comments")
          .where("postId", "==", this.data.id)
          .orderBy("postedTime", "asc")
          .limit(5)
          .onSnapshot(querySnapshot => {
            if (querySnapshot.docs.length > 0) {
              self.comments = querySnapshot.docs.map(comment => {
                let commentObj = comment.data();
                commentObj.id = comment.id;
                return commentObj;
              });
              self.openReplies = true;
            } else {
              this.openReplies = false;
            }
          });
      } else {
        this.db
          .collection("Posts_Comments")
          .where("postId", "==", this.$route.params.id)
          .orderBy("postedTime", "asc")
          .onSnapshot(querySnapshot => {
            if (querySnapshot.docs.length > 0) {
              self.comments = querySnapshot.docs.map(comment => {
                let commentObj = comment.data();
                commentObj.id = comment.id;
                return commentObj;
              });
              self.openReplies = true;
            } else {
              this.openReplies = false;
            }
          });
      }
    },
    viewLikedUsers() {
      alert("aaa");
    },
    updateReplyMsg(event) {
      if (this.replyToUser) {
        let findyoName = "@" + this.findyoName(this.replyToUser.displayName);
        let innerText = event.target.innerText.replace(findyoName, "");
        this.replyMsg = innerText;
      } else {
        this.replyMsg = event.target.innerText;
      }
    },
    pasteContent(event) {
      this.replyToUser = undefined;
      setTimeout(() => {
        this.replyMsg = event.target.innerText.replace(/\n/g, " ");
        this.$refs.commentDiv.innerText = this.replyMsg;
      }, 0);
    },
    mapCategoryHierarchy(list, categoryHierarchy) {
      list.map(o => {
        categoryHierarchy.push(o);
        if (o.childs && o.childs.length > 0) {
          this.mapCategoryHierarchy(o.childs, categoryHierarchy);
        }
      });
    },
    contact() {
      let chat_object = {
        post_id: this.data.id,
        post_owner_id: this.data.userId,
        sender_id: this.user.uid,
        post_owner_dp: this.data.photoUrl,
        post_owner_username: this.data.userName
      };
      this.eventBus.$emit("set-chat-data", chat_object);
      this.$router.push({
        path: `/chat/${this.findyoName(this.data.userName)}`
      });
    },
    showContactModal() {
      if (this.isLoggedIn) {
        this.isContactTypeModal = true;
      } else {
        let obj = {
          state: true,
          type: 2,
          title: "Sorry",
          message: `You are not allowed to perform this action<br/>
          Do you wish to continue?<br/>
          Please sign-in`
        };
        this.eventBus.$emit("set-info", obj);
      }
    },
    closeContactModal() {
      this.isContactTypeModal = false;
    }
  },
  mounted() {
    this.getImageUrl();
    if (!this.isExpand) {
      this.expand();
    }
    if (this.data.location) {
      this.findEndLocation([this.data.location]);
      this.endLocation =
        this.flatternLocations.length > 0
          ? this.flatternLocations[this.flatternLocations.length - 1]
          : "";
    }
  }
};
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";
.header-row {
  position: relative;
  border-bottom: none;
}
.body-row {
  display: block;
  margin: 0px 0px 10px 0px;
}
.foot-row {
  margin-top: 0px;
}
textarea {
  padding: 20px 20px;
  min-height: 30px;
  transition: 0.2s;
}
.badge-reviewed {
  background-image: url(./../../assets/img/svg/reviewed.svg);
}
.reply-main-wrapper {
  padding: 20px 0px 0px 0px;
}

.category-selection-ul:not(:last-child) {
  margin: 0px 20px 0px 0px;
}
.selected-breadbrumb,
.selected-breadbrumb li,
.selected-breadbrumb li span {
  cursor: default !important;
  text-transform: lowercase;
}
.category-selection-li:not(:last-child) {
  margin: 0px 0px 10px 0px;
}
.selected-breadbrumb > li {
  margin: 0px 20px 0px 0px;
  padding: 5px 0px;
  height: 22px;
}
.selected-breadbrumb > li:not(:last-child) {
  height: auto;
}
.selected-breadbrumb > li:not(:last-child):after {
  content: url(./../../assets/img/svg/chevron-right.svg);
  position: absolute;
  width: 10px;
  height: 14px;
  background-size: 10px;
  background-position: center;
  background-repeat: no-repeat;
  right: -15px;
  top: 6px;
}
.post-from {
  margin: 5px 0px 0px 0px;
}
.contact-method-container {
  display: flex;
  margin: 20px 0px 0px 0px;
  text-align: left;
}
.contact-method {
  width: calc(50% - 10px);
  display: flex;
  align-items: center;
  color: #fff;
  padding: 10px;
  margin: 0px;
  cursor: pointer;
  &.contact-method-phone {
    background-color: $consumer-lite;
    &:hover {
      background-color: $consumer;
    }
  }
  &.contact-method-chat {
    background-color: $seller-lite;
    &:hover {
      background-color: $seller;
    }
  }
  &:nth-child(1) {
    margin: 0px 20px 0px 0px;
  }
  .contact-method-icon {
    width: 60px;
    height: 100px;
    background-size: 32px;
    background-repeat: no-repeat;
    background-position: center;
    &.phone {
      background-image: url("./../../assets/img/svg/phone-white.svg");
    }
    &.chat {
      background-image: url("./../../assets/img/svg/chat-white.svg");
    }
  }
  .contact-method-content {
    margin: 0px 0px 0px 0px;
    label {
      cursor: pointer;
    }
  }
}
</style>