<template>
  <v-row class="mt-5">
    <v-stepper v-model="state.step" class="w-100" vertical>
      <v-form ref="step1" v-model="state.stepValidation.step1" class="w-100">
        <v-stepper-step :complete="state.step > 1" step="1">
          Basic information
        </v-stepper-step>
        <v-stepper-content step="1">
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="state.form.username"
                :rules="state.usernameRules"
                label="Username (required)"
                name="username"
                required
                autofocus
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="state.form.email"
                :rules="state.emailRules"
                label="E-Mail (required)"
                name="email"
                type="email"
                required
                disabled
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="state.form.firstname"
                :rules="state.firtnameRules"
                label="First name (required)"
                name="firstname"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="state.form.lastname"
                :rules="state.lastnameRules"
                label="Last name"
                name="lastname"
              ></v-text-field>
            </v-col>
            <!-- <v-col cols="12" md="6">
              <v-menu
                ref="menu"
                v-model="state.form.dob.menu"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template #activator="{ on, attrs }">
                  <v-text-field
                    v-model="state.form.dob.date"
                    label="Date of birth"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="state.form.dob.date"
                  :active-picker.sync="state.form.dob.activePicker"
                  :max="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)"
                  min="1950-01-01"
                  @change="saveDoB"
                ></v-date-picker>
              </v-menu>
            </v-col> -->
            <v-col cols="12">
              <v-row>
                <v-col
                  v-for="(contact, index) in state.form.contacts"
                  :key="index"
                  cols="6"
                  class="d-flex align-center"
                >
                  <v-text-field
                    v-model="contact.number"
                    :rules="state.phoneNumberRules"
                    :label="`Contact number ${contact.primary ? '(primary)' : index + 1}`"
                    name="lastname"
                  ></v-text-field>
                  <v-btn
                    v-if="state.form.contacts.length === 1 || index === (state.form.contacts.length - 1)"
                    class="ml-2"
                    fab
                    dark
                    small
                    color="secondary"
                    :disabled="isAddContactDisabled"
                    @click="addContactField"
                  >
                    <v-icon dark>
                      mdi-plus
                    </v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
          <v-btn
            color="success"
            :disabled="!state.stepValidation.step1"
            @click="state.step = 2"
          >
            Next
          </v-btn>
        </v-stepper-content>
      </v-form>

      <v-form ref="step2" v-model="state.stepValidation.step2" class="w-100">
        <v-stepper-step :complete="state.step > 2" step="2">
          Contact information
        </v-stepper-step>
        <v-stepper-content step="2">
          <v-row>
            <v-col cols="12">
              <v-textarea
                v-model="state.form.address"
                :rules="state.addressRules"
                label="Address"
                name="address"
                required
                autofocus
                rows="4"
              ></v-textarea>
            </v-col>
          </v-row>
          <v-btn
            color="success"
            :disabled="!state.stepValidation.step2"
            @click="state.step = 3"
          >
            Next
          </v-btn>
          <v-btn text @click="state.step = 1">
            Back
          </v-btn>
        </v-stepper-content>
      </v-form>

      <v-form ref="step3" v-model="state.stepValidation.step3" class="w-100">
        <v-stepper-step :complete="state.step > 3" step="3">
          Profile defails
        </v-stepper-step>
        <v-stepper-content step="3">
          <v-row>
            <v-col cols="12">
              <v-textarea
                v-model="state.form.about"
                :rules="state.addressRules"
                label="About you"
                name="address"
                required
                autofocus
                rows="4"
              ></v-textarea>
            </v-col>
          </v-row>
          <v-btn
            color="success"
            :disabled="!state.stepValidation.step3"
            @click="state.step = 4"
          >
            Review details
          </v-btn>
          <v-btn text @click="state.step = 2">
            Back
          </v-btn>
        </v-stepper-content>
      </v-form>

      <v-stepper-step step="4">
        Review information
      </v-stepper-step>
      <v-stepper-content step="4">
        <v-simple-table>
          <tbody>
            <tr>
              <td>Username</td>
              <td v-text="`@${state.form.username}`"></td>
            </tr>
            <tr>
              <td>First name</td>
              <td v-text="state.form.firstname"></td>
            </tr>
            <tr>
              <td>Last name</td>
              <td v-text="state.form.lastname"></td>
            </tr>
            <!-- <tr>
              <td>Date of birth</td>
              <td v-text="state.form.dob.date"></td>
            </tr> -->
            <tr>
              <td>Email</td>
              <td v-text="state.form.email"></td>
            </tr>
            <tr>
              <td>Address</td>
              <td v-text="state.form.address"></td>
            </tr>
            <tr>
              <td>Contact numbers</td>
              <td>
                <p v-for="(contact, idx) in state.form.contacts" :key="idx" class="mb-0 d-flex align-center">
                  <span v-text="contact.number"></span>
                  <v-icon v-if="contact.primary" small class="ml-2">
                    mdi-checkbox-blank-circle
                  </v-icon>
                </p>
              </td>
            </tr>
            <tr>
              <td>About you</td>
              <td v-text="state.form.about"></td>
            </tr>
          </tbody>
        </v-simple-table>
        <v-btn
          color="success"
          :disabled="state.isSaved || state.isSaving"
          @click="save"
          v-text="state.isSaved ? 'Saved' : state.isSaving ? 'Saving your information...' : 'Save and continue'"
        >
        </v-btn>
        <v-btn
          text
          :disabled="state.isSaved || state.isSaving"
          @click="state.step = 3"
        >
          Back
        </v-btn>
      </v-stepper-content>
    </v-stepper>
  </v-row>
