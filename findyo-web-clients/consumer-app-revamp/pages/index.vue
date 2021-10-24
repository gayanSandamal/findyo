<template>
  <v-row justify="start" align="center">
    <v-col cols="6">
      <profile-add-post />
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { defineComponent } from '@nuxtjs/composition-api'
import { useGetters } from 'vuex-composition-helpers'

export default defineComponent({
  auth: true,
  meta: {
    auth: false
  },
  setup() {
    const { user } = useGetters(['user'])
    return {
      user
    }
  }
})
</script>
