<template>
  <v-app dark>
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant="miniVariant"
      :clipped="clipped"
      fixed
      app
    >
      <v-list>
        <v-list-item
          v-for="(item, i) in filteredItems"
          :key="i"
          :to="item.to"
          router
          exact
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-text="item.title" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar :clipped-left="clipped" fixed app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-btn icon @click.stop="miniVariant = !miniVariant">
        <v-icon>mdi-{{ `chevron-${miniVariant ? 'right' : 'left'}` }}</v-icon>
      </v-btn>
      <v-btn icon @click.stop="clipped = !clipped">
        <v-icon>mdi-application</v-icon>
      </v-btn>
      <v-btn icon @click.stop="fixed = !fixed">
        <v-icon>mdi-minus</v-icon>
      </v-btn>
      <v-toolbar-title v-text="title" />
      <v-spacer />
      <v-btn v-if="$auth.loggedIn" color="secondary" @click="logout">
        <v-icon>mdi-logout</v-icon>
        &nbsp; Logout
      </v-btn>
    </v-app-bar>
    <v-main>
      <v-container>
        <nuxt />
      </v-container>
    </v-main>
    <v-footer :absolute="!fixed" app>
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      clipped: false,
      drawer: false,
      fixed: false,
      items: [
        {
          icon: 'mdi-apps',
          title: 'Welcome',
          to: '/'
        },
        {
          icon: 'mdi-chart-bubble',
          title: 'Profile',
          to: '/profile'
        },
        {
          icon: 'mdi-account',
          title: 'Register',
          to: '/register'
        },
        {
          icon: 'mdi-login',
          title: 'Login',
          to: '/login'
        }
      ],
      miniVariant: false,
      right: true,
      title: 'Findyo Admin'
    }
  },
  computed: {
    filteredItems() {
      const newArray = []
      this.items.forEach(i => {
        if (i.title === 'Login' || i.title === 'Register') {
          if (!this.$auth.loggedIn) {
            newArray.push(i)
          }
        } else {
          newArray.push(i)
        }
      })
      return newArray
    }
  },
  methods: {
    async logout() {
      // console.log(this.$auth.$storage.getUniversal('userId'))
      // console.log(this.$auth.user)
      await this.$auth.logout()
      this.$router.push('/login')
    }
  }
}
</script>
