<template>
  <div class="page settings completion">
    <div class="row box settings-inner-wrapper">
      <div class="settings-list">
        <div class="search-settings" v-if="currentPage">
          <div
            class="settings-single-item-list-wrapper"
            v-for="(page, index) in completion"
            :key="index"
            :class="{'active': page.page_name === currentPage.page_name}"
          >
            <label
              :to="'/settings/profile'"
              v-text="page.page_name"
              :class="{'active': page.page_name === currentPage.page_name}"
            ></label>
          </div>
        </div>
        <content-placeholders v-else class="page-item-placeholder flex v-center">
          <content-placeholders-img v-for="(item, index) in 3" :key="index" />
        </content-placeholders>
      </div>
      <div class="settings-sub-wrapper">
        <div class="setting-item-header">
          <!-- <div class="setting-item-name" v-text="currentPage.page_name"></div> -->
        </div>
        <div v-if="currentPage" class="inner-settings-content-wrapper m-t-2">
          <div
            class="inner-settings-item display-inline full-width"
            v-for="(field, index) in currentPage.fields"
            :key="index"
          >
            <div class="display-inline full-width">
              <div class="title" v-text="field.label"></div>
            </div>
            <div class="display-inline full-width input-wrapper m-b-3">
              <input
                v-if="field.type === 'single-text'"
                v-model="field.value"
                type="text"
                class="display-inline full-width"
                :class="{'error': field.error}"
              />
              <input
                v-if="field.type === 'single-email'"
                v-model="field.value"
                type="email"
                class="display-inline full-width"
                :disabled="!isRegisteredOnPhone"
                :class="{'error': field.error}"
              />
              <datepicker
                v-if="field.type === 'single-date'"
                v-model="field.value"
                name="dateofbirth"
                :format="'dd MMM yyyy'"
                :maximum-view="'year'"
              ></datepicker>
              <div
                v-if="field.type === 'numeric-array'"
                class="display-inline full-width input-wrapper m-b-3"
              >
                <div
                  class="flex full-width input-wrapper v-center m-b-1"
                  v-for="(innerField, innerIndex) in field.value"
                  :key="innerIndex"
                >
                  <input
                    v-if="innerField.type === 'tel'"
                    type="tel"
                    class="display-inline full-width"
                    v-model="innerField.value"
                    :disabled="innerIndex === 0 && isRegisteredOnPhone"
                    :class="{'error': field.error}"
                  />
                  <button
                    class="hollow"
                    v-if="(field.value.length - 1 !== innerIndex) && !(innerIndex === 0 && isRegisteredOnPhone)"
                    style="margin-left: 10px;"
                    @click.stop="removeElement(field.value, innerIndex)"
                  >Remove</button>
                  <button
                    class="hollow"
                    v-if="field.value.length - 1 === innerIndex"
                    style="margin-left: 10px;"
                    @click.stop="addElement(field.value, innerField)"
                  >Add</button>
                </div>
              </div>
              <textarea
                v-if="field.type === 'single-textarea'"
                v-model="field.value"
                class="display-inline full-width"
                :class="{'error': field.error}"
              />
              <div
                v-if="field.type === 'input-array'"
                class="display-inline full-width input-wrapper m-b-3"
              >
                <div
                  class="display-inline flex input-wrapper m-b-1"
                  v-for="(innerField, innerIndex) in field.value"
                  :key="innerIndex"
                >
                  <label class="input-wrapper-label flex v-center" v-text="innerField.label"></label>
                  <input
                    v-if="innerField.type === 'single-text'"
                    type="text"
                    class="display-inline full-width"
                    v-model="innerField.value"
                    :class="{'error': field.error}"
                  />
                  <input
                    v-if="innerField.type === 'single-numeric'"
                    type="number"
                    class="display-inline full-width"
                    v-model="innerField.value"
                    :class="{'error': field.error}"
                  />
                </div>
              </div>
              <!-- http://www.vue-tags-input.com/#/examples/autocomplete -->
              <vue-tags-input
                v-if="field.type === 'text-array'"
                v-model="field.tag"
                :tags="field.value"
                @tags-changed="newTags => field.value = newTags"
                :autocomplete-items="filteredItems"
              />
            </div>
          </div>
          <div class="inner-settings-button-wrapper">
            <Button
              class="hollow pull-right"
              style="margin-right: 10px;"
              :label="isFirstPage ? 'Cancel' : 'Back'"
              :action="cancelAction"
              :disabled="saveDisabled"
            />
            <Button
              class="pull-right"
              :label="isLastPage ? 'Save' : 'Next'"
              :action="okAction"
              :disabled="saveDisabled"
              :loading="isUsernameValidating"
            />
          </div>
        </div>
        <div v-else class="inner-settings-content-wrapper m-t-2">
          <content-placeholders class="m-b-4" v-for="(item, index) in 5" :key="index">
            <content-placeholders-heading />
          </content-placeholders>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import axios from "axios";
