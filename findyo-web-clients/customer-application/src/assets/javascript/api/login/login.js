import baseApiExecuter from '@/assets/javascript/api/baseExecuter';

export default {
  mixins: [baseApiExecuter],
  data() {
    return {};
  },
  methods: {
    async register(data, successCallback, errorCallback) {
      try {
        await this.callFindYoApi(
          {
            url: data.url,
            data: data.data,
            method: data.method,
          },
          async (response) => {
            await successCallback(response);
          },
          async (error) => {
            await errorCallback(error);
          }
        );
      } catch (error) {
        errorCallback(error);
      }
    },
  },
};
