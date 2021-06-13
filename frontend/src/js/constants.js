const Constants = {
  AXIOS_QUERY_STATE_LOADING: 'loading',
  AXIOS_QUERY_STATE_LOADED: 'loaded',
  AXIOS_QUERY_STATE_FAILED: 'failed'
};

Constants.install = (Vue) => {
  Vue.prototype.$CONSTANT = (key) => {
    return Constants[key];
  }
};

export default Constants;
