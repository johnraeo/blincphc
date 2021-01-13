import VueRouter from 'vue-router';

import style from '../sass/app.scss';

import Blinc from './blinc';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
const account_name ='asd-chad'
const password = 'P5HxoQwu7jbL6Ure87XsEqJxmYEU9tFjDqCFgg5276wh2'
const blinc = new Blinc({
  account_name,
  password
})
// blinc.connect()
require('./bootstrap');


window.Vue = require('vue');
Vue.use(VueRouter)
Vue.prototype.$blinc = blinc

//User
var router = new VueRouter({
    routes: [
        {path: '/change-password', component: require('./components/Dashboard/User/ChangePassword.vue').default},
        {path: '/limits-and-verifications', component: require('./components/Dashboard/User/LimitsAndVerifications.vue').default},
        {path: '/settings', component: require('./components/Dashboard/User/Settings.vue').default},
        {path: '/profile-settings', component: require('./components/Dashboard/User/Profile.vue').default},

        {path: '/home', component: require('./components/Dashboard/Transactions/History.vue').default},

        {path: '/login', component: require('./components/Pages/Login.vue').default},
        {path: '/sign-up', component: require('./components/Pages/Register.vue').default}
    ],
    mode: 'history'
  })

//Auth
var authRouter = new VueRouter({
  routes: [
      {
        path: '/',
        component: require('./components/Pages/Landing.vue').default
      },
      {
        path: '/login', 
        component: require('./components/Pages/Login.vue').default
      },
      {
        path: '/login/:message', 
        props: true,
        component: require('./components/Pages/Login.vue').default
      },
      {path: '/sign-up', component: require('./components/Pages/Register.vue').default},
      {path: '/reset', component: require('./components/Pages/ForgotPass.vue').default},
      {path: '/reset-pass', component: require('./components/Pages/ResetPass.vue').default}
  ],
  mode: 'history'
})


//Dashboard
Vue.component('BillsPaymentModal', require('./components/Dashboard/Transactions/BillsPayment.vue').default);
Vue.component('CashInModal', require('./components/Dashboard/Transactions/CashIn.vue').default);
Vue.component('CashOutModal', require('./components/Dashboard/Transactions/CashOut.vue').default);
Vue.component('ConvertModal', require('./components/Dashboard/Transactions/Convert.vue').default);
Vue.component('RequestFundsModal', require('./components/Dashboard/Transactions/RequestFunds.vue').default);
Vue.component('SendFundsModal', require('./components/Dashboard/Transactions/SendFunds.vue').default);
Vue.component("TransactComponent", require('./components/Dashboard/Transact.vue').default);
Vue.component("ChangePasswordModal", require('./components/Dashboard/User/ChangePassword.vue').default);

//Functions
Vue.component('EnrollBTSModal', require('./components/Dashboard/EnrollBTS.vue').default);
Vue.component('DownloadModal', require('./components/Dashboard/Download.vue').default);
Vue.component('InviteModal', require('./components/Dashboard/Invite.vue').default)


//Global
Vue.component("navbar-component", require('./components/Navbar.vue').default);
Vue.component('landing-nav-component', require('./components/LandingNavbar.vue').default);
Vue.component("ModalComponent", require('./components/Dashboard/Modal.vue').default);


//Loader
Vue.component("loader-component", require('./components/Loader.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router: router,
    style,
    data: {
      ModalComponent: false,
      EnrollBTSModal: false,
      TransactComponent: false,
      DownloadModal: false,
      ChangePasswordModal: false,
      InviteModal: false,
    }
});

const auth = new Vue({
  el: '#auth',
  router: authRouter,
  style,
  data: {
    
  }
});