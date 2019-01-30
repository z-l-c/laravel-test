
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('book', require('./components/Book.vue'));

/**
 * Next, import modules.
 */

import Croppa from 'vue-croppa';
import 'vue-croppa/dist/vue-croppa.css';
Vue.component('croppa', Croppa.component);


const app = new Vue({
    el: '#app',
    data: {
      croppa: {},
      dataUrl: ''
    }
});
