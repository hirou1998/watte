require('./bootstrap');

window.Vue = require('vue');

Vue.component('loading', require('./components/modules/Loading.vue').default);
Vue.component('start-form', require('./components/StartForm.vue').default);
Vue.component('confirm', require('./components/Confirm.vue').default);
Vue.component('form-button', require('./components/modules/FormButton.vue').default);
Vue.component('add-amount', require('./components/AddAmount.vue').default);
Vue.component('amount-number-form', require('./components/modules/AmountNumberForm.vue').default);
Vue.component('amount-payer-form', require('./components/modules/AmountPayerForm').default);
Vue.component('amount-note-form', require('./components/modules/AmountNoteForm.vue').default);
Vue.component('amount-lists', require('./components/AmountLists.vue').default);
Vue.component('amount-item', require('./components/modules/AmountItem.vue').default);
Vue.component('amount-tab', require('./components/modules/AmountTab.vue').default);
Vue.component('amount-each-member', require('./components/modules/AmountEachMember.vue').default);
Vue.component('setting', require('./components/Setting.vue').default);
Vue.component('participants', require('./components/Participants.vue').default);
Vue.component('participant', require('./components/modules/Participant.vue').default);
Vue.component('profile-block', require('./components/modules/ProfileBlock.vue').default);
Vue.component('ratio-block', require('./components/modules/RatioBlock.vue').default);
Vue.component('ratio-modal', require('./components/modules/RatioModal.vue').default);

const app = new Vue({
    el: '#app',
});
