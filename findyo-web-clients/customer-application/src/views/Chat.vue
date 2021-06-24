<template>
  <div class="page chat" :class="[chatSelected ? 'chat-selected' : 'chat-deselected', {'shuffled': cardShuffle}]">
    <div class="box-nue chat-box flex">
      <div class="chat-list-wrapper box-nue">
        <ul class="chat-list">
          <li
            v-for="(chatUser, index) in chatUserList"
            :key="index"
            @click="directToChat(chatUser)"
          >
            <div
              class="chat-item flex v-center"
              :class="{'active': currentChatUser ? chatUser.chatID === currentChatUser.chatID : false}"
            >
              <UserImage :userImage="chatUser.displayPicture" :displayName="chatUser.displayName" />
              <div class="user-head-block">
                <div class="full-width flex h-between">
                  <p class="user-title" v-text="chatUser.displayName"></p>
                </div>
                <p
                  class="user-chat"
                  v-text="chatshortner('!@#$%^&*efeDrfe rwER WER Werw$ RWeFS ResdfGH DTfKFgyjESg zdrU ZESDT r af')"
                ></p>
              </div>
              <div class="chat-indicator-wrapper">
                <p class="posted-time">5 days ago</p>
                <div class="chat-indicator flex v-center h-center">
                  <svg
                    id="seen"
                    enable-background="new 0 0 512 512"
                    height="18"
                    width="18"
                    viewBox="0 0 512 512"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <g>
                      <path
                        fill="#8e95c5"
                        d="m473.074 120.729-203.295 203.296-21.212-21.212 153.765-153.764-38.926-38.926-153.765 153.765-61.047-61.047-38.926 38.926 61.047 61.047-21.211 21.211-110.578-110.577-38.926 38.926 149.503 149.503 60.138-60.137 60.137 60.137 242.222-242.221z"
                      />
                    </g>
                  </svg>
                  <!-- <span class="post-action-count">1</span> -->
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="chat-box-wrapper flex v-bottom">
        <div class="full-width" style="height: 100%">
          <router-view
            v-if="currentChatUser"
            class="settings-sub-wrapper"
            :eventBus="eventBus"
            :db="db"
            :auth="auth"
            :user="user"
            :chatHistory="chatHistory"
            :currentChatUser="currentChatUser"
            :replyAction="send"
            :message_obj="message_obj"
            @chat-back="backToChat"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { findyoName } from "./../func/usables";
