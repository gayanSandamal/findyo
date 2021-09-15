<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6">
      <div class="text-center mt-3">
        <h1>Sign up</h1>
      </div>
      <div class="text-center">
        <v-alert
          :value="state.alert"
          color="red"
          dark
          border="top"
          icon="mdi-home"
          transition="slide-y-transition"
        >
          {{ state.errorList }}
        </v-alert>
      </div>
      <div class="register-card">
        <v-card class="mt-5">
          <v-card-title class="headline">
            <h5>Please fill the details</h5>
          </v-card-title>
          <v-card-text>
            <v-form ref="form" v-model="state.valid" lazy-validation>
              <v-text-field
                v-model="state.email"
                :rules="state.emailRules"
                label="E-mail"
                required
              ></v-text-field>

              <v-text-field
                v-model="state.password"
                :rules="state.passwordRules"
                label="Password"
                required
                type="password"
              ></v-text-field>

              <v-text-field
                v-model="state.confirmPassword"
                :rules="state.confirmPasswordRules"
                label="confirm Password"
                required
                type="password"
              ></v-text-field>

              <v-checkbox
                v-model="state.checkbox"
                :rules="[v => !!v || 'You must agree to continue!']"
                label="Do you agree to terms and conditions?"
                required
              ></v-checkbox>

              <v-btn
                :disabled="!state.valid"
                color="success"
                class="mr-4"
                @click="validate"
              >
                Register
              </v-btn>

              <v-btn color="error" class="mr-4" @click="reset">
                Reset Form
              </v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </div>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import {
  defineComponent,
  reactive,
  computed,
  useContext,
  useRouter
} from '@nuxtjs/composition-api'
import forEach from 'lodash/forEach'
export default defineComponent({
  auth: false,
  setup(_, context: any) {
    const { $axios } = useContext()
    const router = useRouter()
    const state = reactive({
      valid: true,
      email: '',
      emailRules: [
        (v: string) => !!v || 'E-mail is required',
        (v: string) => /.+@.+\..+/.test(v) || 'E-mail must be valid'
      ],
      password: '',
      passwordRules: [
        (v: string) => !!v || 'Password is required',
        (v: string) =>
          (v && v.length >= 8) || 'Password must be greater than 8 characters'
      ],
      confirmPassword: '',
      checkbox: false,
      alert: false,
      errorList: ''
    })
    const confirmPasswordRules = computed(() => {
      return [
        (v: string) => !!v || 'Confirm Password is required',
        (v: string) => (v && v === state.password) || 'Password not match'
      ]
    })

    const validate = () => {
      context.refs.form.validate()
      if (state.valid) {
        save()
      }
    }
    const reset = () => {
      context.refs.form.reset()
    }
    const save = async () => {
      try {
        const postData = {
          email: state.email,
          password: state.password,
          c_password: state.confirmPassword
        }
        const response = await $axios.post('emailregister', postData)
        const { status } = response
        if (status === 200) {
          router.push('/login')
        } else if (status === 202) {
          showBackendValidations(response)
        }
      } catch (error) {
        console.log(error)
      }
    }
    const showBackendValidations = (responseData: any) => {
      state.errorList = ''
      if (!responseData) {
        state.errorList = 'Something went wrong'
        state.alert = true
        return
      }
      if (responseData.data.email && responseData.data.email.length > 0) {
        forEach(responseData.data.email, (err: string, index: number) => {
          state.errorList += `${err}${
            responseData.data.email.length - 1 !== index ? '\n \n' : ''
          }`
        })
        state.alert = true
      } else if (
        responseData.data.password &&
        responseData.data.password.length > 0
      ) {
        forEach(responseData.data.password, (err: string, index: number) => {
          state.errorList += `${err}${
            responseData.data.password.length - 1 !== index ? '\n \n' : ''
          }`
        })
        state.alert = true
      } else if (
        responseData.data.c_password &&
        responseData.data.c_password.length > 0
      ) {
        forEach(responseData.data.c_password, (err: string, index: number) => {
          state.errorList += `${err.replace('c password', 'confirm password')}${
            responseData.data.c_password.length - 1 !== index ? '\n \n' : ''
          }`
        })
        state.alert = true
      }
    }
    const hideAlert = () => {
      setInterval(() => {
        state.alert = false
      }, 5000)
    }
    hideAlert()
    return {
      state,
      confirmPasswordRules,
      validate,
      reset
    }
  }
})
</script>

<style lang="scss" scoped>
.register-card {
  margin-top: 10vh;
}
</style>
