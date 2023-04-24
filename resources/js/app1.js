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
import Vue from "vue";

import JsonExcel from "vue-json-excel";

Vue.component("downloadExcel", JsonExcel);

import JwPagination from 'jw-vue-pagination';
Vue.component('jw-pagination', JwPagination);
Vue.component('pagination', require('laravel-vue-pagination'));

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
Vue.filter('datebet',function(created_at){
  return moment(created_at).format('lll');
});
Vue.filter('datec',function(created_at){
  return moment(created_at).format("ll");
});
Vue.filter('fightdatex',function(fightdate){
  return moment(fightdate).format("lll");
});
Vue.filter('currency', function (value) {
    return parseFloat(value).toFixed(2);
});
Vue.filter('currency1', function (value) {
    return value.slice(0,-2);
});
Vue.filter('currency2', function (value) {
  return value.slice(0,-4).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
});


import Calendar from 'v-calendar/lib/components/calendar.umd'
import DatePicker from 'v-calendar/lib/components/date-picker.umd'

// Register components in your 'main.js'
Vue.component('calendar', Calendar);
Vue.component('date-picker', DatePicker);

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
Vue.component('reportdashboard', require('./components/admin/reportdashboard.vue').default);
Vue.component('reports', require('./components/admin/reports.vue').default);
Vue.component('accountreports', require('./components/admin/accountsreports.vue').default);
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
Vue.component('pending', require('./components/user/pending.vue').default);
Vue.component('history', require('./components/user/history.vue').default);
Vue.component('cashier', require('./components/cashier/cashier.vue').default);
Vue.component('deposit', require('./components/cashier/deposit.vue').default);
Vue.component('logs', require('./components/admin/logs.vue').default);
Vue.component('session', require('./components/session.vue').default);
Vue.component('transcashier', require('./components/cashier/transactions.vue').default);
Vue.component('modalcash', require('./components/cashier/modalcashin.vue').default);
Vue.component('transactionadminreport', require('./components/admin/transactions.vue').default);
Vue.component('spotcheck', require('./components/admin/spotcheck.vue').default);
Vue.component('monitoring', require('./components/admin/monitoring.vue').default);
Vue.component('test', require('./components/test.vue').default);
Vue.component('test2', require('./components/test2.vue').default);
Vue.component('transferfunds', require('./components/user/transferfunds.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
Vue.config.devtools = false
Vue.config.debug = false