</template>

<script lang="ts">
import { computed, defineComponent, reactive, ref, watch, useContext, useRouter } from '@nuxtjs/composition-api'
import { useActions, useGetters } from 'vuex-composition-helpers'
import findIndex from 'lodash/findIndex'
import cloneDeep from 'lodash/cloneDeep'
import { usernameRules, nameRules, emailRules, contactNumberRules, dateRules, addressRules } from '~/assets/scripts/validations'
export default defineComponent({
  auth: true,
  meta: {
    auth: true
  },
  setup() {
    const { $axios } = useContext()
    const { showSnack } = useActions(['showSnack'])
    const { user } = useGetters(['user'])
    const router = useRouter()
    const userClone = ref(cloneDeep(user))
    const state = reactive({
      isSaving: false,
      isSaved: false,
      step: 1,
      usernameRules: [
        (v: string) => !!v || 'Username is required',
        (v: string) => usernameRules('Username', v, null).valid || usernameRules('Username', v, 'Username can only contain lowercase letters and numbers. ex: gayans94').msg
      ],
      firtnameRules: [
        (v: string) => !!v || 'First name is required',
        (v: string) => nameRules('First name', v, null).valid || nameRules('First name', v, 'First name can only contain uppercase and loawercase letters').msg
      ],
      lastnameRules: [
        (v: string) => nameRules('Last name', v, null).valid || nameRules('Last name can only contain uppercase and loawercase letters', v, null).msg
      ],
      emailRules: [
        (v: string) => !!v || 'Email is required',
        (v: string) => emailRules('Email', v, null).valid || emailRules('Email', v, null).msg
      ],
      phoneNumberRules: [
        (v: string) => !!v || 'Contact number is required',
        (v: string) => contactNumberRules('Contact number', v, null).valid || contactNumberRules('Contact number', v, null).msg
      ],
      dateRules: [
        (v: string) => !!v || 'Contact number is required',
        (v: string) => dateRules('Contact number', v, null).valid || dateRules('Contact number', v, null).msg
      ],
      addressRules: [
        (v: string) => addressRules('Address', v, null).valid || addressRules('Address', v, null).msg
      ],
      stepValidation: {
        step1: false,
        step2: false,
        step3: false
      },
      form: {
        username: '',
        firstname: '',
        lastname: '',
        dob: {
          activePicker: null as string | null,
          date: null as string | null,
          menu: false
        },
        email: '',
        address: '',
        contacts: [
          {
            number: '',
            primary: true
          }
        ],
        about: ''
      }
    })

    const menu = ref()
    const menuState = computed(() => state.form.dob.menu)

    const bindUserDetails = (val: any) => {
      state.form.username = val.username || ''
      state.form.firstname = val.firstname || ''
      state.form.lastname = val.lastname || ''
      state.form.email = val.email || ''
      state.form.address = val.address || ''
      state.form.about = val.about || ''
      let contacts = [
        {
          number: '',
          primary: true
        }
      ]
      if (val.phone) {
        contacts = JSON.parse(val.phone)
      }
      state.form.contacts = contacts
      state.form.dob.date = val.dob || ''
    }

    if (user.value) {
      bindUserDetails(user.value)
    }

    watch(user, (val) => {
      bindUserDetails(val)
    })

    watch(menuState, (val) => {
      val && setTimeout(() => (state.form.dob.activePicker = 'YEAR'))
    })

    const isAddContactDisabled = computed(() =>
      findIndex(state.form.contacts, (contact: any) => contact.number.trim() === '') > -1 ||
      !state.stepValidation.step1
    )

    const saveDoB = (date: string) => {
      menu.value.save(date)
    }

    const addContactField = () => {
      state.form.contacts.push({
        number: '',
        primary: false
      })
    }

    const save = () => {
      state.isSaving = true
      try {
        const data = {
          id: user.value.id,
          cid: user.value.cid,
          username: state.form.username,
          displayname: state.form.username,
          firstname: state.form.firstname,
          lastname: state.form.lastname,
          address: state.form.address,
          about: state.form.about,
          phone: JSON.stringify(state.form.contacts)
        }
        $axios.put('updateuser', data)
          .then(() => {
            setTimeout(() => {
              state.isSaving = false
              state.isSaved = true
              setTimeout(() => {
                state.isSaved = false
                showSnack({
                  text: 'your information has been saved successfully!',
                  color: 'success'
                })
                router.push({ path: '/profile' })
              }, 2000)
            }, 2000)
          })
          .catch((error) => {
            state.isSaving = false
            state.isSaved = false
            console.error(error)
          })
      } catch (error) {
        state.isSaving = false
        state.isSaved = false
        console.error(error)
      }
    }

    return {
      user,
      menu,
      userClone,
      save,
      saveDoB,
      state,
      addContactField,
      isAddContactDisabled
    }
  }
})
</script>
