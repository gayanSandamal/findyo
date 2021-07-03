import axios from 'axios';
export default {
  data() {
    return {
      findYoApiUrl: null,
    };
  },
  methods: {
    async callFindYoApi(object, successCallback, errorCallback) {
      try {
        const apiObject = await {
          baseURL: this.findYoApiUrl,
          method: object.method || 'GET',
          url: object.url,
          Headers: {},
        };
        if (object.method === 'POST') {
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
