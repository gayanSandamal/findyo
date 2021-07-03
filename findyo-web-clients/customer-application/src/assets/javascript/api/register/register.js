import baseApiExecuter from '@/assets/javascript/api/baseExecuter';

export default {
  mixins: [baseApiExecuter],
  data() {
    return {};
  },
  methods: {
    async register(data, successCallback, errorCallback) {
      try {
        await this.callFindYoApi({
            url: data.url,
            data: data.data,
            method: data.method,
          },
          (response) => {
            successCallback(response);
          },
          (error) => {
            errorCallback(error);
          }
        );
      } catch (error) {
        errorCallback(error);
      }
    },
  },
};