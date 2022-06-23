import Vue from 'vue';
import App from './App.vue';
import "./bootstrap.js";
import BootstrapVue from 'bootstrap-vue';
import VueApexCharts from 'vue-apexcharts'
import Vuelidate from 'vuelidate';
import vco from "v-click-outside";
import router from './router';
import store from './state/store';
import _ from 'lodash';
import FormValidation from '@/components/form-validation.vue';

import '@/assets/scss/app.scss';

Vue.prototype.axios = window.axios;
Vue.prototype._ = _;
Vue.config.productionTip = false;
Vue.use(BootstrapVue);
Vue.use(vco);
Vue.use(Vuelidate);
Vue.component('apexchart', VueApexCharts)
Vue.component('FormValidation', FormValidation);

new Vue({
    router: router,
    store: store,
    render: h => h(App)
}).$mount('#app')