import colors from 'vuetify/es5/util/colors'

export default {
  ssr: true,
  // Target: https://go.nuxtjs.dev/config-target
  target: 'server',

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    titleTemplate: '%s - admin-portal-revamp',
    title: 'admin-portal-revamp',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' }
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: ['~/assets/main.scss'],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: ['~/plugins/vue-tooltip.ts'],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/typescript
    '@nuxt/typescript-build',
    // https://go.nuxtjs.dev/stylelint
    '@nuxtjs/stylelint-module',
    // https://go.nuxtjs.dev/vuetify
    '@nuxtjs/vuetify',
    // https://composition-api.nuxtjs.org/getting-started/setup
    '@nuxtjs/composition-api/module'
  ],

  generate: {
    // choose to suit your project
    interval: 1000
  },

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    // https://go.nuxtjs.dev/pwa
    '@nuxtjs/pwa',
    // https://auth.nuxtjs.org/guide/setup
    '@nuxtjs/auth-next'
  ],

  auth: {
    strategies: {
      local: {
        token: {
          property: 'token',
          global: true,
          required: true,
          type: 'Bearer'
        },
        user: {
          property: 'id',
          autoFetch: true
        },
        endpoints: {
          login: { url: 'emaillogin', method: 'post' },
          logout: false,
          user: false
        }
      }
    }
  },

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    // fallback url
    baseURL: process.env.NODE_ENV === 'production' ? 'https://findyo-admin.audiblepro.com/api/' : 'http://127.0.0.1:8000/api/',
    retry: { retries: 3 },
    common: {
      Accept: 'application/json, text/plain, */*'
    }
  },

  router: {
    middleware: ['auth-detect']
  },

  publicRuntimeConfig: {
    axios: {
      browserBaseURL: process.env.VUE_APP_FIND_YO_API_URL
    }
  },

  privateRuntimeConfig: {
    axios: {
      baseURL: process.env.VUE_APP_FIND_YO_API_URL
    }
  },

  // PWA module configuration: https://go.nuxtjs.dev/pwa
  pwa: {
    manifest: {
      lang: 'en'
    }
  },

  // Vuetify module configuration: https://go.nuxtjs.dev/config-vuetify
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: false,
      themes: {
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3
        }
      }
    }
  },

  // env: {
  //   baseApi: process.env.BASE_API || 'http://127.0.0.1:8000/api/'
  // },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    transpile: ['vuex-composition-helpers', 'vue-tooltip'],
    extend(config) {
      config.devtool = 'source-map'
    }
  }
}
