<template>
  <div class="page profile">
    <div class="banner-wrapper box" v-bind:style="{ backgroundImage: 'url('+coverPhotoUrl+')' }">
      <div
        class="profile-completion-indicator-wrapper white-chip"
        v-if="isMyProfile"
        @click.stop="goToCompletion"
      >
        <div class="full-width">
          <label class="title" v-text="profileCompletionValue+'% completed'"></label>
        </div>
        <div
          class="profile-completion-indicator m-b-1"
        >Click to complete your profile and get verified.</div>
        <div class="progress-bar-wrapper full-width flex h-left">
          <div class="progress-bar" :style="{width: profileCompletionValue+'%'}"></div>
        </div>
      </div>
      <div class="profile-banner-image-wrapepr">
        <UserWidget
          v-if="isProfileDataLoaded"
          class="profile-banner-image"
          :displayName="firstLastName"
          :email="displayName"
          :photoUrl="profilePhotoUrl"
        />
        <label v-if="isMyProfile && isProfileDataLoaded" for="profile-upload" class="update-image"></label>
        <input
          v-if="isMyProfile"
          id="profile-upload"
          type="file"
          v-show="false"
          accept=".png, .jpg, .jpeg"
          @change="addCompressProfilePicture($event)"
        />
      </div>
      <div class="banner-action-wrapper">
        <ul class="banner-action-ul" v-if="isMyProfile">
          <li>
            <label for="cover-upload" class="cover-upload">Update cover</label>
            <input
              id="cover-upload"
              type="file"
              v-show="false"
              accept=".png, .jpg, .jpeg"
              @change="addCompressCoverPhoto($event)"
            />
          </li>
          <li>
            <router-link to="/settings/profile">Edit profile</router-link>
          </li>
        </ul>
      </div>
      <!-- <div class="banner-tabs-wrapper">
        <ul class="banner-tab-ul">
          <li class="banner-tab-li">
            <router-link :to="'timeline'">
              <span class="banner-tab-icon timeline"></span>
              <label class="banner-tab-label">Timeline</label>
            </router-link>
          </li>
          <li class="banner-tab-li">
            <router-link :to="'portfolio'">
              <span class="banner-tab-icon portfolio"></span>
              <label class="banner-tab-label">Portfolio</label>
            </router-link>
          </li>
          <li class="banner-tab-li">
            <span class="banner-tab-icon connections"></span>
            <label class="banner-tab-label">Connections</label>
          </li>
        </ul>
      </div>-->
    </div>
    <div class="feed-wrapper">
      <router-view
        :user="user"
        :db="db"
        :auth="auth"
        :fb="fb"
        :postTypes="postTypes"
        :eventBus="eventBus"
        :categoryHierarchy="categoryHierarchy"
        :locationHierarchy="locationHierarchy"
        :nestedCats="nestedCats"
        :locations="locations"
        :profileSettings="profileSettings"
        :profileData="profileData"
        :userData="userData"
        :isProfileDataLoaded="isProfileDataLoaded"
        :isMyProfile="isMyProfile"
      />
    </div>
  </div>
</template>

<script>
/*eslint-disable*/
import { findyoName } from "./../func/usables";
import Compressor from "compressorjs";

