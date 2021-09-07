<template>
  <v-app dark>
    <v-navigation-drawer
      v-model="state.drawer"
      :mini-variant="state.miniVariant"
      :clipped="state.clipped"
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
    <v-app-bar :clipped-left="state.clipped" fixed app>
      <v-app-bar-nav-icon @click.stop="state.drawer = !state.drawer" />
      <v-btn icon @click.stop="state.miniVariant = !state.miniVariant">
        <v-icon>mdi-{{ `chevron-${state.miniVariant ? 'right' : 'left'}` }}</v-icon>
      </v-btn>
      <v-btn icon @click.stop="state.clipped = !state.clipped">
        <v-icon>mdi-application</v-icon>
      </v-btn>
      <v-btn icon @click.stop="state.fixed = !state.fixed">
        <v-icon>mdi-minus</v-icon>
      </v-btn>
      <v-toolbar-title v-text="state.title" />
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
    <v-footer :absolute="!state.fixed" app>
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script lang="ts">
import {
  defineComponent,
  reactive,
  computed,
  useContext,
  useRouter
} from '@nuxtjs/composition-api'
import { IDrawerMenu } from '@/interfaces/ui'

export default defineComponent({
  setup() {
    const { $auth } = useContext()
    const router = useRouter()
    const state = reactive({
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
      ] as IDrawerMenu[],
      miniVariant: false,
      right: true,
      title: 'Findyo Admin'
    })

    const filteredItems = computed(() => {
      const newArray: IDrawerMenu[] = []
      state.items.forEach((i) => {
        if (i.title === 'Login' || i.title === 'Register') {
          if (!$auth.loggedIn) {
            newArray.push(i)
          }
        } else {
          newArray.push(i)
        }
      })
      return newArray
    })

    const logout = async () => {
      // console.log(this.$auth.$storage.getUniversal('userId'))
      // console.log(this.$auth.user)
      await $auth.logout()
      router.push('/login')
    }

    return {
      state,
      filteredItems,
      logout
    }
  }
})
</script>
