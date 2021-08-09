<template>
<div>
<p>Logout</p>
</div>

</template>

<script>
import firebase from 'firebase/app'
import 'firebase/auth'

export default {
  name: 'logout',
  mounted () {
    firebase.auth().signOut().then(() => {
      this.$router.push('/')
    }).catch((error) => {
      alert(error)
    })
  }
}
</script>

<style scoped>
@import "./../assets/styles/firebaseui.css";
</style>
