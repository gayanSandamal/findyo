<template>
  <v-dialog v-model="state.dialog" width="500">
    <template #activator="{ on, attrs }">
      <v-card class="mx-auto">
        <v-card-actions>
          <v-list-item>
            <v-row class="d-flex justify-content-center">
              <v-col cols="2">
                <v-list-item-avatar color="grey darken-3 m-0">
                  <v-img
                    class="elevation-6"
                    alt=""
                    src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
                  ></v-img>
                </v-list-item-avatar>
              </v-col>
              <v-col cols="10" align-self="center">
                <v-btn
                  block
                  rounded
                  depressed
                  class="text-transform-none justify-start font-weight-regular text--disabled"
                  v-bind="attrs"
                  v-on="on"
                >
                  Publish your post
                </v-btn>
              </v-col>
            </v-row>
          </v-list-item>
        </v-card-actions>
      </v-card>
    </template>
    <v-card class="mx-auto pt-4">
      <v-card-text class="pb-0">
        <v-textarea
          counter
          :rules="state.rules"
          :value="state.form.content"
          rows="4"
          filled
          auto-grow
          autofocus
        ></v-textarea>
        <p class="mb-0">
          Max {{ maxCharacters }} chatacters
        </p>
      </v-card-text>

      <v-card-actions>
        <div class="w-100">
          <v-radio-group
            v-model="state.form.postType"
            class="px-2"
            row
          >
            <v-radio
              v-for="postType in state.postTypes"
              :key="postType.key"
              :label="`I'm a ${postType.label}`"
              :value="postType.key"
            ></v-radio>
          </v-radio-group>
          <v-list-item class="grow">
            <v-list-item-avatar color="mx-0 grey darken-3">
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
              <v-icon class="mr-1">
                mdi-share-variant
              </v-icon>
              <span class="subheading">45</span>
            </v-row>
          </v-list-item>
        </div>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { defineComponent, reactive } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const maxCharacters = 100
    const state = reactive({
      rules: [
        (v: string) =>
          v.length <= maxCharacters || `Max ${maxCharacters} characters`
      ],
      dialog: false,
      postTypes: [
        {
          key: 'seller',
          label: 'seller'
        },
        {
          key: 'consumer',
          label: 'buyer'
        }
      ],
      form: {
        content: '',
        postType: 'seller'
      }
    })
    return {
      state,
      maxCharacters
    }
  }
})
</script>

<style lang="scss" scoped>
.trigger-add-post-btn {
  width: calc(100% - 32px);
}
</style>
