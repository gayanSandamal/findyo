<template>
  <div id="app" :class="{ available: loaded }">
    <Header
      :eventBus="eventBus"
      :isUser="user ? true : false"
      :user="user"
      :userData="userData"
      :isProfileDataLoaded="isProfileDataLoaded"
    />
    <article class="container">
      <router-view
        :eventBus="eventBus"
        :user="user"
        :languageId="languageId"
        :postTypes="postTypes"
        :db="db"
        :auth="auth"
        :fb="fb"
        :profileData="profileData"
        :userData="userData"
        :isProfileDataLoaded="isProfileDataLoaded"
        :locations="locations"
        :chatData="chatData"
        :chatUserList="chatUserList"
      />
    </article>
    <sidebar-nav
      :eventBus="eventBus"
      :user="user"
      :userData="userData"
      :isProfileDataLoaded="isProfileDataLoaded"
    />
    <info-window v-if="infoObject.state" :info="infoObject" />
    <Message :eventBus="eventBus" :msg="''" />
    <Progress :eventBus="eventBus" />
  </div>
</template>
<script>
/* eslint-disable */
import firebase from "firebase/app";
import "firebase/auth";
import { eventBus } from "./event-bus.js";
import { fbIinit } from "./firebaseInit";
import { findyoName } from "./func/usables";
import user from "@/assets/javascript/api/user";
import app from "@/assets/javascript/mixins/app.mixin.js";

