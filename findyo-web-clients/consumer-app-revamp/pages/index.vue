<template>
  <v-row justify="center" align="center">
    <v-col cols="12">
      Home page
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { defineComponent } from '@nuxtjs/composition-api'
import { useGetters } from 'vuex-composition-helpers'

export default defineComponent({
  auth: false,
  setup() {
    const { user } = useGetters(['user'])
    return {
      user
    }
  }
})
</script>
