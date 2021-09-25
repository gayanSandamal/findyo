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
          v-for="(item, i) in filteredDrawerItems"
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
      <v-row align="center">
        <v-col cols="1" sm="1" class="d-none d-sm-block">
          <img class="img m-0 logo" :src="require('~/assets/img/logo.png')" alt="logo">
        </v-col>
        <v-col cols="10" sm="5" md="4" class="offset-md-3 offset-sm-2">
          <v-text-field
            placeholder="Search"
            :hide-details="true"
            filled
            rounded
          ></v-text-field>
        </v-col>
        <v-col cols="2" sm="2" class="text-right offset-md-2 offset-sm-2 d-none d-sm-block">
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
                      <span class="white--text text-h5" v-text="shortName"></span>
                    </v-avatar>
                    <span class="mx-4" v-text="fullName"></span>
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
        <nuxt />
      </v-container>
    </v-main>
    <v-footer app>
      <span>&copy; {{ new Date().getFullYear() }} Findyo All rights reserved</span>
    </v-footer>
    <v-snackbar
      v-model="state.snackbar.state"
      fixed
      top
      center
      elevation="24"
      :color="state.snackbar.color"
      :timeout="state.snackbar.timeout"
      :multi-line="true"
    >
      {{ state.snackbar.text }}
      <template #action="{ attrs }">
        <v-btn dark text v-bind="attrs" @click="state.snackbar.state = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-app>
</template>

<script lang="ts">
import {
  defineComponent,
  reactive,
  computed,
  useContext,
  useRouter,
  useRoute
} from '@nuxtjs/composition-api'
import { useActions, useGetters } from 'vuex-composition-helpers'
import filter from 'lodash/filter'
import sortBy from 'lodash/sortBy'
import includes from 'lodash/includes'
import { IDrawerMenu } from '@/interfaces/ui'

export default defineComponent({
  setup(_, context) {
    const { $auth } = useContext()
    const router = useRouter()
    const route = useRoute()
    const { setUser } = useActions(['setUser'])
    const { user, drawerMenus } = useGetters(['user', 'drawerMenus'])
    const state = reactive({
      drawer: true,
      miniVariant: true,
      right: true,
      title: 'findyo.lk',
      snackbar: {
        text: '',
        color: '',
        timeout: -1,
        state: false
      }
    })

    context.root.$store.subscribe((mutation, val) => {
      if (mutation.type === 'SHOW_MESSAGE') {
        state.snackbar.text = val.snackbar.text
        state.snackbar.color = val.snackbar.color
        state.snackbar.timeout = val.snackbar.timeout || 3000
        state.snackbar.state = true
      }
    })

    const filteredDrawerItems = computed((): IDrawerMenu[] => sortBy(
      filter(drawerMenus.value, (i: IDrawerMenu) => {
        let state = true
        if (!$auth.loggedIn && i.authentication) {
          state = false
        }
        return state
      }), ['order'])
    )

    const fullName = computed(() => {
      if (user.value.firstname && user.value.lastname) {
        return `${user.value.firstname} ${user.value.lastname}`
      } else if (user.value.firstname) {
        return user.value.firstname
      } else {
        return 'New user'
      }
    })

    const shortName = computed(() => {
      if (user.value.firstname && user.value.lastname) {
        return `${user.value.firstname.charAt(0)}${user.value.lastname.charAt(0)}`
      } else if (user.value.firstname) {
        return user.value.firstname.charAt(0)
      } else {
        return ':)'
      }
    })

    const getUserObj = () => {
      try {
        const user: any = $auth.$storage.getCookie('userFull')
        if (user && user.id) {
          if ($auth.loggedIn && includes(['login', 'register'], route.value.name)) {
            router.push({ path: '/' })
          }
          setUser(user)
        } else if (includes(['login', 'register', 'index'], route.value.name)) {
          router.push({ path: route.value.fullPath })
        } else if (!includes(['login', 'register', 'index'], route.value.name)) {
          router.push({ path: '/' })
        }
      } catch (error) {
        console.error(error)
      }
    }

    getUserObj()

    const logout = async () => {
      await $auth.logout()
      $auth.$storage.removeCookie('userFull')
      router.push({ path: '/login' })
    }

    return {
      state,
      fullName,
      shortName,
      filteredDrawerItems,
      logout
    }
  }
})
</script>
