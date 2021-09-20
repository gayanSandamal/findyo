<template>
  <v-app dark>
    <v-navigation-drawer
      v-model="state.drawer"
      :mini-variant="state.miniVariant"
      :clipped="true"
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
    <v-app-bar :clipped-left="true" fixed app>
      <!-- <div class="w-100 d-flex align-center justify-space-between"> -->
      <v-row align="center">
        <v-col cols="1">
          <img class="img m-0 logo" :src="require('~/assets/img/logo.png')" alt="logo">
        </v-col>
        <v-col cols="4" class="offset-md-3">
          <v-text-field
            placeholder="Search"
            :hide-details="true"
            filled
            rounded
          ></v-text-field>
        </v-col>
        <v-col cols="2" class="text-right offset-md-2">
          <client-only>
            <div v-if="$auth.loggedIn" class="d-flex align-center justify-end">
              <v-menu offset-y nudge-bottom="18">
                <template #activator="{ on, attrs }">
                  <v-btn
                    color="secondary"
                    dark
                    text
                    plain
                    :ripple="false"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-avatar
                      color="grey"
                      size="48"
                    >
                      <span class="white--text text-h5">48</span>
                    </v-avatar>
                    <span class="mx-4">Gayan sandamal</span>
                    <v-icon
                      dark
                      left
                    >
                      mdi-chevron-down
                    </v-icon>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item>
                    <v-btn
                      color="secondary"
                      text
                      plain
                      :ripple="false"
                      @click="logout"
                    >
                      <v-icon
                        right
                        dark
                        block
                      >
                        mdi-logout
                      </v-icon>
                      <span class="ml-4">Logout</span>
                    </v-btn>
                  </v-list-item>
                </v-list>
              </v-menu>
            </div>
            <v-btn v-else text color="secondary" @click="$router.push({path: '/login'})">
              &nbsp; Login
            </v-btn>
          </client-only>
        </v-col>
      </v-row>
    </v-app-bar>
    <v-main>
      <v-container>
      {{ user }}
        <nuxt />
      </v-container>
    </v-main>
    <v-footer app>
      <span>&copy; {{ new Date().getFullYear() }} Findyo All rights reserved</span>
      {{ state.config }}
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
import filter from 'lodash/filter'
import sortBy from 'lodash/sortBy'
import { IDrawerMenu } from '@/interfaces/ui'

export default defineComponent({
  setup() {
    const { $auth } = useContext()
    const router = useRouter()
    const state = reactive({
      drawer: true,
      items: [
        {
          icon: 'mdi-login',
          title: 'Login',
          to: '/login',
          authentication: false,
          order: 1
        },
        {
          icon: 'mdi-account',
          title: 'Register',
          to: '/register',
          authentication: false,
          order: 2
        },
        {
          icon: 'mdi-apps',
          title: 'Welcome',
          to: '/',
          authentication: true,
          order: 3
        },
        {
          icon: 'mdi-chart-bubble',
          title: 'Profile',
          to: '/profile',
          authentication: true,
          order: 4
        },
        {
          icon: 'mdi-crosshairs-gps',
          title: 'locations',
          to: '/locations',
          authentication: true,
          order: 5
        },
        {
          icon: 'mdi-account',
          title: 'Review Lib',
          to: '/review-lib',
          authentication: true,
          order: 7
        },
        {
          icon: 'mdi-cart',
          title: 'Categories',
          to: '/categories',
          authentication: true,
          order: 6
        }
      ] as IDrawerMenu[],
      miniVariant: true,
      right: true,
      title: 'findyo.lk',
      config: process.env
    })
    debugger
    const user = computed(() => $auth.$state.user)
    const filteredItems = computed((): IDrawerMenu[] => {
      const result: IDrawerMenu[] = filter(state.items, (i: IDrawerMenu) => {
        if ($auth.loggedIn) {
          return i.authentication
        } else {
          return !i.authentication
        }
      })
      const sortedList: IDrawerMenu[] = sortBy(result, ['order'])
      return sortedList
    })

    const logout = async () => {
      await $auth.logout()
      router.push('/login')
    }

    return {
      state,
      user,
      filteredItems,
      logout
    }
  }
})
</script>
