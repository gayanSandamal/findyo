import axios from 'axios'
export default {
  data () {
    return {
      findYoApiUrl: null
    }
  },
  computed: {
    user () {
      return this.$store.state.user.user
    }
  },
  methods: {
    async callFindYoApi (object, successCallback, errorCallback) {
      try {
        const apiObject = await {
          baseURL: this.findYoApiUrl,
          method: object.method || 'GET',
          url: object.url,
          headers: {
            Authorization: `Bearer ${
              this.user && this.user.token
                ? this.user.token
                : object.data && object.data.token
                ? object.data.token
                : ''
            }`
          }
        }
        if (object.data) {
          apiObject.data = await object.data
        }
        const response = await axios(apiObject)
        successCallback(response)
      } catch (error) {
        errorCallback(error)
      }
    }
  },
  created () {
    // this.findYoApiUrl = process.env.NODE_ENV === 'production' ? 'https://findyo-admin.audiblepro.com/api/' : 'http://127.0.0.1:8000'
  }
}
