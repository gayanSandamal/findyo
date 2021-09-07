<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6">
      <v-card class="mt-5">
        <v-card-title class="headline">
          Welcome to Findyo Admin Panel
        </v-card-title>
        <v-card-text>
          <p>Please use menu to access each functionality</p>
          <p>
            For more information contact technical team
            <a
              href="https://facebook.com"
              target="_blank"
              rel="noopener noreferrer"
            >
              Facebook </a>
          </p>

          <hr class="my-3" />
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn v-if="!$auth.loggedIn" color="primary" nuxt to="/login">
            Login
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { defineComponent, useContext } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const { $auth } = useContext()
    // CODE GOES HERE
    return {
      $auth
    }
  }
})
</script>
