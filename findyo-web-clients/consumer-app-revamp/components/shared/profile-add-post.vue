<template>
  <v-card class="mx-auto">
    <v-card-text class="pb-0">
      <v-textarea
        counter
        :rules="state.rules"
        :value="state.form.content"
        rows="4"
        filled
        auto-grow
      ></v-textarea>
      <p class="mb-0">
        Max {{ maxCharacters }} chatacters
      </p>
    </v-card-text>

    <v-card-actions>
      <v-list-item class="grow">
        <v-list-item-avatar color="grey darken-3">
          <v-img
            class="elevation-6"
            alt=""
            src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
          ></v-img>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title>Gayan Sandamal</v-list-item-title>
        </v-list-item-content>

        <v-row align="center" justify="end">
          <v-icon class="mr-1"> mdi-heart </v-icon>
          <span class="subheading mr-2">256</span>
          <span class="mr-1">·</span>
          <v-icon class="mr-1"> mdi-share-variant </v-icon>
          <span class="subheading">45</span>
        </v-row>
      </v-list-item>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import { defineComponent, reactive } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const maxCharacters = 100
    const state = reactive({
      rules: [
        (v: string) => v.length <= maxCharacters || `Max ${maxCharacters} characters`
      ],
      form: {
        content: ''
      }
    })
    return {
      state,
      maxCharacters
    }
  }
})
</script>