export default {
  mixins: [user, app],
  name: "app",
  data() {
    return {
      db: undefined,
      auth: undefined,
      loaded: false,
      fb: undefined,
      eventBus: undefined,
      languageId: 0,
      postTypes: [
        {
          typeId: 1,
          label: "Post as a consumer",
          placeholder: "Ask for a service you need right now",
          filterLabel: "Consumer"
        },
        {
          typeId: 2,
          label: "Post as a freelancer",
          placeholder: "Let the world know about your service",
          filterLabel: "Freelancer"
        }
      ],
      infoObject: {
        state: false,
        type: 0,
        title: null,
        message: null
      },
      isProfileDataLoaded: false,
      locations: {},
      chatData: {},
      chatUserList: []
    };
  },
  props: {
    firebaseInit: undefined
  },
  components: {
    Header: () =>
      import(/* webpackChunkName: "Header"*/ "@/components/common/Header"),
    "sidebar-nav": () =>
      import(
        /* webpackChunkName: "sidebar-nav"*/ "@/components/common/SidebarNav"
      ),
    "info-window": () =>
      import(
        /* webpackChunkName: "info-window"*/ "@/components/modals/InfoWindow"
      ),
    Message: () =>
      import(/* webpackChunkName: "Message"*/ "@/components/common/Message"),
    Progress: () =>
      import(/* webpackChunkName: "Progress"*/ "@/components/common/Progress")
  },
  computed: {
    user() {
      return this.$store.state.user.user;
    }
  },
  methods: {
    setInfoObj(params) {
      this.infoObject.state = params.state;
      this.infoObject.type = params.type;
      this.infoObject.title = params.title;
      this.infoObject.message = params.message;
    },
    authChanged() {
      return new Promise((resolve, reject) => {
        if (this.user && user.token !== undefined && user.token !== undefined) {
          this.getUserProfile(this.user);
          resolve();
        } else {
          reject("Error getting logged in user");
        }
      });
    },
    async getUserProfile(user, fromCompletion) {
      this.isProfileDataLoaded = false;
      await this.getUser(
        {
          url: `GetUser/${user.id}`,
          method: "GET"
        },
        async response => {
          const { data } = response;
          // setProfile Data
          this.profileData.email = this.user.email;
          this.profileData.last_name = data[0].lastname;
          this.profileData.displayName = data[0].displayname;
          this.profileData.first_name = data[0].firstname;
          this.profileData.username = data[0].username;
          this.profileData.district = data[0].district;
          this.profileData.creationTime = data[0].created_at;
          this.profileData.phoneNumber = data[0].phone;
          this.profileData.postal_code = data[0].postal_code;
          this.profileData.address = data[0].address;
          this.profileData.emailVerified = data[0].email_verified_at
            ? data[0].email_verified_at
            : false;
          this.profileData.job_title = data[0].job_title;
          this.profileData.country = data[0].country;
          this.profileData.province = data[0].province;
          this.profileData.city = data[0].city;
          this.profileData.id = this.user.id;

          //set userData
          this.userData.creationTime = data[0].created_at;
          this.userData.job_title = data[0].job_title;
          this.userData.address = data[0].address;
          this.userData.postal_code = data[0].postal_code;
          this.userData.displayName = data[0].displayname;
          this.userData.username = data[0].username;
          this.userData.phoneNumber = data[0].phone;
          this.userData.district = data[0].district;
          this.userData.email = this.user.email;
          this.userData.country = data[0].country;
          this.userData.last_name = data[0].lastname;
          this.userData.province = data[0].province;
          this.userData.emailVerified = data[0].email_verified_at
            ? data[0].email_verified_at
            : false;
          this.userData.first_name = data[0].firstname;
          this.userData.city = data[0].city;
          this.userData.id = this.user.id;

          const updatedUser = {
            ...user,
            displayName: data[0].displayname,
            phoneNumber: data[0].phone,
            emailVerified: data[0].email_verified_at
              ? data[0].email_verified_at
              : false,
            userId: data[0].cid,
            username: data[0].username
          };
          // save to store for later use inside components
          this.$store.commit("updateUser", updatedUser);
          this.$store.commit("setUserData", this.userData);
          this.$store.commit("setProfileData", this.profileData);
        },
        error => {
          console.error(error);
        }
      );

      this.db
        .collection("Users")
        .doc(user.uid)
        .onSnapshot(querySnapshot => {
          this.profileData = querySnapshot.data();
          this.userData = querySnapshot.data();
          this.$set(this.profileData, "id", querySnapshot.id);
          this.$set(this.userData, "id", querySnapshot.id);
          this.isProfileDataLoaded = true;
          if (fromCompletion) {
            this.eventBus.$emit("message", {
              msg:
                "Profile Updated Successfully. You will be redirected to your profile",
              type: 0
            });
            setTimeout(() => {
              this.$router.push({
                path: "/" + findyoName(this.userData.displayName) + "/timeline"
              });
            }, 3000);
          }
        });
    },
    loadProfile(displayName) {
      this.db
        .collection("Users")
        .where("displayName", "==", displayName)
        .orderBy("creationTime", "desc")
        .onSnapshot(querySnapshot => {
          if (querySnapshot.docs.length > 0) {
            this.profileData = querySnapshot.docs[0].data();
            this.$set(this.profileData, "uid", querySnapshot.docs[0].id);
          }
        });
    },
    cleanProfile() {
      this.profileData = {};
    },
    getLocations() {
      this.db
        .collection("Locations")
        .get()
        .then(snapShot => {
          let locations = [];
          snapShot.forEach(doc => {
            let data = doc.data();
            data.id = doc.id;
            locations.push(data);
          });
          this.nestLocations(locations);
        })
        .catch(error => {
          console.error(error);
        });
    },
    nestLocations(locations) {
      locations.map(o => {
        if (o.is_province) {
          this.$set(o, "parent_id", o.country_id);
        } else if (o.is_district) {
          this.$set(o, "parent_id", o.province_id);
        } else if (o.is_city) {
          this.$set(o, "parent_id", o.district_id);
        }
      });
      locations.map(o => {
        this.$set(o, "locations", []);
        locations.map(p => {
          if (o.id === p.parent_id) {
            o.locations.push(p);
          }
        });
      });
      this.locations = locations.find(o => o.is_country);
    },
    setChatData(data) {
      this.chatData = data;
    },
    getChatUserList() {
      //debugger
      const userID = this.user.id;
      if (userID) {
        //const self = this;
        // this.db
        //   .collection("Chats")
        //   .where("UserID", "array-contains", userID)
        //   .orderBy("lastUpdatedTime", "desc")
        //   .onSnapshot(
        //     querySnapshot => {
        //       if (querySnapshot.docs.length > 0) {
        //         querySnapshot.forEach(doc => {
        //           let data = doc.data();
        //           this.chatUserList.push({
        //             displayName:
        //               data.Users[0].userID != this.user.uid
        //                 ? data.Users[0].displayName
        //                 : data.Users[1].displayName,
        //             displayPicture:
        //               data.Users[0].userID != this.user.uid
        //                 ? data.Users[0].displayPicture
        //                 : data.Users[1].displayPicture,
        //             chatID: data.chatID,
        //             userID:
        //               data.Users[0].userID != this.user.uid
        //                 ? data.Users[0].userID
        //                 : data.Users[1].userID
        //           });
        //         });
        //       } else {
        //         console.log("No Chats");
        //       }
        //     },
        //     error => {
        //       console.log("Error getting chat user list", error);
        //     }
        //   );
        //chat need to impelement
      } else {
        console.error("Invalid user name");
      }
    }
  },
  created() {
    this.eventBus = eventBus;
    this.db = firebase.firestore();
    this.auth = firebase.auth();

    this.authChanged()
      .then(() => {
        this.getChatUserList();
      })
      .catch(error => {
        console.error(error);
      });
  },
  mounted() {
    this.fb = fbIinit.obj;
    this.eventBus.$on("check-auth", this.authChanged);
    this.eventBus.$on("set-info", this.setInfoObj);
    this.eventBus.$on("load-profile", this.loadProfile);
    this.eventBus.$on("clean-profile", this.cleanProfile);
    this.eventBus.$on("load-profile-uid", this.getUserProfile);
    this.eventBus.$on("set-chat-data", this.setChatData);
    this.loaded = true;
    this.getLocations();
  },
  beforeDestroy() {
    this.eventBus.$off("check-auth", this.authChanged);
    this.eventBus.$off("set-info", this.setInfoObj);
    this.eventBus.$off("load-profile", this.loadProfile);
    this.eventBus.$off("clean-profile", this.cleanProfile);
    this.eventBus.$off("load-profile-uid", this.getUserProfile);
    this.eventBus.$off("set-chat-data", this.setChatData);
  }
};
</script>

<style lang="scss">
@import "./assets/styles/reset.css";
@import "./assets/styles/variables.scss";
@import "./assets/styles/main.scss";
@import "./assets/styles/responsive.scss";
@import "./assets/styles/vue-content-placeholders.css";
</style>
