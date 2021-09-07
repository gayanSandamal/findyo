<template>
  <v-app dark>
    <h1 v-if="error.statusCode === 404">
      {{ pageNotFound }}
    </h1>
    <h1 v-else>
      {{ otherError }}
    </h1>
    <NuxtLink to="/">
      Home page
    </NuxtLink>
  </v-app>
</template>

<script lang="ts">
import {
  defineComponent,
  reactive
} from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    error: {
      type: Object,
      default: null
    }
  },
  setup(props) {
    const state = reactive({
      pageNotFound: '404 Not Found',
      otherError: 'An error occurred'
    })

    const head = () => {
      const title = props.error.statusCode === 404 ? state.pageNotFound : state.otherError
      return {
        title
      }
    }

    return {
      state,
      head
    }
  }
})
</script>

<style scoped>
h1 {
  font-size: 20px;
}
</style>
