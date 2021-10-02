<template>
  <div class="my-5">
    <v-tabs>
      <v-tab>Manage Locations</v-tab>
      <v-tab>Manage Levels</v-tab>
      <v-tab-item>
        <LazyLocationTab />
      </v-tab-item>
      <v-tab-item>
        <LazyManageLavelsTab />
      </v-tab-item>
    </v-tabs>
  </div>
</template>

<script lang="ts">
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    return {}
  }
})
</script>
