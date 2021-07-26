<template>
  <div
    class="add-post-container"
    :class="{'sticky-add-post': !isArticle ? isAddPostFocused.state || postContent.trim() !== '' : true}"
  >
    <div
      class="post-overlay-before"
      :class="{'post-overlay': !isArticle ? isAddPostFocused.state : true}"
      @click="postFocus(false)"
    ></div>
    <div
      class="add-post box-nue"
      :class="{'add-post-focused': !isArticle ? isAddPostFocused.state : true}"
      @click.stop="postFocus(true)"
    >
      <div class="header-row">
        <UserImage :userImage="profilePhotoUrl" />
        <div class="full-width add-post-selection-block">
          <ul class="post-type-ul">
            <li
              class="switch-nue"
              :class="[{'selected': postType.typeId === selectedType.typeId}, postTypeLabel(postType.typeId)]"
              v-for="postType in postTypes"
              :key="postType.typeId"
              @click.stop="selectType(postType.typeId)"
            >{{postType.label}}</li>
            <!-- <li class="switch-nue"
              :class="[{'selected': !isArticle ? postType.typeId === selectedType.typeId : postType.typeId === content.postType}, postTypeLabel(postType.typeId), {'disable': isArticle}]"
              v-for="postType in postTypes"
              :key="postType.typeId"
              @click.stop="selectType(postType.typeId)"
            >{{postType.label}}</li>-->
          </ul>
          <div class="cat-loc-wrapper full-width flex h-start">
            <div class="set-category-wrapper flex v-center">
              <div
                class="category none-clickable-label"
                :class="{'validate-error': isEmptyCategory}"
                v-if="breadcrumb.length === 0"
                @click.stop="getCategorySelectionModal"
              >
                Category*
                <span class="dim">(required)</span>
              </div>
              <ul
                class="selected-breadbrumb clickable-label"
                v-if="breadcrumb.length > 0"
                @click.stop="getCategorySelectionModal"
              >
                <li
                  class="inline-flex v-center"
                  v-show="item.selected"
                  v-for="(item, index) in breadcrumb"
                  :key="index"
                >
                  <span>{{item.name_en}}</span>
                </li>
              </ul>
            </div>
            <div class="set-category-wrapper flex v-center">
              <div
                class="category none-clickable-label"
                v-if="locationBreadcrumb.length === 0"
                @click.stop="getLocationSelectionModal"
              >
                Location
                <span class="dim">(optional)</span>
              </div>
              <ul
                class="selected-breadbrumb selected-location-breadbrumb clickable-label"
                v-if="locationBreadcrumb.length > 0"
                @click.stop="getLocationSelectionModal"
              >
                <li
                  class="inline-flex v-center"
                  v-show="item.selected"
                  v-for="(item, index) in locationBreadcrumb"
                  :key="index"
                >
                  <span>{{item.name_en}}</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="body-row">
        <p
          v-if="!isArticle ? !isAddPostFocused.state : false"
          class="input-placeholder"
          @click="postFocus(true)"
        >{{typePlaceholder}}</p>
        <!-- {{content.postContent}} -->
        <textarea
          v-show="!isArticle && isAddPostFocused.state"
          ref="addPostTextarea"
          class="add-post-textarea"
          :class="{'content': postContent.trim() !== '', 'add-post-textarea-focused': isPostFocused2}"
          v-autosize="postContent"
          v-model="postContent"
          :placeholder="typePlaceholder"
        />
        <textarea
          v-show="isArticle"
          ref="addPostTextareaNew"
          class="add-post-textarea"
          :class="{'content': postContent.trim() !== '', 'add-post-textarea-focused': isPostFocused2}"
          v-autosize="postContent"
          v-model="content.postContent"
          :placeholder="typePlaceholder"
        />
        <ul class="media-array" v-if="!isArticle">
          <li v-for="(file, idx) in files" :key="idx">
            <base-image-input :file="file" :files="files" />
          </li>
        </ul>
        <ul class="media-array" v-if="isArticle">
          <li v-for="(imageUrl, idx) in imageUrls" :key="idx">
            <img class="disable" :src="imageUrl" />
          </li>
        </ul>
      </div>
      <div class="add-post-foot-row full-width flex v-center h-between">
        <ul class="add-post-actions">
          <li v-if="!isArticle">
            <div class="input-charm-wrapper">
              <input
                type="file"
                @change="addMedia(1, $event)"
                multiple
                class="input-charm input-charm-photo"
                id="input-charm-photo"
                accept=".gif, .jpg, .jpeg, .png"
              />
              <label for="input-charm-photo" class="label-charm photo-icon"></label>
            </div>
          </li>
          <!-- <li>
            <div class="input-charm-wrapper">
              <input type="file" @change="addMedia(2, $event)" multiple class="input-charm input-charm-photo" id="input-charm-photo"/>
              <label for="input-charm-photo" class="label-charm video-icon"></label>
            </div>
          </li>-->
        </ul>
        <Button
          class="pull-right"
          :label="!isArticle ? 'Publish' : 'Update'"
          :action="post"
          :loading="isPosting"
        />
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import firebase from "firebase/app";
import "firebase/firestore";
import "firebase/auth";
import "firebase/storage";
import Compressor from "compressorjs";
import { resolve } from "url";

