import Vue from 'vue';
import VueRouter from 'vue-router'
import App from './App';
import SuiVue from 'semantic-ui-vue';
import Constants from './constants';
import routes from './routes';

Vue.use(SuiVue);
Vue.use(Constants);
Vue.use(VueRouter);

Vue.config.productionTip = false;

const router = new VueRouter({
  root: '/',
  mode: 'history',
  routes: routes
});

new Vue({
  router,
  render: h => h(App)
}).$mount('#app');
