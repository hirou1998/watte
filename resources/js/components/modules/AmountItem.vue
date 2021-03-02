<template>
    <li class="amount-item" :data-archived="amount.archive_flg">
        <section class="amount-item-inner" :data-menu-expanded="hamburgerButtonClicked">
            <div class="amount-head-container">
                <profile-block :user="amount.line_friend" icon-size="50"></profile-block>
                <p class="big-txt amount-number">{{number}} <span class="txt-smaller">円</span></p>
            </div>
            <div>
                <div class="amount-item-bottom">
                    <p class="small-txt amount-register-date">{{dateParser(amount.created_at)}}</p>
                    <p class="small-txt amount-memo">{{amount.note}}</p>
                </div>
                <div v-if="amount.deals.length > 0">
                    <ul class="amount-partners-container">
                        <li
                            v-for="deal in amount.deals"
                            :key="deal.partner"
                            class="amount-partner"
                        >
                            <profile-block :user="deal.line_friend" icon-size="20" name-size="1.2rem"></profile-block>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        <hamburger-button
            v-if="isPaidByMe"
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