export default {
  name: "profile",
  data() {
    return {
      profileUrl: "",
      categories: [],
      childCategories: [],
      profileSettings: {
        isEditable: false
      },
      profileCompletionValue: 20,
      isMyProfile: false
    };
  },
  props: {
    eventBus: undefined,
    db: undefined,
    auth: undefined,
    user: undefined,
    fb: undefined,
    postTypes: {
      type: Array,
      default() {
        return [];
      }
    },
    profileData: {
      type: Object,
      default() {
        return {};
      }
    },
    userData: {
      type: Object,
      default() {
        return {};
      }
    },
    isProfileDataLoaded: {
      type: Boolean,
      default: false
    },
    locations: {
      type: Object
    }
  },
  components: {
    UserWidget: () => import("@/components/common/UserWidget")
  },
  computed: {
    categoryHierarchy() {
      let categoryHierarchy = [];
      this.mapCategoryHierarchy(this.nestedCats, categoryHierarchy);
      return categoryHierarchy;
    },
    locationHierarchy() {
      let locationHierarchy = []
      this.mapLocationHierarchy(this.locationList, locationHierarchy)
      return locationHierarchy
    },
    locationList() {
      return this.locations.locations ? this.locations.locations : []
    },
    nestedCats() {
      let list = [];
      if (this.categories.length > 0) {
        this.categories.map(o => {
          let childs = [];
          this.childCategories.map(p => {
            if (o.id === p.parent_id) {
              childs.push(p);
            }
          });
          o.childs = childs;
          list.push(o);
        });
      }
      return list;
    },
    coverPhotoUrl() {
      return this.profileData.coverURL
        ? this.profileData.coverURL
        : '';
    },
    profilePhotoUrl() {
      let url = ''
      if (this.isProfileDataLoaded) {
        url = this.profileData.displayPicture
          ? this.profileData.displayPicture
          : ''
      }
      return url;
    },
    displayName() {
      let displayName = ''
      if (this.isProfileDataLoaded) {
        displayName = this.profileData.displayName ? this.profileData.displayName : ''
      }
      return displayName
    },
    firstLastName() {
      let firstLastName = "";
      if (this.isProfileDataLoaded) {
        if (this.profileData.first_name && this.profileData.last_name) {
          firstLastName =
            this.profileData.first_name + " " + this.profileData.last_name;
        } else if (this.profileData.first_name) {
          firstLastName = this.profileData.first_name;
        }
        //  else {
        //   firstLastName = this.user.providerData[0].displayName;
        // }
      }
      return firstLastName;
    }
  },
  methods: {
    findyoName(str) {
      return findyoName(str);
    },
    checkUserForProfile() {
      if (this.$route.params.id !== this.userData.displayName) {
        this.isMyProfile = false
      } else {
        this.isMyProfile = true
      }
    },
    mapCategoryHierarchy(list, categoryHierarchy) {
      list.map(o => {
        if (o.selected) {
          categoryHierarchy.push(o);
          if (o.childs && o.childs.length > 0) {
            this.mapCategoryHierarchy(o.childs, categoryHierarchy);
          }
        }
      });
    },
    getCategories() {
      this.db
        .collection("Categories")
        .get()
        .then(snapShot => {
          this.categories = [];
          this.childCategories = [];
          snapShot.forEach(o => {
            if (o.data().parent_id && o.data().parent_id.trim() !== "") {
              let childObj = {
                id: o.id,
                name_en: o.data().name_en,
                name_sn: o.data().name_sn,
                name_tm: o.data().name_tm,
                parent_id: o.data().parent_id
              };
              this.childCategories.push(childObj);
            }
            let obj = {
              id: o.id,
              name_en: o.data().name_en,
              name_sn: o.data().name_sn,
              name_tm: o.data().name_tm,
              parent_id: o.data().parent_id
            };
            this.categories.push(obj);
          });
        })
        .catch(() => {});
    },
    getHomeStaticData() {
      this.getCategories();
    },
    profileEditState(state) {
      this.profileSettings.isEditable = state;
    },
    goToCompletion() {
      this.$router.push({ name: "completion" });
    },
    addCompressProfilePicture(event) {
      let self = this;
      const file = event.target.files[0];
      // check for accepted file meme types
      if (file.type === "image/png" || file.type === "image/jpeg") {
        new Compressor(file, {
          quality: 0.6,
          convertSize: 50000,
          maxWidth: 240,
          success(compressedFile) {
            // upload db call calls here
            self.uploadProfilePictureToStore(compressedFile);
          },
          error(err) {
            self.eventBus.$emit("message", { msg: err.message, type: 1 });
          }
        });
      } else {
      }
    },
    uploadProfilePictureToStore(compressedFile) {
      //Upload the file
      let self = this;
      let extention;
      let isAccesptableFileType = false;
      if (compressedFile.type === "image/jpeg") {
        extention = "jpg";
        isAccesptableFileType = true;
      } else if (compressedFile.type === "image/png") {
        extention = "png";
        isAccesptableFileType = true;
      }

      if (isAccesptableFileType) {
        const basePath = `Users/${this.user.uid}/profile_picture.${extention}`;
        let snap;
        let task = this.fb
          .storage()
          .ref()
          .child(basePath)
          .put(compressedFile);
        task.on(
          "state_changed",
          function progress(snapshot) {
            snap = snapshot
            let percentage = (snapshot.bytesTransferred / snapshot.totalBytes) * 100
            self.eventBus.$emit('progress', {mode: 'state', percentage: percentage})
          },
          function error(err) {
            self.eventBus.$emit("message", { msg: 'Profile picture did not uploaded successfully', type: 1 });
          },
          function complete(obj) {
            let isPictureExistsInStorage = false
            if (self.user.photoURL) {
              isPictureExistsInStorage = self.user.photoURL
              .toString()
              .includes("firebasestorage.googleapis.com");
            }

            if (!isPictureExistsInStorage) {
              let downloadUrl;
              snap.ref.getDownloadURL().then(function(downloadURL) {
                self.saveNewProfileUrlInUserObject(downloadURL);
              });
            } else {
              self.eventBus.$emit("message", {
                msg: "Profile picture Uploaded Successfully",
                type: 0
              });
              setTimeout(() => {
                location.reload()
              }, 2000)
            }
          }
        );
      }
    },
    saveNewProfileUrlInUserObject(downloadURL) {
      this.user.updateProfile({
        photoURL: downloadURL
      }).then(() => {
          this.eventBus.$emit("message", {
            msg: "Profile picture Uploaded Successfully",
            type: 0
          });
          setTimeout(() => {
            location.reload()
          }, 2000)
        })
        .catch(error => {
          this.eventBus.$emit("message", { msg: error, type: 1 });
        });
    },
    addCompressCoverPhoto(event) {
      let self = this
      this.eventBus.$emit('progress', {mode: 'state', percentage: 0})
      const file = event.target.files[0];
      // check for accepted file meme types
      if (file.type === "image/png" || file.type === "image/jpeg") {
        new Compressor(file, {
          quality: 0.6,
          convertSize: 50000,
          maxWidth: 1440,
          success(compressedFile) {
            // upload db call calls here
            self.uploadCoverPhotoToDB(compressedFile);
          },
          error(err) {
            self.eventBus.$emit("message", { msg: err.message, type: 1 });
          }
        });
      } else {
      }
    },
    uploadCoverPhotoToDB(compressedFile) {
      const basePath = "Users/" + this.user.uid + "/cover_photo.jpg";
      let snap;
      let self = this;
      let task = this.fb
        .storage()
        .ref()
        .child(basePath)
        .put(compressedFile);
      task.on(
        "state_changed",
        function progress(snapshot) {
          snap = snapshot
          let percentage = (snapshot.bytesTransferred / snapshot.totalBytes) * 100
          // if (percentage === 100) {
          //   self.eventBus.$emit('progress', {mode: 'state', percentage: percentage})
          // }
        },
        function error(err) {
          self.eventBus.$emit("message", { msg: 'Cover photo did not uploaded successfully', type: 1 })
        },
        function complete() {
          snap.ref.getDownloadURL().then(function(downloadURL) {
            self.saveNewCoverUrlInDB(downloadURL)
          })
          self.eventBus.$emit('progress', {mode: 'state', percentage: 100})
          self.eventBus.$emit("message", { msg: 'Cover photo uploaded successfully', type: 0 })
        }
      );
    },
    saveNewCoverUrlInDB(downloadURL) {
      this.db
        .collection("Users")
        .doc(this.user.uid)
        .set({ coverURL: downloadURL }, { merge: true })
        .then(() => {
          this.eventBus.$emit("message", {
            msg: "Cover picture uploaded Successfully",
            type: 0
          });
        })
        .catch(error => {
          this.eventBus.$emit("message", { msg: error, type: 1 });
        });
    },
    mapLocationHierarchy(list, locationHierarchy) {
      list.map(o => {
        if (o.selected) {
          locationHierarchy.push(o)
          if (o.locations && o.locations.length > 0) {
            this.mapLocationHierarchy(o.locations, locationHierarchy)
          }
        }
      })
    }
  },
  watch: {
    profileData: {
      immediate: true,
      deep: true,
      handler: function(val, oldVal) {
        if (val && val.displayName && val.displayName.trim() !== '') {
          this.checkUserForProfile()
        }
      }
    }
  },
  beforeMount() {
    this.eventBus.$emit("load-profile", this.$route.params.id);
  },
  mounted() {
    this.getHomeStaticData()
  },
  beforeDestroy() {
    this.eventBus.$emit("clean-profile");
  }
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/variables.scss";
.page.profile {
  max-width: calc(100% - 0px);
  position: relative;
}
.feed-wrapper {
  max-width: 640px;
  display: inline-block;
  width: 100%;
  margin: 50px 0px 0px 0px;
}
.banner-wrapper:after {
  position: absolute;
  content: "";
  bottom: 0px;
  left: 0px;
  width: 100%;
  height: 100px;
  background-image: linear-gradient(to top, $white, rgba(255, 255, 255, 0));
  z-index: 0;
  border-radius: 0px 0px 3px 3px;
}
.banner-action-wrapper {
  position: absolute;
  top: 0px;
  left: 0px;
}
.banner-action-ul {
  display: block;
  padding: 10px;
}
.banner-action-ul > li {
  position: relative;
  display: inline-block;
  padding: 10px 20px;
  background-color: rgba(255, 255, 255, 1);
  line-height: auto;
  font-weight: 500;
  font-size: 12px;
  border-radius: 4px;
  cursor: pointer;
  transition: 0.2s;
  letter-spacing: 1px;
}
.banner-action-ul > li:hover {
  background-color: rgba(255, 255, 255, 1);
  transition: 0.2s;
}
.banner-action-ul > li:not(:last-child) {
  margin: 0px 10px 0px 0px;
}
.banner-tab-icon {
  display: inline-block;
  width: 100%;
  height: 18px;
  background-size: 18px;
  background-position: center;
  background-repeat: no-repeat;
}
.timeline {
  background-image: url(./../assets/img/svg/timeline.svg);
}
.connections {
  background-image: url(./../assets/img/svg/connections.svg);
}
.portfolio {
  background-image: url(./../assets/img/svg/portfolio.svg);
}
.banner-tab-label {
  font-size: 12px;
  font-weight: 600;
  margin-top: 10px;
  display: block;
  text-transform: uppercase;
}
.banner-tabs-wrapper {
  position: absolute;
  bottom: -50px;
  left: 0px;
  width: 100%;
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1;
}
.banner-tab-ul {
  display: block;
  background-color: $white;
}
.banner-tab-li {
  float: left;
  width: 140px;
  text-align: center;
  padding: 10px 0px;
  cursor: pointer;
}
.banner-tab-li > a {
  display: block;
  opacity: 0.5;
  transition: 0.2s;
}
.banner-tab-li > a:hover,
.banner-tab-li > a.router-link-exact-active.router-link-active {
  opacity: 1;
  transition: 0.2s;
}
.update-image {
  background-image: url(./../assets/img/icons/camera.svg);
  position: absolute;
  top: 20px;
  left: 80px;
}
.profile-completion-indicator-wrapper {
  position: absolute;
  bottom: 140px;
  left: 10px;
  width: 300px;
  text-transform: none;
}
.banner-action-ul > li:first-child {
  padding: 0px 0px 0px 0px;
}
.cover-upload {
  position: relative;
  display: inline-block;
  padding: 10px 20px;
}
</style>