/*eslint-disable*/
export default {
  name: "chat",
  components: {
    UserImage: () => import("@/components/common/UserImage"),
    Button: () => import("@/components/inputs/Button")
  },
  data() {
    return {
      message_obj: {
        message: ''
      },
      chat_ID: "",
      chat_master_id: "",
      topRatedFreelancers: [
        {
          userDp: "1",
          username: "user-1",
          title: "Plumber"
        },
        {
          userDp: "2",
          username: "user-2",
          title: "Dev"
        },
        {
          userDp: "3",
          username: "user-3",
          title: "Make-up artist"
        },
        {
          userDp: "1",
          username: "user-1",
          title: "Plumber"
        },
        {
          userDp: "2",
          username: "user-2",
          title: "Dev"
        },
        {
          userDp: "3",
          username: "user-3",
          title: "Make-up artist"
        },
        {
          userDp: "1",
          username: "user-1",
          title: "Plumber"
        },
        {
          userDp: "2",
          username: "user-2",
          title: "Dev"
        },
        {
          userDp: "3",
          username: "user-3",
          title: "Make-up artist"
        },
        {
          userDp: "1",
          username: "user-1",
          title: "Plumber"
        },
        {
          userDp: "2",
          username: "user-2",
          title: "Dev"
        },
        {
          userDp: "3",
          username: "user-3",
          title: "Make-up artist"
        }
      ],
      messageLoop: [
        {
          id: 1,
          username: "someone",
          time: "01:00 PM",
          message:
            "dummy message goes here Received msg Received msgReceived msgReceived msg Received msg Received msgReceived msg Received msgReceived msg",
          isMe: false
        },
        {
          id: 2,
          username: "you",
          time: "01:00 PM",
          message: "dummy message goes here send",
          isMe: true
        },
        {
          id: 3,
          username: "someone",
          time: "01:00 PM",
          message: "dummy message goes here received",
          isMe: false
        }
      ],
      chat_by_post_obj: {
        post_id: null,
        post_owner_id: null,
        sender_id: null
      },
      chatHistory: [],
      currentChatUser: undefined,
      chatSelected: false,
      cardShuffle: false
    };
  },
  props: {
    eventBus: undefined,
    db: undefined,
    auth: undefined,
    profileSummary: {
      type: [Object, Array]
    },
    user: {
      type: Object,
      default() {
        return {};
      }
    },
    chatData: {
      type: Object,
      default() {
        return {};
      }
    },
    chatUserList: {
      type: Array
    }
  },
  methods: {
    // getChatUserList(userID) {
    //   //console.log(this.$route.query.post_owner_id)

    //   return new Promise((resolve, reject) => {

    //     const self = this;

    //     this.db
    //       .collection("Chats")
    //       .where("UserID", "array-contains", userID)
    //       .orderBy("lastUpdatedTime", "desc")
    //       .onSnapshot(querySnapshot => {
    //         if (querySnapshot.docs.length > 0) {
    //           querySnapshot.forEach(doc => {
    //             //console.log(doc.id,"=>", doc.data())
    //             this.chat_master_id = doc.id;
    //             let data = doc.data();
    //             self.chatUserList.push({
    //               displayName:
    //                 data.Users[0].userID != this.user.uid
    //                   ? data.Users[0].displayName
    //                   : data.Users[1].displayName,
    //               displayPicture:
    //                 data.Users[0].userID != this.user.uid
    //                   ? data.Users[0].displayPicture
    //                   : data.Users[1].displayPicture,
    //               chatID: data.chatID,
    //               userID:
    //                 data.Users[0].userID != this.user.uid
    //                   ? data.Users[0].userID
    //                   : data.Users[1].userID
    //             });
    //           });
    //           resolve()
    //           console.log(this.chatUserList);
    //         } else {
    //           resolve("No Users")
    //           console.log("No Chats");
    //         }
    //       },error=>{
    //         reject("Error getting chat user list")
    //       });
    //   });
    // },
    directToChat(chatUser) {
      this.chatSelection(true)
      this.chat_ID = chatUser.chatID;
      //console.log(chatID);
      this.currentChatUser = chatUser;
      this.db
        .collection("Chat_Messages")
        .where("chatID", "==", chatUser.chatID)
        .orderBy("postedTime", "desc")
        .onSnapshot(querySnapshot => {
          console.log(querySnapshot.size);

          this.chatHistory = [];
          querySnapshot.forEach(doc => {
            let test = doc.data();
            this.chatHistory.push(test);
            this.chatHistory.sort(function(a, b) {
              let keyA = new Date(a.postedTime);
              let keyB = new Date(b.postedTime);
              // Compare the 2 dates
              if (keyA < keyB) return -1;
              if (keyA > keyB) return 1;
              return 0;
            })
          })
          const chatHistoryWrapper = document.querySelector('.chat-thread')
          chatHistoryWrapper.scrollTop = chatHistoryWrapper.clientHeight
        })
      this.$router.push({
        path: `/chat/${this.findyoName(chatUser.displayName)}`
      })
    },
    send() {
      this.message_obj.message
      const date = Date.now()
      this.db
        .collection("Chat_Messages")
        .add({
          chatID: this.chat_ID,
          message: this.message_obj.message,
          postedTime: date,
          userID: this.user.uid
        })
        .then(() => {
          this.updateChatList(date);
        })
        .catch(error => {
          this.message_obj.message = ''
          // this.$refs.commentDiv.innerText = ''
          document.getElementById('chatDiv').innerText = ''
          console.error("Error sending message: ", error)
        });
    },
    updateChatList(date) {
      this.db
        .collection("Chats")
        .doc(this.chat_master_id)
        .set(
          {
            lastUpdatedTime: date
          },
          { merge: true }
        )
        .then(() => {
          this.message_obj.message = ''
        })
        .catch(error => {
          console.log("Error")
        });
    },
    chatshortner(str) {
      let string = str;
      const limit = 39;
      if (str.length > limit) {
        string = str.substring(0, limit - 3);
        string = string + "...";
      }
      return string;
    },
    findyoName(str) {
      return findyoName(str);
    },
    initiateNewChat(
      date,
      randVal,
      postOwnerID,
      otherUserPhotoURL,
      otherUserDisplayName
    ) {
      return this.db.collection("Chats").add({
        UserID: [this.user.uid, postOwnerID],
        Users: [
          {
            displayName: this.user.displayName,
            displayPicture: this.user.photoURL,
            userID: this.user.uid
          },
          {
            displayName: otherUserDisplayName,
            displayPicture: otherUserPhotoURL,
            userID: postOwnerID
          }
        ],
        chatID: randVal,
        lastUpdatedTime: date
      });
    },
    sendFirstChatMessage(date, uniqueChatID) {
      this.db.collection("Chat_Messages").add({
        chatID: uniqueChatID,
        message: "Hi this is my first chat message",
        postedTime: date,
        userID: this.user.uid
      });
    },
    chatSelection (state) {
      this.chatSelected = state
      setTimeout(() => {
        this.cardShuffle = state
      }, 750)
    },
    initiateChat () {
      if (this.chatData.post_id) {
        this.chat_by_post_obj.post_id = this.chatData.post_id;
        this.chat_by_post_obj.post_owner_id = this.chatData.post_owner_id;
        this.chat_by_post_obj.sender_id = this.chatData.sender_id;
        this.chat_by_post_obj.post_owner_username = this.chatData.post_owner_username;
        this.chat_by_post_obj.post_owner_dp = this.chatData.post_owner_dp;
      }
      if (this.chat_by_post_obj.post_id) {
        let chat = this.chatUserList.filter(chatUser => {
          return chatUser.userID === this.chat_by_post_obj.post_owner_id;
        })
        //If chat exsits
        if (chat.length > 0) {
          const chatID = chat[0].chatID
          if (chatID) {
            this.directToChat(chat[0])
          }
        } else {
          if (this.chatUserList.length === 0) {
            // If a new chat
            let date = Date.now()
            let randVal = date.toString().concat((Math.random() * 10000000000).toString())
            // Gayan need to change this
            this.initiateNewChat(
              date,
              randVal,
              this.chat_by_post_obj.post_owner_id,
              this.chat_by_post_obj.post_owner_dp,
              this.chat_by_post_obj.post_owner_username
            ).then(() => {
                // this.sendFirstChatMessage(date, randVal)
              }).then(() => {
                // const chat = {
                //   chatID: randVal,
                //   displayName: "mobile_user",
                //   displayPicture: "",
                //   userID: "vmzP6NX2iydrQbNhTEctR6wj2ON2",
                // }
                // this.directToChat(randVal)
              }).catch(error => {
                console.log("Error creating new chat : ", error)
              })
          }
        }
      }
    },
    backToChat () {
      this.chatSelection(false)
      this.currentChatUser = undefined
    }
  },
  mounted () {
    this.initiateChat()
  },
  watch: {
    chatUserList: {
      deep: true,
      immediate: true,
      handler (val) {
        if (val.length) {
          this.initiateChat()
        }
      }
    }
  }
};
</script>