export default {
  name: "add-post",
  data() {
    return {
      selectedType: {},
      postType: "",
      typePlaceholder: "",
      postContent: "",
      isPostFocused2: false,
      files: [],
      filesCompressed: [],
      compressedCounter: 0,
      isPosting: false,
      isEmptyCategory: false,
      postCategory: [],
      postLocation: []
    };
  },
  components: {
    UserImage: () => import("@/components/common/UserImage"),
    Button: () => import("@/components/inputs/Button"),
    "base-image-input": () => import("@/components/common/BaseImageInput")
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
    isAddPostFocused: {
      type: Object
    },
    isArticle: Boolean,
    isEditable: {
      type: Object
    },
    content: {
      type: Object,
      default: function() {
        return {};
      }
    },
    imageUrls: {
      type: Array
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
    profileData: {
      type: Object,
      default() {
        return {};
      }
    },
    nestedCats: {
      type: Array
    },
    nestedLocs: {
      type: Array
    }
  },
  computed: {
    userData() {
      return this.$store.state.user.userData;
    },
    profilePhotoUrl() {
      let url;
      if (this.userData) {
        url = this.profileData.displayPicture
          ? this.profileData.displayPicture
          : "";
      }
      return url;
    },
    displayName() {
      let displayName = "";
      if (this.userData) {
        displayName = this.profileData.displayName
          ? this.profileData.displayName
          : this.user.providerData[0].displayName;
      }
      return displayName;
    }
  },
  methods: {
    imagetoBlob() {
      console.log(this.$refs.imgPrv);
    },
    postTypeLabel(typeId) {
      let type;
      if (typeId === 1) {
        type = "consumer";
      } else if (typeId === 2) {
        type = "seller";
      }
      return type;
    },
    postFocus(state) {
      if (this.isArticle) {
        if (!state) {
          this.$set(this.isEditable, "state", state);
          this.selectType(1);
        }
      } else {
        if (state) {
          this.$set(this.isAddPostFocused, "state", state);
          setTimeout(() => {
            this.isPostFocused2 = true;
            this.$refs.addPostTextarea.focus();
          }, 100);
        } else {
          if (this.files.length > 0 || this.postContent.trim() !== "") {
          } else {
            this.isPostFocused2 = false;
            setTimeout(() => {
              this.$set(this.isAddPostFocused, "state", state);
            }, 200);
          }
        }
      }
    },
    selectType(id) {
      this.selectedType = this.postTypes.find(o => {
        return o.typeId === id;
      });
      this.typePlaceholder = this.selectedType.placeholder;
    },
    // selectType(id) {
    //   if (!this.isArticle) {
    //     this.selectedType = this.postTypes.find(o => {
    //       return o.typeId === id
    //     })
    //     this.typePlaceholder = this.selectedType.placeholder
    //   }
    // },
    addMedia(type, event) {
      if (type === 1) {
        event.target.files.forEach(o => {
          if (
            o.type === "image/png" ||
            o.type === "image/jpeg" ||
            o.type === "image/gif"
          ) {
            this.files.push(o);
          } else {
          }
        });
      }
    },
    uploadAttachments(file, post_id, index) {
      return new Promise((resolve, reject) => {
        const basePath = "Posts/" + post_id + "/" + index + ".jpg";
        //Upload the file
        let task = this.fb
          .storage()
          .ref()
          .child(basePath)
          .put(file);
        task.on(
          "state_changed",
          function progress(snapshot) {
            let percentage =
              (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
            //uploader.value = percentage
            // console.log(percentage)
          },
          function error(err) {
            console.log(err);
            alert("File are not uploaded successfully");
          },
          function complete() {
            console.log("image " + index + " added successfully");
          }
        );
      });
    },
    resetNestedCats(arr) {
      arr.map(o => {
        this.$set(o, "selected", false);
        if (o.childs && o.childs.length > 0) {
          this.resetNestedCats(o.childs);
        }
      });
    },
    resetNestedLocs(arr) {
      arr.map(o => {
        this.$set(o, "selected", false);
        if (o.childs && o.childs.length > 0) {
          this.resetNestedLocs(o.childs);
        }
      });
    },
    postToDB() {
      this.isPosting = true;
      if (this.user) {
        this.postContent;
        let canPost =
          this.filesCompressed.length === 0 && this.postContent.trim() === ""
            ? false
            : true;
        if (canPost) {
          this.db
            .collection("User_Posts")
            .add({
              userId: this.user.uid,
              photoUrl: this.profilePhotoUrl,
              userName: this.displayName,
              postContent: this.postContent,
              postType: this.selectedType.typeId,
              ImageCount: this.filesCompressed.length,
              category: this.postCategory,
              location:
                this.postLocation.length > 0 ? this.postLocation[0] : [],
              contactOption:
                this.user.phoneNumber != null
                  ? this.user.phoneNumber
                  : this.user.email
            })
            .then(doc => {
              this.postContent = "";
              this.isPostFocused2 = false;
              this.breadcrumb.splice(0, this.breadcrumb.length);
              this.locationBreadcrumb.splice(0, this.locationBreadcrumb.length);
              this.postCategory = [];
              this.postLocation = [];
              this.resetNestedCats(this.nestedCats);
              this.resetNestedLocs(this.nestedLocs);
              setTimeout(() => {
                this.$set(this.isAddPostFocused, "state", false);
              }, 200);
              if (this.filesCompressed.length > 0) {
                for (let i = 0; i < this.filesCompressed.length; i++) {
                  this.uploadAttachments(
                    this.filesCompressed[i],
                    doc.id,
                    i + 1
                  );
                }
              }
              this.files = [];
              this.eventBus.$emit("message", {
                msg: "Your post has been successfully published",
                type: 0
              });
              this.isPosting = false;
            })
            .catch(err => {
              this.eventBus.$emit("message", { msg: err, type: 1 });
              this.isPosting = false;
            });
        } else {
          this.eventBus.$emit("message", {
            msg: "You cannot publish empty contents",
            type: 1
          });
          this.isPosting = false;
        }
      } else {
        this.eventBus.$emit("message", {
          msg: "You are not authorized",
          type: 1
        });
        this.isPosting = false;
      }
    },
    updateDB() {
      this.isPosting = true;
      if (this.user) {
        this.postContent;
        let canPost =
          this.filesCompressed.length === 0 &&
          this.content.postContent.trim() === ""
            ? false
            : true;
        if (canPost) {
          let Pid = this.content.id.toString();
          this.db
            .collection("User_Posts")
            .doc(Pid)
            .set(
              {
                userId: this.user.uid,
                photoUrl: this.profilePhotoUrl,
                userName: this.displayName,
                postContent: this.content.postContent,
                postType: this.selectedType.typeId,
                ImageCount: this.filesCompressed.length,
                category: this.postCategory,
                location:
                  this.postLocation.length > 0 ? this.postLocation[0] : []
              },
              { merge: true }
            )
            .then(doc => {
              this.postContent = "";
              this.isPostFocused2 = false;
              setTimeout(() => {
                this.$set(this.isEditable, "state", false);
              }, 200);

              if (this.filesCompressed.length > 0) {
                for (let i = 0; i < this.filesCompressed.length; i++) {
                  this.uploadAttachments(
                    this.filesCompressed[i],
                    doc.id,
                    i + 1
                  );
                }
              }
              this.isPosting = false;
            })
            .catch(err => {
              this.isPosting = false;
            });
        } else {
          this.eventBus.$emit("message", {
            msg: "You cannot publish empty contents",
            type: 1
          });
          this.isPosting = false;
        }
      } else {
        this.eventBus.$emit("message", {
          msg: "You are not authorized",
          type: 1
        });
        this.isPosting = false;
      }
      // if (this.user) {
      //   let Pid = this.content.id.toString()
      //   this.db.collection("User_Posts").doc(Pid)
      //     .set({
      //       userId: this.user.uid,
      //       photoUrl: this.profilePhotoUrl,
      //       userName: this.displayName,
      //       postContent: this.content.postContent,
      //       postType: this.selectedType.typeId,
      //       ImageCount: this.filesCompressed.length,
      //       category: this.postCategory,
      //       location: this.postLocation.length > 0 ? this.postLocation[0] : []
      //     },
      //     { merge: true }
      //     )
      //     .then(doc => {
      //       this.postContent = ''
      //       this.isPostFocused2 = false
      //       setTimeout(() => {
      //         this.$set(this.isEditable, 'state', false)
      //       }, 200)

      //       if(this.filesCompressed.length > 0){
      //         for(let i=0;i<this.filesCompressed.length;i++){

      //             this.uploadAttachments(this.filesCompressed[i],doc.id,i+1);
      //         }
      //       }
      //       this.isPosting = false
      //     })
      //     .catch(err => {
      //       this.isPosting = false
      //     })
      // } else {
      //   this.isPosting = false
      // }
    },
    compress(file) {
      let self = this;
      new Compressor(file, {
        quality: 0.6,
        convertSize: 50000,
        maxWidth: 800,
        success(result) {
          self.compressedCounter = self.compressedCounter + 1;
          self.filesCompressed.push(result);
          if (self.files.length === self.compressedCounter) {
            if (self.isArticle) {
              self.updateDB();
              //console.log(self.data)
            } else {
              if (self.breadcrumb.length === 0) {
                self.isEmptyCategory = true;
                self.isPosting = false;
                self.eventBus.$emit("message", {
                  msg: "You cannot publish without selecting a category",
                  type: 1
                });
              } else {
                self.postToDB();
              }
            }
          }
        },
        error(err) {
          console.log(err.message);
        }
      });
    },
    post() {
      this.filesCompressed = [];
      this.compressedCounter = 0;
      this.isPosting = true;
      this.files.map(o => {
        this.compress(o);
      });
      if (this.breadcrumb.length === 0) {
        this.isEmptyCategory = true;
        this.isPosting = false;
        this.eventBus.$emit("message", {
          msg: "You cannot publish without selecting a category",
          type: 1
        });
      } else {
        this.isEmptyCategory = false;
        if (this.files.length === 0) {
          if (this.isArticle) {
            this.updateDB();
          } else {
            this.setPostCategory();
            this.postLocation = JSON.parse(
              JSON.stringify(this.locationBreadcrumb)
            );
            this.setPostLocations([this.postLocation[0]]);
            this.postToDB();
          }
        }
      }
    },
    setPostCategory() {
      let array = JSON.parse(JSON.stringify(this.breadcrumb));
      let category = array[0];
      delete category["selected"];
      if (category.childs && category.childs.length > 0) {
        let deselected_child_list = [];
        category.childs.map((o, i) => {
          if (!o.selected) {
            deselected_child_list.push(i);
          }
          delete o["selected"];
        });
        deselected_child_list.sort(function(a, b) {
          return b - a;
        });
        deselected_child_list.map(idx => {
          category.childs.splice(idx, 1);
        });
      }
      this.postCategory = category;
    },
    setPostLocations(array) {
      let location = array[0] ? array : [];
      location.map((o, i) => {
        if (!o.selected) {
          location.splice(i, 1);
        }
        delete o["selected"];
        delete o["parent_id"];
        delete o["country_id"];
        delete o["province_id"];
        delete o["district_id"];
        delete o["is_province"];
        delete o["is_district"];
        delete o["is_city"];
        if (o.locations && o.locations.length > 0) {
          this.setPostLocations(o.locations);
        }
      });
    },
    getCategorySelectionModal() {
      this.$set(this.isCategorySelectModal, "state", true);
    },
    getLocationSelectionModal() {
      this.$set(this.isLocationSelectModal, "state", true);
    }
  },
  mounted() {
    this.selectType(1);
    if (this.isArticle) {
      this.$refs.addPostTextarea.focus();
      setTimeout(() => {
        this.isPostFocused2 = true;
        this.$refs.addPostTextareaNew.focus();
      }, 100);
    }

    if (this.content && this.content.postType) {
      this.eventBus.$emit("set-catHierarchy", this.content.category);
      this.selectType(this.content.postType);
    }
  },
  watch: {
    breadcrumb: {
      immediate: true,
      deep: true,
      handler: function(val, oldVal) {
        this.isEmptyCategory = false;
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.body-row {
  display: block;
  margin-top: 20px;
}
.foot-row {
  margin-top: 10px;
}
.add-post-focused {
  z-index: 999;
}
.input-placeholder {
  display: inline-block;
  width: 100%;
  padding: 10px;
  box-sizing: border-box;
  cursor: text;
}
.add-post-textarea {
  min-height: 72px;
}
.photo-icon {
  background-image: url(./../../assets/img/svg/gallery.svg);
}
.photo-icon:hover,
.photo-icon.active {
  background-image: url(./../../assets/img/svg/gallery-active.svg);
}
.video-icon {
  background-image: url(./../../assets/img/svg/camera.svg);
  background-size: 24px;
}
.video-icon:hover,
.video-icon.active {
  background-image: url(./../../assets/img/svg/camera-active.svg);
}
.add-post-actions {
  display: inline-block;
}
.add-post-actions li {
  display: inline-block;
}
.add-post-actions li:not(:last-child) {
  margin: 0px 10px 0px 0px;
}
.media-array {
  display: inline-block;
  width: 100%;
  margin: 10px -5px 0px -5px;
  li {
    float: left;
    width: 25%;
    padding: 0px 5px 5px 5px;
    box-sizing: border-box;
    .base-image-input {
      height: 100px;
      width: 100%;
      border-radius: 4px;
    }
  }
}
.add-post-container.sticky-add-post {
  position: sticky;
  top: 60px;
  z-index: 999;
}
.set-category-wrapper {
  height: 19px;
  margin: 10px 0px 0px 20px;
}
.selected-breadbrumb > li {
  padding: 0px 0px;
  text-transform: lowercase;
}
.selected-breadbrumb > li:not(:last-child):after {
  content: url(./../../assets/img/svg/chevron-right.svg);
  position: absolute;
  width: 10px;
  height: 14px;
  background-size: 10px;
  background-position: center;
  background-repeat: no-repeat;
  right: -20px;
  top: 5px;
}
.set-category-wrapper > .category {
  display: inline-block;
  box-sizing: border-box;
  padding: 3px 5px 2px 5px;
  border-radius: 4px;
}
.set-category-wrapper .selected-breadbrumb {
  cursor: pointer;
}
.set-category-wrapper .selected-breadbrumb > li {
  height: auto;
}
.selected-breadbrumb > li:not(:last-child):after {
  top: 1px;
  right: -15px;
}
.selected-breadbrumb > li:not(:last-child) {
  margin: 0px 20px 0px 0px;
}
.category-selection-modal .selected-breadbrumb > li {
  padding: 0px 10px;
}
.selected-location-breadbrumb {
  position: relative;
}
.selected-location-breadbrumb:before {
  content: "/";
  position: absolute;
  top: -3px;
  left: -12px;
  font-size: 18px;
  font-weight: 700;
}
</style>