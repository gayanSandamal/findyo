<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6">
      <div class="text-center mt-3">
        <h1>Sign in</h1>
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

              <v-checkbox v-model="state.checkbox" label="Remember me"></v-checkbox>

              <v-btn
                :disabled="!state.valid"
                color="success"
                class="mr-4"
                @click="validate"
              >
                Login
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

<script>
import {
  defineComponent,
  reactive,
  useContext
} from '@nuxtjs/composition-api'
export default defineComponent({
  setup(_, context) {
    const { $auth } = useContext()
    const state = reactive({
      valid: true,
      email: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid'
      ],
      password: '',
      passwordRules: [v => !!v || 'Password is required'],
      checkbox: false,
      alert: false,
      errorList: ''
    })

    const validate = () => {
      context.$refs.form.validate()
      if (state.valid) {
        login()
      }
    }
    const reset = () => {
      context.$refs.form.reset()
    }
    const login = async () => {
      try {
        const postData = {
          email: state.email,
          password: state.password
        }
        // REMOVE COMMENTED CODE BLOCK IF NOT USING
        // const response = await $axios.post('emaillogin', postData)
        const response = await $auth.loginWith('local', {
          data: postData
        })
        console.log(response) // REMOVE UNWANTED CONSOLE LOGS
        const { status, data } = response
        if (status === 200) {
          setUser(data)
        } else if (status === 202) {
          showBackendValidations(response)
        } else if (status === 203) {
          state.errorList = 'username or password is incorrect please try again'
          state.alert = true
        }
      } catch (error) {
        console.log(error) // USE CONSOLE.ERROR NEXT TIME
      }
    }
    const setUser = (user) => {
      $auth.setUser(user)
    }
    const showBackendValidations = (responseData) => {
      state.errorList = ''
      if (!responseData) {
        state.errorList = 'Something went wrong'
        state.alert = true
        return
      }
      if (responseData.data.email && responseData.data.email.length > 0) {
        responseData.data.email.forEach((err, index) => {
          state.errorList += `${err}${
            responseData.data.email.length - 1 !== index ? '\n \n' : ''
          }`
        })
        state.alert = true
      } else if (
        responseData.data.password &&
        responseData.data.password.length > 0
      ) {
        responseData.data.password.forEach((err, index) => {
          state.errorList += `${err}${
            responseData.data.password.length - 1 !== index ? '\n \n' : ''
          }`
        })
        this.alert = true
      } else if (
        responseData.data.c_password &&
        responseData.data.c_password.length > 0
      ) {
        responseData.data.c_password.forEach((err, index) => {
          state.errorList += `${err.replace('c password', 'confirm password')}${
            responseData.data.c_password.length - 1 !== index ? '\n \n' : ''
          }`
        })
        this.alert = true
      }
    }
    // ALWAYS USE camelCase FOR FUNCTION NAMES
    const hideAlert = () => {
      setInterval(() => {
        state.alert = false
      }, 5000)
    }
    hideAlert()
    return {
      state,
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
