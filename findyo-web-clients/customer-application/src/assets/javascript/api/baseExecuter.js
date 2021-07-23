import axios from 'axios';
export default {
  data() {
    return {
      findYoApiUrl: null,
    };
  },
  computed: {
    user() {
      return this.$store.state.user;
    },
  },
  methods: {
    async callFindYoApi(object, successCallback, errorCallback) {
      try {
        const apiObject = await {
          baseURL: this.findYoApiUrl,
          method: object.method || 'GET',
          url: object.url,
          headers: {
            Authorization: this.user ? `Bearer ${this.user.token}` : '',
          },
        };
        if (object.data) {
          apiObject.data = await object.data;
        }
        const response = await axios(apiObject);
        successCallback(response);
      } catch (error) {
        errorCallback(error);
      }
    },
  },
  created() {
    this.findYoApiUrl = process.env.VUE_APP_FIND_YO_API_URL;
  },
};