<style scoped lang="scss">
@import "@/assets/styles/variables.scss";
.chat-box {
  height: calc(100vh - 100px);
  margin: 0px;
  .chat-list-wrapper {
    width: 300px;
    display: block;
    background-color: $gray-lite;
    padding: 10px;
    margin: 0px;
    .chat-list {
      li {
        .chat-item {
          user-select: none;
          padding: 10px;
          border-radius: 10px;
          &.active {
            box-shadow: $shadow-inset-nue-1;
          }
          .user-head-block {
            width: calc(100% - 100px);
            .user-chat {
              letter-spacing: 0.5px;
              line-height: 1.3;
              font-weight: 400;
              color: $primary-color;
              opacity: 0.75;
              margin-top: 5px;
              display: block;
            }
          }
          .chat-indicator-wrapper {
            position: relative;
            width: 50px;
            .posted-time {
              position: absolute;
              top: 0px;
              right: 0px;
              white-space: nowrap;
              text-align: right;
            }
            .chat-indicator {
              margin: 15px 0px 0px 0px;
              height: 32px;
            }
            .post-action-count {
              position: relative;
              top: initial;
              left: initial;
            }
          }
        }
      }
    }
  }
  .chat-box-wrapper {
    width: calc(100% - 300px);
    height: 100%;
    padding: 0px 0px 0px 20px;
  }
}
</style>

