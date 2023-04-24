/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import vSelect from 'vue-select';

Vue.component('v-select', vSelect);
import 'vue-select/dist/vue-select.css';
import 'sweetalert2/src/sweetalert2.scss'
import Swal from 'sweetalert2'
window.Swal=Swal;
const Toast = Swal.mixin({
  toast: true,
  position: 'top',
  showConfirmButton: false,
  timer: 5000
});
window.Toast=Toast;

import moment from 'moment';
window.moment = moment;

import 'vue-spinners/dist/vue-spinners.css'
import VueSpinners from 'vue-spinners'

Vue.use(VueSpinners);
//
// Vue.filter('datef',function(fightdate){
//   return moment(fightdate).format('LL');
// });

import VueBarcode from '@chenfengyuan/vue-barcode';
Vue.component(VueBarcode.name, VueBarcode);

import VueHtmlToPaper from 'vue-html-to-paper';
const options = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
}

import JwPagination from 'jw-vue-pagination';
Vue.component('jw-pagination', JwPagination);

Vue.use(VueHtmlToPaper, options);

// Vue.use(VueHtmlToPaper);

import VueApexCharts from 'vue-apexcharts';
Vue.use(VueApexCharts);

Vue.component('apexchart', VueApexCharts);


import { Form, HasError, AlertError,  AlertErrors,  AlertSuccess } from 'vform'
window.Form=Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertErrors.name, AlertErrors)
Vue.component(AlertSuccess.name, AlertSuccess)



Vue.filter('datef',function(updated_at){
  return moment(updated_at).format('lll');
});
Vue.filter('datec',function(created_at){
  return moment(created_at).format("ll");
});
Vue.filter('fightdatex',function(fightdate){
  return moment(fightdate).format("lll");
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('home', require('./components/user/home.vue').default);
Vue.component('claims', require('./components/user/claims.vue').default);
Vue.component('admin-dashboard', require('./components/admin/dashboard.vue').default);
Vue.component('reports', require('./components/admin/reports.vue').default);
Vue.component('admin-users', require('./components/admin/users.vue').default);
Vue.component('bethistory', require('./components/user/bethistory.vue').default);
Vue.component('transactions', require('./components/user/transactions.vue').default);
Vue.component('userwithdrawal', require('./components/user/withdrawaluser.vue').default);
Vue.component('cash', require('./components/user/cashonhand.vue').default);
Vue.component('changepassword', require('./components/user/changepasswordmodal.vue').default);
Vue.component('arena', require('./components/admin/arena.vue').default);
Vue.component('groups', require('./components/admin/groups.vue').default);
Vue.component('transuser', require('./components/user/transactionsuser.vue').default);
Vue.component('topplayers', require('./components/user/topplayers.vue').default);
Vue.component('cashier', require('./components/cashier/cashier.vue').default);
Vue.component('deposit', require('./components/cashier/deposit.vue').default);
Vue.component('logs', require('./components/admin/logs.vue').default);
Vue.component('session', require('./components/session.vue').default);
Vue.component('transcashier', require('./components/cashier/transactions.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
