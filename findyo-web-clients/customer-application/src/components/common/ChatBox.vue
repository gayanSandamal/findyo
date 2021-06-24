<template>
  <div class="chat-history full-width" ref="chatHistory">
    <svg class="chat-back" @click="$emit('chat-back')" fill="#0b1247" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 55.753 55.753" style="enable-background:new 0 0 55.753 55.753;" xml:space="preserve">
      <g>
        <path d="M12.745,23.915c0.283-0.282,0.59-0.52,0.913-0.727L35.266,1.581c2.108-2.107,5.528-2.108,7.637,0.001
          c2.109,2.108,2.109,5.527,0,7.637L24.294,27.828l18.705,18.706c2.109,2.108,2.109,5.526,0,7.637
          c-1.055,1.056-2.438,1.582-3.818,1.582s-2.764-0.526-3.818-1.582L13.658,32.464c-0.323-0.207-0.632-0.445-0.913-0.727
          c-1.078-1.078-1.598-2.498-1.572-3.911C11.147,26.413,11.667,24.994,12.745,23.915z"/>
      </g>
    </svg>
    <div class="chat-history-wrapper" ref="chatHistorywrapper" :style="{'max-height': height + 'px'}">
      <ul class="chat-thread">
        <li class="flex"  v-for="(item, index) in chatHistory" :key="index" :class="{'h-right mine': item.userID === user.uid}">
          <div class="msg-box ">
            <div class="chat-item flex v-center active">
              <UserImage :userImage="item.userID === user.uid ? user.photoURL : currentChatUser.displayPicture" :displayName="currentChatUser.displayName"/>
              <div class="user-head-block">
                <div class="full-width flex h-between">
                  <p class="user-title" v-text="currentChatUser.displayName"></p>
                </div>
                <p class="user-chat" v-text="item.message"></p>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="textarea-wrapper">
      <div contenteditable="" ref="chatDiv" id="chatDiv" class="contenteditable content" @keydown.enter.prevent @keyup="updateReplyMsg" @paste="pasteContent($event)" autofocus></div>
      <Button class="pull-right" :label="'Reply'" :action="reply" :loading="isSending"/>
    </div>
  </div>
</template>

<script>
export default {
  name: 'chat-box',
  components: {
    Button: () => import ('@/components/inputs/Button'),
    UserImage: () => import("@/components/common/UserImage")
  },
  data() {
    return {
      height: 300
    }
  },
  props: {
    replyAction: undefined,
    isSending: {
      type: Boolean,
      default: false
    },
    chatHistory: {
      type: Array,
      default() {
        return []
      }
    },
    eventBus: undefined,
    db: undefined,
    auth: undefined,
    user: {
      type: Object,
      default() {
        return {}
      }
    },
    currentChatUser: {
      type: Object,
      default() {
        return {}
      }
    },
    message_obj: {
      type: Object,
      default() {
        return {}
      }
    },
  },
  methods: {
    updateReplyMsg(event) {
      this.message_obj.message = event.target.innerText
      this.$set(this.message_obj, 'message', event.target.innerText)
    },
    pasteContent(event) {
      this.replyToUser = undefined
      setTimeout(() => {
        this.$set(this.message_obj, 'message', event.target.innerText.replace(/\n/g, " "))
        this.$refs.chatDiv.innerText = this.message_obj.message
      }, 0)
    },
    reply() {
      if (this.replyAction) {
        return this.replyAction()
      }
    },
    setHeight() {
      this.height = this.$refs.chatHistory.clientHeight - 60
    }
  },
  mounted() {
    this.setHeight()
  },
  created() {
    window.addEventListener('resize', () => {
      this.setHeight()
    })
  }
}
</script>

<style lang="scss" scoped>
@import "@/assets/styles/variables.scss";
.chat-history {
  position: relative;
  height: 100%;
  .chat-history-wrapper {
    width: 100%;
    overflow: auto;
    position: absolute;
    bottom: 50px;
    left: 0px;
    .chat-thread {
      li {
        &:not(:last-child) {
          margin: 0px 0px 10px 0px;
        }
        .msg-box {
          background-color: $gray-nue;
          padding: 10px 20px 10px 10px;
          border-radius: 40px 40px 40px 0px;
          min-width: 150px;
          max-width: 60%;
          width: auto;
          word-break: keep-all;
          white-space: nowrap;
          box-shadow: $primary-shadow-0-nue;
          .user-head-block {
            width: calc(100% - 100px);
            .user-chat {
              letter-spacing: 0.5px;
              line-height: 1.3;
              color: $primary-color;
              opacity: 0.75;
              margin-top: 5px;
              display: block;
            }
            .user-title {
              letter-spacing: 1px;
              white-space: nowrap;
            }
            .user-chat,
            .user-title {
              color: #000;
              font-weight: 600;
            }
          }
        }
        &.mine {
          .msg-box {
            border-radius: 40px 40px 0px 40px;
            background-color: $seller-lite;
          }
        }
      }
    }
  }
  .textarea-wrapper {
    position: absolute;
    bottom: 0px;
    left: 0px;
  }
}
.chat-back {
  width: 20px;
  height: 20px;
  display: none;
}
</style>