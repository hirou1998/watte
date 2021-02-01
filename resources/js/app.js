require('./bootstrap');

window.Vue = require('vue');

Vue.component('loading', require('./components/modules/Loading.vue').default);
Vue.component('start-form', require('./components/StartForm.vue').default);
Vue.component('confirm', require('./components/Confirm.vue').default);
Vue.component('form-button', require('./components/modules/FormButton.vue').default);
Vue.component('add-amount', require('./components/AddAmount.vue').default);
Vue.component('amount-number-form', require('./components/modules/AmountNumberForm.vue').default);
Vue.component('amount-user-form', require('./components/modules/AmountUserForm.vue').default);
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
Vue.component('toggle-block', require('./components/modules/ToggleBlock.vue').default);
Vue.component('api-loading', require('./components/modules/ApiLoading.vue').default);
Vue.component('hamburger-button', require('./components/modules/HamburgerButton.vue').default);
Vue.component('amount-itme-menu', require('./components/modules/AmountItemMenu.vue').default);
Vue.component('modal-base', require('./components/modules/ModalBase.vue').default);
Vue.component('amount-menu-modal', require('./components/modules/AmountMenuModal.vue').default);
Vue.component('option-window', require('./components/modules/OptionWindow.vue').default);
Vue.component('amount-item-option-window', require('./components/modules/AmountItemOptionWindow.vue').default);
Vue.component('info-block', require('./components/modules/InfoBlock.vue').default);
Vue.component('info-modal', require('./components/modules/InfoModal.vue').default);
Vue.component('image-form', require('./components/modules/ImageForm.vue').default);
Vue.component('event-edit', require('./components/EventEdit.vue').default);

axios.defaults.headers.common['Authorization'] = "Bearer " + document.querySelector('meta[name="user-token"]').getAttribute('content');

const app = new Vue({
    el: '#app',
});
