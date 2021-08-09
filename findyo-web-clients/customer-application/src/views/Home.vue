<template>
  <div class="page home">
    <div class="feed-wrapper">
      <categorySelectModal
        v-if="isCategorySelectModal.state"
        :list="nestedCats"
        :breadcrumb="categoryHierarchy"
        :isCategorySelectModal="isCategorySelectModal"
      />
      <locationSelectModal
        v-if="isLocationSelectModal.state"
        :list="locations.locations"
        :breadcrumb="locationHierarchy"
        :isLocationSelectModal="isLocationSelectModal"
      />
      <add-post
        :eventBus="eventBus"
        v-if="user ? true : false"
        :db="db"
        :auth="auth"
        :user="user"
        :fb="fb"
        :postTypes="postTypes"
        :isAddPostFocused="isAddPostFocused"
        :breadcrumb="categoryHierarchy"
        :nestedCats="nestedCats"
        :nestedLocs="locations.locations"
        :locationBreadcrumb="locationHierarchy"
        :isCategorySelectModal="isCategorySelectModal"
        :isLocationSelectModal="isLocationSelectModal"
        :profileData="userData"
      />
      <ul
        v-if="posts.length > 0 && !isZeroPosts"
        class="feed-main-filter-block box-nue flex v-center"
        :class="{ 'feed-main-filter-block-sticky': !isAddPostFocused.state }"
      >
        <li class="filter-icon-wrapper">
          <span class="label-charm filter-icon"></span>
        </li>
        <li
          class="feed-main-filter-icon-all switch-nue"
          :class="[{ selected: 0 === selectedType.typeId }, postTypeLabel(0)]"
          @click.stop="filterByType(0)"
        >All</li>
        <li
          class="switch-nue"
          :class="[
            { selected: postType.typeId === selectedType.typeId },
            postTypeLabel(postType.typeId),
          ]"
          v-for="postType in postTypes"
          :key="postType.typeId"
          @click.stop="filterByType(postType.typeId)"
        >{{ postType.filterLabel }}</li>
      </ul>
      <ul
        v-else
        class="feed-main-filter-block box-nue"
        :class="{ 'feed-main-filter-block-sticky': !isAddPostFocused.state }"
      >
        <li class="filter-icon-wrapper">
          <span class="label-charm filter-icon dim"></span>
        </li>
      </ul>
      <ul class="feed-main-posts-block" v-if="posts.length > 0 && !isZeroPosts">
        <li v-for="post in posts" :key="post.id">
          <Post
            :data="post"
            :user="user"
            :postTypes="postTypes"
            :isAddPostFocused="isAddPostFocused"
            :eventBus="eventBus"
            :db="db"
            :auth="auth"
            :breadcrumb="categoryHierarchy"
            :nestedCats="nestedCats"
            :nestedLocs="locations.locations"
            :locationBreadcrumb="locationHierarchy"
            :isCategorySelectModal="isCategorySelectModal"
            :isLocationSelectModal="isLocationSelectModal"
            :profileData="userData"
          />
        </li>
      </ul>
      <div
        v-if="!isMorePostsLoading"
        @click.stop="loadPosts"
        class="button flex v-center h-center m-b-2"
      >Load more posts</div>
      <div class="placeholder-wrapper" v-if="isMorePostsLoading">
        <content-placeholders class="box-nue" v-for="item in 3" :key="item">
          <content-placeholders-text :lines="3" />
          <content-placeholders-heading :rounded="true" :img="true" />
        </content-placeholders>
      </div>
    </div>
    <div class="home-sidebar-wrapper">
      <div class="home-sidebar home-sidebar-1">
        <div class="box-nue">
          <div class="home-top-list">
            <div class="full-width">
              <h3 class="title">Top categories</h3>
            </div>
            <ul>
              <li>
                <!-- <UserWidget :displayName="'user.providerData[0].displayName'" :email="'user.providerData[0].email'" :photoUrl="''"/> -->
              </li>
            </ul>
          </div>
          <!-- <div class="home-top-list">
            <div class="full-width">
              <h3 class="title">Top freelancers</h3>
            </div>
            <ul>
              <li>
                <UserWidget :displayName="'Appuhami'" :email="'plumber'" :photoUrl="''"/>
              </li>
              <li>
                <UserWidget :displayName="'Signgno'" :email="'welding engineer'" :photoUrl="''"/>
              </li>
            </ul>
          </div>-->
        </div>
      </div>
      <div class="home-sidebar home-sidebar-2">
        <div class="box-nue">
          <div class="full-width">
            <h3 class="title">Filters</h3>
          </div>
          <div class="full-width">
            <h5 class="sub-title">Filter by location</h5>
          </div>
          <ul class="filter-breadcrumb">
            <li v-for="(item, idx) in locationBreadcrumb" :key="idx">
              {{ title(item)
              }}
              <span class="tag-remove" @click.stop="removeLoc(item)"></span>
            </li>
          </ul>
          <location-list
            :list="locationList"
            :languageId="languageId"
            :addedFilters="locationBreadcrumb"
            :isParent="true"
          />
          <div class="full-width">
            <Button class :label="'Filter'" :action="applyFilter" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import firebase from "firebase/app";
