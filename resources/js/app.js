require('./bootstrap');

window.Vue = require('vue');

Vue.component('start-form', require('./components/StartForm.vue').default);
Vue.component('confirm', require('./components/Confirm.vue').default);
Vue.component('form-button', require('./components/modules/FormButton.vue').default);

const axiosPost = axios.create({
    xsrfHeaderName: "X-XSRF-TOKEN",
    withCredentials: true
});

const app = new Vue({
    el: '#app',
});
