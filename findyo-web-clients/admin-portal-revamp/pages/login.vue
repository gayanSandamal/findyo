<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6">
      <div class="text-center mt-3">
        <h1>Sign in</h1>
      </div>
      <div class="text-center">
        <v-alert
          :value="alert"
          color="red"
          dark
          border="top"
          icon="mdi-home"
          transition="slide-y-transition"
        >
          {{ errorList }}
        </v-alert>
      </div>
      <div class="register-card">
        <v-card class="mt-5">
          <v-card-title class="headline">
            <h5>Please fill the details</h5>
          </v-card-title>
          <v-card-text>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-text-field
                v-model="email"
                :rules="emailRules"
                label="E-mail"
                required
              ></v-text-field>

              <v-text-field
                v-model="password"
                :rules="passwordRules"
                label="Password"
                required
                type="password"
              ></v-text-field>

              <v-checkbox v-model="checkbox" label="Remember me"></v-checkbox>

              <v-btn
                :disabled="!valid"
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
export default {
  data: () => ({
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
  }),
  computed: {
    //
  },
  mounted() {
    this.hide_alert()
  },
  methods: {
    validate() {
      this.$refs.form.validate()
      if (this.valid) {
        this.login()
      }
    },
    reset() {
      this.$refs.form.reset()
    },
    async login() {
      try {
        const postData = {
          email: this.email,
          password: this.password
        }
        // const response = await this.$axios.post('emaillogin', postData)
        const response = await this.$auth.loginWith('local', {
          data: postData
        })
        console.log(response)
        const { status, data } = response
        if (status === 200) {
          this.setUser(data)
        } else if (status === 202) {
          this.showBackendValidations(response)
        } else if (status === 203) {
          this.errorList = 'username or password is incorrect please try again'
          this.alert = true
        }
      } catch (error) {
        console.log(error)
      }
    },
    setUser(user) {
      this.$auth.setUser(user)
    },
    showBackendValidations(responseData) {
      this.errorList = ''
      if (!responseData) {
        this.errorList = 'Something went wrong'
        this.alert = true
        return
      }
      if (responseData.data.email && responseData.data.email.length > 0) {
        responseData.data.email.forEach((err, index) => {
          this.errorList += `${err}${
            responseData.data.email.length - 1 !== index ? '\n \n' : ''
          }`
        })
        this.alert = true
      } else if (
        responseData.data.password &&
        responseData.data.password.length > 0
      ) {
        responseData.data.password.forEach((err, index) => {
          this.errorList += `${err}${
            responseData.data.password.length - 1 !== index ? '\n \n' : ''
          }`
        })
        this.alert = true
      } else if (
        responseData.data.c_password &&
        responseData.data.c_password.length > 0
      ) {
        responseData.data.c_password.forEach((err, index) => {
          this.errorList += `${err.replace('c password', 'confirm password')}${
            responseData.data.c_password.length - 1 !== index ? '\n \n' : ''
          }`
        })
        this.alert = true
      }
    },
    hide_alert() {
      setInterval(() => {
        this.alert = false
      }, 5000)
    }
  }
}
</script>

<style lang="scss" scoped>
.register-card {
  margin-top: 10vh;
}
</style>