import "firebase/firestore";
// import locs from './../json/locations.json'
import { languageLabel } from "./../func/usables";

export default {
  name: "home",
  data() {
    return {
      posts: [],
      isZeroPosts: true,
      isMorePostsLoading: true,
      pageSize: 3,
      lastSnapshot: {},
      lastSnapshotChkObj2: {},
      lastSnapshotChkObj1: {},
      locationBreadcrumb: [],
      selectedType: {},
      isAddPostFocused: {
        state: false
      },
      isInitial: true,
      categories: [],
      childCategories: [],
      isCategorySelectModal: {
        state: false
      },
      isLocationSelectModal: {
        state: false
      },
      categoryHierarchyInit: [],
      fromEdit: false
    };
  },
  props: {
    eventBus: undefined,
    db: undefined,
    auth: undefined,
    user: undefined,
    fb: undefined,
    locations: {
      type: Object
    },
    languageId: {
      type: Number,
      default: 0
    },
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
    }
  },
  components: {
    "add-post": () =>
      import(/* webpackChunkName: "add-post"*/ "@/components/feed/AddPost"),
    Post: () => import(/* webpackChunkName: "Post"*/ "@/components/feed/Post"),
    "location-list": () =>
      import(
        /* webpackChunkName: "location-list"*/ "@/components/common/Lists/LocationList"
      ),
    Button: () =>
      import(/* webpackChunkName: "Button"*/ "@/components/inputs/Button"),
    UserWidget: () =>
      import(
        /* webpackChunkName: "UserWidget"*/ "@/components/common/UserWidget"
      ),
    categorySelectModal: () =>
      import(
        /* webpackChunkName: "categorySelectModal"*/ "@/components/common/Modals/CategorySelection"
      ),
    locationSelectModal: () =>
      import(
        /* webpackChunkName: "locationSelectModal"*/ "@/components/common/Modals/LocationSelection"
      )
  },
  computed: {
    locationList() {
      return this.locations.locations ? this.locations.locations : [];
    },
    categoryHierarchy() {
      if (!this.fromEdit) {
        this.categoryHierarchyInit = [];
      }
      this.mapCategoryHierarchy(this.nestedCats, this.categoryHierarchyInit);
      return this.categoryHierarchyInit;
    },
    // categoryHierarchy: {
    //   get: function () {
    //     if (!this.fromEdit) {
    //       this.categoryHierarchyInit = []
    //     }
    //     this.mapCategoryHierarchy(this.nestedCats, this.categoryHierarchyInit)
    //     return this.categoryHierarchyInit
    //   },
    //   set: function (list) {
    //     this.fromEdit = true
    //     this.categoryHierarchyInit.push(list)
    //   }
    // },
    locationHierarchy() {
      let locationHierarchy = [];
      this.mapLocationHierarchy(this.locationList, locationHierarchy);
      return locationHierarchy;
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
    }
  },
  methods: {
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
    mapLocationHierarchy(list, locationHierarchy) {
      list.map(o => {
        if (o.selected) {
          locationHierarchy.push(o);
          if (o.locations && o.locations.length > 0) {
            this.mapLocationHierarchy(o.locations, locationHierarchy);
          }
        }
      });
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
    title(obj) {
      return languageLabel(this.languageId, obj);
    },
    getPostsInitialy(postsFilter) {
      this.isMorePostsLoading = true;
      if (postsFilter !== 0) {
        this.db
          .collection("User_Posts")
          .where("postType", "==", postsFilter)
          .orderBy("postedTime", "desc")
          .limit(this.pageSize)
          .get()
          .then(querySnapshot => {
            this.posts = [];
            this.isMorePostsLoading = false;
            querySnapshot.forEach(doc => {
              //console.log(doc.data())
              this.addToPosts(doc);
            });
          })
          .catch(err => {
            console.log("Error getting posts", err);
          });
        // .onSnapshot(querySnapshot => {
        //   this.posts = []
        //   this.isMorePostsLoading = false
        //   this.addToPosts(querySnapshot.docs)
        //   this.isMorePostsLoading = false
        // })
      } else {
        this.db
          .collection("User_Posts")
          .orderBy("postedTime", "desc")
          .limit(this.pageSize)
          .get()
          .then(querySnapshot => {
            this.posts = [];
            this.isMorePostsLoading = false;
            this.addToPosts(querySnapshot.docs);
          })
          .catch(err => {
            console.log("Error getting posts", err);
          });
      }
    },
    getMorePosts(postsFilter) {
      // Get filterd result
      if (postsFilter !== 0) {
        this.db
          .collection("User_Posts")
          .where("postType", "==", postsFilter)
          .orderBy("postedTime", "desc")
          .startAfter(this.lastSnapshot)
          .limit(this.pageSize)
          .get()
          .then(querySnapshot => {
            this.isMorePostsLoading = false;
            this.addToPosts(querySnapshot.docs);
            if (querySnapshot.docs.length > 0) {
              this.lastSnapshotChkObj2 = querySnapshot.docs[
                querySnapshot.docs.length - 1
              ].data();
            } else {
              this.lastSnapshotChkObj2 = undefined;
            }
          });
      } else {
        this.db
          .collection("User_Posts")
          .orderBy("postedTime", "desc")
          .startAfter(this.lastSnapshot)
          .limit(this.pageSize)
          .get()
          .then(querySnapshot => {
            this.addToPosts(querySnapshot.docs);
            // setTimeout(() => {
            this.isMorePostsLoading = false;
            // }, 2000)
            if (querySnapshot.docs.length > 0) {
              this.lastSnapshotChkObj2 = querySnapshot.docs[
                querySnapshot.docs.length - 1
              ].data();
            } else {
              this.lastSnapshotChkObj2 = undefined;
            }
          });
      }
    },
    addToPosts(arr) {
      //console.log(arr)
      if (arr.length > 0) {
        arr.map(o => {
          let obj = o.data();
          this.$set(obj, "id", o.id);
          this.posts.push(obj);
          this.lastSnapshot = o;
          this.lastSnapshotChkObj1 = obj;
          this.isZeroPosts = this.posts.length > 0 ? false : true;
        });
      }
    },
    removeLoc(loc) {
      let idx = this.locationBreadcrumb.indexOf(loc);
      if (idx > -1) {
        this.locationBreadcrumb.splice(idx, 1);
      }
    },
    filterByType(typeId) {
      if (typeId === 0) {
        this.selectedType = {
          filterLabel: "Freelancer",
          label: "Provide a service",
          placeholder: "Let the world know about your service",
          typeId: 0
        };
      } else {
        this.selectedType = this.postTypes.find(o => {
          return o.typeId === typeId;
        });
      }
      this.getPostsInitialy(typeId);
    },
    applyFilter() {},
    // homeScroll(e) {
    //   let scrollingWindow = (window.innerHeight + window.scrollY)
    //   let scrollThreashold = document.body.offsetHeight
    //   if (scrollingWindow >= scrollThreashold) {
    //     if (!this.isMorePostsLoading) {
    //       this.isMorePostsLoading = true
    //       if (this.lastSnapshotChkObj2 && (this.lastSnapshotChkObj1 !== this.lastSnapshotChkObj2)) {
    //           this.getMorePosts(this.selectedType.typeId)
    //       } else {
    //         this.isMorePostsLoading = false
    //       }
    //     }
    //   }
    // },
    loadPosts(e) {
      if (!this.isMorePostsLoading) {
        this.isMorePostsLoading = true;
        if (
          this.lastSnapshotChkObj2 &&
          this.lastSnapshotChkObj1 !== this.lastSnapshotChkObj2
        ) {
          this.getMorePosts(this.selectedType.typeId);
          setTimeout(() => {
            window.scrollTo(0, document.body.scrollHeight);
          }, 500);
        } else {
          this.isMorePostsLoading = false;
        }
      }
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
        .catch(error => {
          console.log(error);
        });
    },
    getHomeStaticData() {
      this.getCategories();
    },
    setCategoryHierarchy(list) {
      this.categoryHierarchyInit = list;
      // this.categoryHierarchy = list
    }
  },
  created() {
    // window.addEventListener('scroll', (e) => {
    //   this.homeScroll(e)
    // }, {passive: true})
  },
  beforeDestroy() {
    // window.removeEventListener('scroll', (e) => {
    //   this.homeScroll(e)
    // }, {passive: true})
    this.eventBus.$off("set-catHierarchy", this.setCategoryHierarchy);
  },
  mounted() {
    this.filterByType(0);
    this.getHomeStaticData();
    this.eventBus.$on("set-catHierarchy", this.setCategoryHierarchy);
    // if (!this.user.displayName) {
    //   this.$router.push({path: '/completion'})
    // }
  }
};
</script>

<style scoped>
.filter-icon {
  background-image: url(./../assets/img/svg/filter.svg);
  background-size: 16px;
  width: 16px;
  height: 16px;
  background-position: center;
  cursor: default !important;
  border-radius: 0px;
  box-shadow: none;
}
.filter-icon-wrapper {
  padding: 0px !important;
  margin: 0px 10px 0px 0px;
  cursor: default !important;
}
.tag-remove {
  background-image: url(./../assets/img/svg/close.svg);
}
.home-top-list li .user-widget-wrapper a {
  justify-content: flex-start;
}
.home-top-list li .user-widget-wrapper .user-title-wrapper {
  margin: 0px 0px 0px 10px;
}
.home-top-list ul {
  margin: 10px 0px 0px 0px;
}
.home-top-list ul li:not(:last-child) {
  margin: 0px 0px 10px 0px;
}
</style>
