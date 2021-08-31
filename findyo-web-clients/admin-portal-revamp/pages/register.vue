<template>
  <v-row justify="center" align="center">
    <v-col cols="12" sm="8" md="6">
      <div class="text-center mt-3">
        <h1>Sign up</h1>
      </div>
      <div class="register-card">
        <v-card class="mt-5">
          <v-card-title class="headline">
            <h5>Please fill the details</h5>
          </v-card-title>
          <v-card-text>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-text-field
                v-model="name"
                :counter="100"
                :rules="nameRules"
                label="Name"
                required
              ></v-text-field>

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

              <v-text-field
                v-model="confirmPassword"
                :rules="confirmPasswordRules"
                label="confirmPassword"
                required
                type="password"
              ></v-text-field>

              <v-checkbox
                v-model="checkbox"
                :rules="[v => !!v || 'You must agree to continue!']"
                label="Do you agree to terms and conditions?"
                required
              ></v-checkbox>

              <v-btn
                :disabled="!valid"
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

<script>
export default {
  data: () => ({
    valid: true,
    name: '',
    nameRules: [
      v => !!v || 'Name is required',
      v => (v && v.length <= 100) || 'Name must be less than 100 characters'
    ],
    email: '',
    emailRules: [
      v => !!v || 'E-mail is required',
      v => /.+@.+\..+/.test(v) || 'E-mail must be valid'
    ],
    password: '',
    passwordRules: [
      v => !!v || 'Password is required',
      v => (v && v.length >= 8) || 'Password must be greater than 8 characters'
    ],
    confirmPassword: '',
    checkbox: false
  }),
  computed: {
    confirmPasswordRules() {
      return [
        v => !!v || 'Confirm Password is required',
        v => (v && v === this.password) || 'Password not match'
      ]
    }
  },
  methods: {
    validate() {
      this.$refs.form.validate()
      if (this.valid) {
        this.save()
      }
    },
    reset() {
      this.$refs.form.reset()
    },
    save() {
      //
    }
  }
}
</script>

<style lang="scss" scoped>
.register-card {
  margin-top: 10vh;
}
</style>