import { findyoName } from "./../func/usables";
import Datepicker from "vuejs-datepicker";
import complete from "@/assets/javascript/mixins/complete.mixin.js";
import user from "@/assets/javascript/api/user";

export default {
  name: "completion",
  mixins: [complete, user],
  components: {
    Button: () => import("@/components/inputs/Button"),
    VueTagsInput: () => import("@johmun/vue-tags-input"),
    Datepicker
  },
  data() {
    return {
      tag: "",
      uiLayout: undefined,
      completionProcessed: [],
      currentPage: undefined,
      currentPageIndex: 0,
      autocompleteItems: [
        {
          text: "Spain"
        },
        {
          text: "France"
        },
        {
          text: "USA"
        },
        {
          text: "Germany"
        },
        {
          text: "China"
        }
      ],
      saveDisabled: false,
      isUsernameValidating: false
    };
  },
  props: {
    eventBus: undefined,
    db: undefined,
    auth: undefined
    // user: undefined
  },
  computed: {
    dobFormat(date) {
      return moment(date).format("MMMM Do YYYY, h:mm:ss a");
    },
    isFirstPage() {
      return this.currentPageIndex === 0 ? true : false;
    },
    isLastPage() {
      return this.currentPageIndex === this.completion.length - 1
        ? true
        : false;
    },
    filteredItems() {
      return this.autocompleteItems.filter(
        i => i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1
      );
    },
    isRegisteredOnPhone() {
      return this.user.registerMethod === "email"
        ? false
        : this.user.providerData[0].providerId === "phone"
        ? true
        : false;
    },
    user() {
      return this.$store.state.user.user;
    },
    userData() {
      return this.$store.state.user.userData;
    }
  },
  methods: {
    checkUsername(username, successCallback) {
      // return axios.post(
      //   process.env.VUE_APP_ROOT_API + "/checkUserNameAlreadyExists",
      //   {
      //     UserName: username
      //   }
      // );
      // no api call for this yet.
      return new Promise((resolve, reject) => {
        const response = {
          data: null
        };
        resolve(response);
      });
    },
    okAction() {
      this.validateRequiredFields();
      let errorObject = this.currentPage.fields.find(o => o.error);
      if (errorObject && errorObject.error) {
        this.eventBus.$emit("message", {
          msg: "Please fill the required fields first",
          type: 1
        });
        return;
      }
      if (this.currentPageIndex === 0) {
        this.isUsernameValidating = true;
        let username = this.currentPage.fields.find(
          o => o.label === "Username"
        );
        const regex = /[A-Z0-9-!$%^&*()+|~=`{}\[\]:";'<.>?,\/]/g;
        const found = username.value.match(regex);
        if (
          found ||
          username.value.includes(" ") ||
          username.value.includes(" ")
        ) {
          this.eventBus.$emit("message", {
            msg: `Username can only contain lowercase charactres and an underscore. Ex: john_doe`,
            type: 1
          });
          this.isUsernameValidating = false;
        } else {
          let checkUsernamePromise = this.checkUsername(username.value);
          checkUsernamePromise
            .then(response => {
              if (!response.data) {
                this.isUsernameValidating = false;
                this.currentPageIndex = this.currentPageIndex + 1;
                this.currentPage = this.completion[this.currentPageIndex];
              } else {
                this.isUsernameValidating = false;
                this.eventBus.$emit("message", {
                  msg: `Username already exists. Try another username`,
                  type: 1
                });
              }
            })
            .catch(error => {
              this.eventBus.$emit("message", {
                msg: `Error when checking username availability`,
                type: 1
              });
              this.isUsernameValidating = false;
            });
        }
      } else if (this.currentPageIndex < this.completion.length - 1) {
        this.currentPageIndex = this.currentPageIndex + 1;
        this.currentPage = this.completion[this.currentPageIndex];
        this.isUsernameValidating = false;
      } else {
        this.saveProfileCompletion();
        this.isUsernameValidating = false;
      }
    },
    validateRequiredFields() {
      this.completion.map(o => {
        o.fields.map(p => {
          if (p.required && !p.value) {
            this.$set(p, "error", true);
          } else {
            this.$set(p, "error", undefined);
          }
        });
      });
    },
    cancelAction() {
      if (this.currentPageIndex > 0) {
        this.currentPageIndex = this.currentPageIndex - 1;
        this.currentPage = this.completion[this.currentPageIndex];
      }
    },
    replaceUnderscoresToNormal(obj) {
      const result = {};
      Object.keys(obj).forEach(function(key) {
        result[key.replace(/_/g, "").toLowerCase()] = obj[key];
      });
      return result;
    },
    replaceNormalToUnderscores(obj) {
      const result = {};
      Object.keys(obj).forEach(function(key) {
        result[this.objectKeyRename(key)] = obj[key];
      });
      return result;
    },
    async saveProfileCompletion() {
      this.saveDisabled = true;
      let saveObj = this.makeProfileSaveObj();
      saveObj.phoneNumber =
        Array.isArray(saveObj.phoneNumber) && saveObj.phoneNumber.length
          ? saveObj.phoneNumber[0]
          : saveObj.phoneNumber
          ? saveObj.phoneNumber
          : "";
      this.$set(saveObj, "id", this.user.id);
      this.$set(saveObj, "cid", this.user.userId);
      const objectArray = Object.entries(saveObj);
      objectArray.forEach(([key, value]) => {
        if (value === undefined) {
          delete saveObj[key];
        }
      });
      saveObj = this.replaceUnderscoresToNormal(saveObj);
      try {
        await this.updateUser(
          {
            data: saveObj,
            url: "updateuser",
            method: "PUT"
          },
          async response => {
            await response.data;
            if (response.status === 200) {
              const { data } = await response;
              const updatedUser = {
                ...user,
                displayName: data.displayname ?? data[0].displayname,
                phoneNumber: data.phone,
                emailVerified: data.email_verified_at
                  ? data.email_verified_at
                  : false,
                userId: data.cid,
                username: data.username
              };
              this.$store.commit("updateUser", updatedUser);

              const updatedUserData = {};
              //set userData
              updatedUserData.creationTime = data.created_at;
              updatedUserData.job_title = data.job_title;
              updatedUserData.address = data.address;
              updatedUserData.postal_code = data.postal_code;
              updatedUserData.displayName =
                data.displayname ?? data.displayname;
              updatedUserData.username = data.username;
              updatedUserData.phoneNumber = data.phone;
              updatedUserData.district = data.district;
              updatedUserData.email = this.user.email;
              updatedUserData.country = data.country;
              updatedUserData.last_name = data.lastname;
              updatedUserData.province = data.province;
              updatedUserData.emailVerified = data.email_verified_at
                ? data.email_verified_at
                : false;
              updatedUserData.first_name = data.firstname;
              updatedUserData.city = data.city;
              updatedUserData.id = this.user.id;
              this.$store.commit("setUserData", updatedUserData);
              this.$router.push({ name: "home" });
            }
          },
          error => {
            console.error(error);
            this.saveDisabled = false;
            this.eventBus.$emit("message", {
              msg: "something went wrong!",
              type: 1
            });
          }
        );
      } catch (error) {
        this.saveDisabled = false;
        this.eventBus.$emit("message", { msg: error, type: 1 });
      }
    },
    makeProfileSaveObj() {
      this.completionProcessed = [];
      this.mapObject(this.completion);
      let obj = {};
      this.completionProcessed.map(o => {
        this.$set(obj, Object.keys(o), Object.values(o)[0]);
      });
      // if (obj["first_name"] && obj["last_name"]) {
      //   this.$set(
      //     obj,
      //     "displayName",
      //     findyoName(obj["first_name"] + " " + obj["last_name"])
      //   );
      // } else if (obj["first_name"]) {
      //   this.$set(obj, "displayName", `${findyoName(obj["first_name"])}`)
      // } else if (obj["username"]) {
      //   this.$set(obj, "displayName", `${findyoName(obj["username"])}`)
      // }
      if (obj["username"]) {
        this.$set(obj, "displayName", `${findyoName(obj["username"])}`);
      }
      if (obj["contact_numbers"]) {
        this.$set(obj, "phoneNumber", obj["contact_numbers"]);
        delete obj["contact_numbers"];
      }
      if (!this.isRegisteredOnPhone) {
        delete obj["email"];
      }

      return obj;
    },
    objectKeyRename(string) {
      return string
        .toLowerCase()
        .split(" ")
        .join("_");
    },
    mapObject(arr) {
      arr.map(o => {
        if (o.fields) {
          o.fields.map(p => {
            let label;
            if (
              p.type === "single-text" ||
              p.type === "single-email" ||
              p.type === "single-textarea" ||
              p.type === "single-numeric"
            ) {
              label = this.objectKeyRename(p.label);
              this.completionProcessed.push({
                [label]: p.value
              });
            } else if (p.type === "numeric-array") {
              if (p.value && p.value.length > 0) {
                let numbers = [];
                p.value.map(q => {
                  numbers.push(q.value);
                });
                let num_list = {};
                label = this.objectKeyRename(p.label);
                this.$set(num_list, label, numbers);
                this.completionProcessed.push(num_list);
              }
            } else if (p.type === "input-array") {
              if (p.value.length > 0) {
                p.value.map(r => {
                  label = this.objectKeyRename(r.label);
                  this.completionProcessed.push({
                    [label]: r.value
                  });
                });
              }
            } else if (p.type === "text-array") {
              if (p.value && p.value.length > 0) {
                let texts = [];
                if (p.value[0].text) {
                  p.value.map(q => {
                    texts.push(q.text);
                  });
                } else {
                  p.value.map(q => {
                    texts.push(q);
                  });
                }
                let text_list = {};
                label = this.objectKeyRename(p.label);
                this.$set(text_list, label, texts);
                this.completionProcessed.push(text_list);
              }
            }
          });
        }
      });
    },
    getUILayout() {
      this.currentPage = this.completion[this.currentPageIndex];
      this.getUserDetails();
    },
    getUserDetails() {
      if (this.user) {
        this.mapUserDataToLayout(this.user);
      } else {
        console.error("Error retriving profile details");
      }
    },
    async loadValuesFromDB() {
      const loadedObject = {
        ...this.user,
        ...this.userData,
        ...this.profileData
      };
      return loadedObject;
    },
    async mapUserDataToLayout(object) {
      let objectArray = await this.loadValuesFromDB();
      objectArray = await Object.entries(objectArray);
      let object_array = [];
      if (this.userData) {
        objectArray.push(["phoneNumber", this.userData.phoneNumber]);
      }
      object_array = Object.entries(object_array);
      objectArray.forEach(([key, value]) => {
        if (key !== "token" && key !== "roles")
          object_array.push({
            label: key,
            value: value
          });
      });
      this.completion.map(o => {
        o.fields.map(p => {
          object_array.map((q, index) => {
            if (
              this.objectKeyRename(q.label) === this.objectKeyRename(p.label)
            ) {
              this.$set(p, "value", q.value);
            }
          });
          if (this.objectKeyRename(p.label) === "contact_numbers") {
            let phoneNumber = object["phoneNumber"];
            if (phoneNumber && phoneNumber.length > 0) {
              let contactNumbers = [];
              if (typeof phoneNumber === "string") {
                const tempNumber = JSON.parse(JSON.stringify(phoneNumber));
                phoneNumber = [];
                phoneNumber.push(tempNumber);
              }
              phoneNumber.map(s => {
                contactNumbers.push({
                  type: p.value[0].type,
                  value: s
                });
              });
              this.$set(p, "value", contactNumbers);
            }
          }
          if (p.type === "input-array" && p.value && p.value.length > 0) {
            p.value.map(t => {
              this.$set(t, "value", object[this.objectKeyRename(t.label)]);
            });
          }
        });
      });
    },
    addElement(arr, obj) {
      let newObj = {};
      let count = 0;
      for (var property in obj) {
        if (obj.hasOwnProperty(property)) {
          let key = Object.keys(obj)[count];
          let value = Object.values(obj)[count];
          if (key === "type") {
            this.$set(newObj, key, value);
          } else {
            this.$set(newObj, key, "");
          }
          count = count + 1;
        }
      }
      arr.push(newObj);
    },
    removeElement(arr, index) {
      arr.splice(index, 1);
    }
  },
  mounted() {
    this.getUILayout();
  }
};
</script>
<style lang="scss" scoped>
@import "@/assets/styles/variables.scss";
.page.settings {
  max-width: calc(100% - 0px);
  position: relative;
}
.settings-inner-wrapper {
  display: inline-block;
  width: 100%;
}
.settings-sub-wrapper {
  width: 100%;
  display: inline-block;
  box-sizing: border-box;
  padding: 0px 10px 0px 20px;
}
.settings-list {
  width: 100%;
  font-size: 10px;
  border-right: 1px solid #e7e7ed;
  float: left;
}
.search-settings .search-block {
  background-color: $white;
  width: 100%;
  display: flex;
  flex-direction: row-reverse;
}
.settings-single-item-list-wrapper > label {
  position: relative;
  display: inline-flex;
  align-items: center;
  width: 100%;
  margin: 0px 0px 0px 0px;
  padding: 0px 20px;
  box-sizing: border-box;
  transition: 0.2s;
  height: 60px;
  font-size: 12px;
  font-weight: bold;
  letter-spacing: 1px;
  text-decoration: none;
}
.search-settings > .settings-single-item-list-wrapper {
  position: relative;
  display: inline-block;
}
.setting-item-header {
  display: inline-flex;
  width: 100%;
  justify-content: space-between;
  padding: 0px;
  align-items: center;
  height: 60px;
  border-bottom: 1px solid #e7e7ed;
}
.setting-item-header .search-block {
  background-color: $white;
  display: inline-block;
}
.setting-item-name {
  margin: 0px 0px 0px 0px;
  font-size: 12px;
  color: $primary-color;
  font-weight: bold;
  letter-spacing: 1px;
}
.setting-item-header .search-settings .search-block .search-button {
  justify-content: flex-end;
}
.setting-item-header .search-settings .search-block .search-field {
  padding: 21px 20px;
}
.inner-settings-button-edit {
  text-align: right;
}
.inner-settings-item {
  position: relative;
  padding: 0px;
  box-sizing: border-box;
  transition: 0.2s;
}
.inner-settings-item .value {
  width: 70%;
  font-weight: 500;
  font-size: 12px;
  color: $primary-color;
  opacity: 0.5;
}
.inner-settings-item .title {
  font-size: 12px;
  color: $primary-color;
  font-weight: bold;
  letter-spacing: 1px;
}
.inner-settings-button-edit {
  width: auto;
  font-size: 12px;
}
.inner-settings-button {
  width: 112px;
  height: 40px;
  border: none;
  border: 1px solid $green;
  box-sizing: border-box;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  border-radius: 4px;
  font-weight: normal;
  font-size: 12px;
  line-height: 18px;
  letter-spacing: 0.07em;
}
.inner-settings-btn-save {
  color: $white;
  background: $green;
}
.inner-settings-btn-cancel {
  color: $green;
  border: 1px solid $green;
  background-color: $white;
  margin-right: 10px;
}
.search-settings .search-block .search-field::placeholder {
  color: transparent;
}
.inner-settings-button-wrapper {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  margin: 20px 0px 0px 0px;
}
.input-wrapper input {
  padding: 10px;
  box-sizing: border-box;
}
.input-wrapper-label {
  width: 200px;
  font-size: 12px;
  font-weight: bold;
  letter-spacing: 1px;
}
textarea {
  resize: none !important;
  display: inline-block;
  border-width: 1px;
  border-style: solid;
  border-color: rgb(169, 169, 169);
  border-image: initial;
  padding: 2px;
  min-height: 200px;
  padding: 10px;
}
.page-item-placeholder > .vue-content-placeholders-img {
  height: 60px;
  width: 158px;
  margin: 0px 10px 0px 0px;
}
</style>
