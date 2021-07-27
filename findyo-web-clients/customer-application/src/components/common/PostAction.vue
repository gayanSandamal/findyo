<template>
  <div class="post-action-wrapper">
    <ul class="post-action-ul">
      <li class="post-action-li" :class="{'active': isLike}" @click.stop="like">
        <span class="post-action-icon like"></span>
        <span v-if="likesCount > 0" class="post-action-count" @click.stop="viewLikes">{{likesCount > 99 ? '99+' : likesCount }}</span>
      </li>
      <!-- <li class="post-action-li" v-if="!isMine" :class="{'active': isReply}" @click.stop="reply">
        <span class="post-action-icon reply"></span>
        <span v-if="repliesCount > 0" class="post-action-count reply" @click.stop>{{repliesCount > 99 ? '99+' : repliesCount }}</span>
      </li> -->
      <!-- <li v-if="shareAction" class="post-action-li" @click.stop="share"> -->
      <li v-if="shareAction" class="post-action-li">
        <span class="post-action-icon share"></span>
      </li>
    </ul>
    <div class="post-action-right">
      <!-- <span v-if="repliesCount > 0" class="toggle-reply-action" :class="{'active': expanded}" @click.stop="toggleReplies"></span> -->
      <ul class="post-action-ul">
        <li class="post-action-li" v-if="!isMine && contactAction" @click.stop="concat">
          <span class="post-action-icon chat"></span>
        </li>
        <li class="post-action-li" v-if="replyAction" :class="{'active': isReply}" @click.stop="reply">
          <span class="post-action-icon reply"></span>
          <span v-if="repliesCount > 0" class="post-action-count reply" @click.stop>{{repliesCount > 99 ? '99+' : repliesCount }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: 'post-action',
  data () {
    return {}
  },
  props: {
    likeAction: undefined,
    replyAction: undefined,
    shareAction: undefined,
    toggleReplyAction: undefined,
    viewLikesActions: undefined,
    contactAction: undefined,
    expanded: {
      type: Boolean,
      default: false
    },
    isReply: {
      type: Boolean,
      default: false
    },
    isLike: {
      type: Boolean,
      default: false
    },
    likesCount: {
      type: Number,
      default: 0
    },
    repliesCount: {
      type: Number,
      default: 0
    },
    isMine: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    like () {
      if (this.likeAction) {
        return this.likeAction()
      }
    },
    reply () {
      if (this.replyAction) {
        return this.replyAction()
      }
    },
    share () {
      if (this.shareAction) {
        return this.shareAction()
      }
    },
    toggleReplies () {
      if (this.toggleReplyAction) {
        return this.toggleReplyAction()
      }
    },
    viewLikes () {
      if (this.viewLikesActions) {
        return this.viewLikesActions()
      }
    },
    concat () {
      if (this.contactAction) {
        return this.contactAction()
      }
    }
  }
}
</script>

<style scoped>
.like {
  background-image: url(./../../assets/img/svg/like.svg);
}
.reply {
  background-image: url(./../../assets/img/svg/reply.svg);
}
.share {
  background-image: url(./../../assets/img/svg/share.svg);
}
.active .like {
  background-image: url(./../../assets/img/svg/liked.svg);
}
.active .like,
.active .reply,
.active .share {
  opacity: 1;
}
.post-action-ul {
  display: inline-block;
}
.post-action-li {
  position: relative;
  display: inline-block;
}
.post-action-li:not(:last-child) {
  margin: 0px 20px 0px 0px;
}
.post-action-wrapper {
  margin: 20px 0px 0px 0px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.toggle-reply-action {
  background-image: url(./../../assets/img/svg/arrow-down.svg);
  display: inline-block;
  width: 18px;
  height: 18px;
  background-size: 14px;
  background-position: center;
  background-repeat: no-repeat;
  cursor: pointer;
  transform: rotate(180deg);
  transition: 0.2s;
}
.toggle-reply-action.active {
  background-image: url(./../../assets/img/svg/arrow-down.svg);
  transform: rotate(0deg);
  transition: 0.2s;
}
.chat {
  background-image: url(./../../assets/img/svg/chat.svg);
}
</style>
