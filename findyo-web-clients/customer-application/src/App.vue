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
import firebase from 'firebase/app';
import 'firebase/auth';
import { eventBus } from './event-bus.js';
import { fbIinit } from './firebaseInit';
import { findyoName } from './func/usables';

export default {
  name: 'app',
  data() {
    return {
      db: undefined,
      auth: undefined,
      loaded: false,
      user: undefined,
      fb: undefined,
      eventBus: undefined,
      languageId: 0,
      postTypes: [
        {
          typeId: 1,
          label: 'Post as a consumer',
          placeholder: 'Ask for a service you need right now',
          filterLabel: 'Consumer',
        },
        {
          typeId: 2,
          label: 'Post as a freelancer',
          placeholder: 'Let the world know about your service',
          filterLabel: 'Freelancer',
        },
      ],
      infoObject: {
        state: false,
        type: 0,
        title: null,
        message: null,
      },
      profileData: {},
      userData: {},
      isProfileDataLoaded: false,
      locations: {},
      chatData: {},
      chatUserList: [],
    };
  },
  props: {
    firebaseInit: undefined,
  },
  components: {
    Header: () =>
      import(/* webpackChunkName: "Header"*/ '@/components/common/Header'),
    'sidebar-nav': () =>
      import(
        /* webpackChunkName: "sidebar-nav"*/ '@/components/common/SidebarNav'
      ),
    'info-window': () =>
      import(
        /* webpackChunkName: "info-window"*/ '@/components/modals/InfoWindow'
      ),
    Message: () =>
      import(/* webpackChunkName: "Message"*/ '@/components/common/Message'),
    Progress: () =>
      import(/* webpackChunkName: "Progress"*/ '@/components/common/Progress'),
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
        this.auth.onAuthStateChanged((user) => {
          if (user) {
            this.user = user;
            this.getUserProfile(user);
            resolve();
          } else {
            this.user = undefined;
            reject('Error getting logged in user');
          }
        });
      });
    },
    getUserProfile(user, fromCompletion) {
      this.isProfileDataLoaded = false;
      this.db
        .collection('Users')
        .doc(user.uid)
        .onSnapshot((querySnapshot) => {
          this.profileData = querySnapshot.data();
          this.userData = querySnapshot.data();
          this.$set(this.profileData, 'id', querySnapshot.id);
          this.$set(this.userData, 'id', querySnapshot.id);
          this.isProfileDataLoaded = true;
          if (fromCompletion) {
            this.eventBus.$emit('message', {
              msg:
                'Profile Updated Successfully. You will be redirected to your profile',
              type: 0,
            });
            setTimeout(() => {
              this.$router.push({
                path: '/' + findyoName(this.userData.displayName) + '/timeline',
              });
            }, 3000);
          }
          //console.log(this.userData)
        });
    },
    loadProfile(displayName) {
      this.db
        .collection('Users')
        .where('displayName', '==', displayName)
        .orderBy('creationTime', 'desc')
        .onSnapshot((querySnapshot) => {
          if (querySnapshot.docs.length > 0) {
            this.profileData = querySnapshot.docs[0].data();
            this.$set(this.profileData, 'uid', querySnapshot.docs[0].id);
          }
        });
    },
    cleanProfile() {
      this.profileData = {};
    },
    getLocations() {
      this.db
        .collection('Locations')
        .get()
        .then((snapShot) => {
          let locations = [];
          snapShot.forEach((doc) => {
            let data = doc.data();
            data.id = doc.id;
            locations.push(data);
          });
          this.nestLocations(locations);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    nestLocations(locations) {
      locations.map((o) => {
        if (o.is_province) {
          this.$set(o, 'parent_id', o.country_id);
        } else if (o.is_district) {
          this.$set(o, 'parent_id', o.province_id);
        } else if (o.is_city) {
          this.$set(o, 'parent_id', o.district_id);
        }
      });
      locations.map((o) => {
        this.$set(o, 'locations', []);
        locations.map((p) => {
          if (o.id === p.parent_id) {
            o.locations.push(p);
          }
        });
      });
      this.locations = locations.find((o) => o.is_country);
    },
    setChatData(data) {
      this.chatData = data;
    },
    getChatUserList() {
      //debugger
      const userID = this.user.uid;
      if (userID) {
        //const self = this;
        this.db
          .collection('Chats')
          .where('UserID', 'array-contains', userID)
          .orderBy('lastUpdatedTime', 'desc')
          .onSnapshot(
            (querySnapshot) => {
              if (querySnapshot.docs.length > 0) {
                querySnapshot.forEach((doc) => {
                  let data = doc.data();
                  this.chatUserList.push({
                    displayName:
                      data.Users[0].userID != this.user.uid
                        ? data.Users[0].displayName
                        : data.Users[1].displayName,
                    displayPicture:
                      data.Users[0].userID != this.user.uid
                        ? data.Users[0].displayPicture
                        : data.Users[1].displayPicture,
                    chatID: data.chatID,
                    userID:
                      data.Users[0].userID != this.user.uid
                        ? data.Users[0].userID
                        : data.Users[1].userID,
                  });
                });
              } else {
                console.log('No Chats');
              }
            },
            (error) => {
              console.log('Error getting chat user list', error);
            }
          );
      } else {
        console.log('Invalid user name');
      }
    },
  },
  created() {
    this.eventBus = eventBus;
    this.db = firebase.firestore();
    this.auth = firebase.auth();

    this.authChanged()
      .then(() => {
        this.getChatUserList();
      })
      .catch((error) => {
        console.error(error);
      });
  },
  mounted() {
    this.fb = fbIinit.obj;
    this.eventBus.$on('check-auth', this.authChanged);
    this.eventBus.$on('set-info', this.setInfoObj);
    this.eventBus.$on('load-profile', this.loadProfile);
    this.eventBus.$on('clean-profile', this.cleanProfile);
    this.eventBus.$on('load-profile-uid', this.getUserProfile);
    this.eventBus.$on('set-chat-data', this.setChatData);
    this.loaded = true;
    this.getLocations();
  },
  beforeDestroy() {
    this.eventBus.$off('check-auth', this.authChanged);
    this.eventBus.$off('set-info', this.setInfoObj);
    this.eventBus.$off('load-profile', this.loadProfile);
    this.eventBus.$off('clean-profile', this.cleanProfile);
    this.eventBus.$off('load-profile-uid', this.getUserProfile);
    this.eventBus.$off('set-chat-data', this.setChatData);
  },
};
</script>

<style lang="scss">
@import './assets/styles/reset.css';
@import './assets/styles/variables.scss';
@import './assets/styles/main.scss';
@import './assets/styles/responsive.scss';
@import './assets/styles/vue-content-placeholders.css';
</style>
