<template>
  <div class="card">
    <div class="card-body">
      <ul class="nav nav-tabs mb-3">
        <li class="nav-item" v-for="(route, index) in postChilds" :key="index">
          <router-link class="nav-link active" :to="'/posts/' + route.path" v-text="route.name"></router-link>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#">Disabled</a>
        </li> -->
      </ul>
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
export default {
  name: 'locations',
  data () {
    return {
      postChilds: [
        {
          path: 'pending',
          name: 'Pending'
        }
      ]
    }
  },
  props: {
  },
  components: {
  },
  computed: {
    allRoutes () {
      return this.$router.options.routes
    },
    currentRouteName () {
      return this.$route.name
    },
    currentChild () {
      return this.allRoutes.find(o => this.currentRouteName === o.name)
    }
  },
  methods: {
  },
  mounted () {
    this.$router.push({ path: '/posts/pending' })
  }
}
</script>
