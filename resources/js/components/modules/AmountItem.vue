<template>
    <li class="amount-item">
        <section class="amount-item-inner" :data-menu-expanded="hamburgerButtonClicked">
            <div class="amount-head-container">
                <profile-block :user="amount.line_friend" icon-size="50"></profile-block>
                <p class="big-txt amount-number">{{number}} <span class="txt-smaller">円</span></p>
            </div>
            <div>
                <p class="normal-txt amount-memo">{{amount.note}}</p>
                <ul class="amount-partners-container" v-if="amount.deals.length > 0">
                    <p class="small-txt amount-partner-title">相手<img src="/images/person-icon.png" alt=""></p>
                    <li
                        v-for="deal in amount.deals"
                        :key="deal.partner"
                        class="amount-partner"
                    >
                        <profile-block :user="deal.line_friend" icon-size="20"></profile-block>
                    </li>
                </ul>
            </div>
            <p class="small-txt amount-register-date">登録日時：{{dateParser(amount.created_at)}}</p>
        </section>
        <hamburger-button 
            :is-clicked="hamburgerButtonClicked"
            @change="changeHamburgerButtonState"
        ></hamburger-button>
    </li>
</template>

<script>
import FormatDateTimeMixin from '../../mixins/formatDateTimeMixin'
import HamburgerButton from './HamburgerButton.vue';
import ProfileBlock from './ProfileBlock'

export default {
    props: ['amount', 'user'],
    components: {
        HamburgerButton,
        ProfileBlock,
    },
    data(){
        return{
            hamburgerButtonClicked: false
        }
    },
    methods: {
        changeHamburgerButtonState(){
            this.$emit('show', this.amount);
        },
    },
    computed: {
        number(){
            return String(this.amount.amount).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
        isPaidByMe(){
            return this.amount.line_friend.line_id == this.user.userId ? true : false;
        }
    },
    mixins: [FormatDateTimeMixin]
}
</script>