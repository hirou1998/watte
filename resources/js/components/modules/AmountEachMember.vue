<template>
    <li class="amount-item">
        <div class="amount-head-container">
            <profile-block :user="each.line_friend" iconSize="50"></profile-block>
            <div>
                <div class="amount-times">
                    <p class="normal-txt mb-0">{{times}}<span class="txt-smaller">倍</span></p>
                </div>
                <ratio-block :ratio-num="ratio"></ratio-block>
            </div>
        </div>
        <div class="amount-each-item-container">
            <div class="amount-each-item">
                <p class="normal-txt mb-0"><span class="small-txt light-txt">支払済</span><span class="amount-each-item-number">{{sum}}</span> 円</p>
            </div>
            <div class="amount-each-item">
                <p class="normal-txt mb-0"><span class="small-txt light-txt">割り勘代</span><span class="amount-each-item-number" :data-deficit="[gap < 0 ? 'true' : 'false']">{{gapDivided}}</span> 円</p>
            </div>
        </div>
        <button class="btn btn-underline" v-if="each.deals.length > 0 && !privateDealsVisibility" @click="showPrivateDeals">個人間の支払いを確認</button>
        <ul class="amount-each-private-item-container" v-show="privateDealsVisibility">
            <li
                v-for="deal in each.deals"
                :key="deal.partner.line_id"
                class="amount-each-private-item"
            >
                <profile-block :user="deal.partner" iconSize="20" nameSize="1.2rem"></profile-block>
                <ul class="amount-each-item-container">
                    <li class="small-txt amount-each-private-item-list"><span class="txt-smaller light-txt">貸し</span><span class="amount-each-private-item-number">{{deal.pay_sum}}</span>円</li>
                    <li class="small-txt amount-each-private-item-list"><span class="txt-smaller light-txt">借り</span><span class="amount-each-private-item-number">{{deal.paid_sum}}</span>円</li>
                    <li class="small-txt amount-each-private-item-list"><span class="txt-smaller light-txt">割り勘代</span><span class="amount-each-private-item-number" :data-deficit="[deal.pay_sum - deal.paid_sum < 0 ? 'true' : 'false']">{{deal.pay_sum - deal.paid_sum}}</span>円</li>
                </ul>
            </li>
        </ul>
        <hamburger-button
            v-if="isPaidByMe"
            @change="changeHamburgerButtonState"
        ></hamburger-button>
    </li>
</template>

<script>
import HamburgerButton from './HamburgerButton.vue';
import ProfileBlock from './ProfileBlock';
import RatioBlock from './RatioBlock'

export default {
    props: ['each', 'totalAmount', 'totalRatio', 'participants', 'user'],
    components: {
        HamburgerButton,
        ProfileBlock,
        RatioBlock
    },
    data(){
        return {
            privateDealsVisibility: false
        }
    },
    computed: {
        mustPayment(){
            return Math.ceil(Number(this.totalAmount) / Number(this.totalRatio) * Number(this.ratio));
        },
        gap(){
            let calcGap = Number(this.sumAddedPrivate) - this.mustPayment;
            return isNaN(calcGap) ? 0 : calcGap;
        },
        gapDivided(){
            return String(this.gap).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
        isPaidByMe(){
            return this.each.line_friend.line_id == this.user.userId ? true : false;
        },
        sum(){
            
            return String(this.sumAddedPrivate).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
        sumAddedPrivate(){
            let sumAddedPrivate = this.each.sum;
            this.each.deals.forEach(deal => {
                sumAddedPrivate += deal.pay_sum;
            })
            return sumAddedPrivate;
        },
        ratio(){
            return this.each.line_friend.pivot.ratio;
        },
        times(){
            return Math.ceil(this.participants.length * this.ratio / this.totalRatio * 100) / 100;
        },
    },
    methods: {
        changeHamburgerButtonState(){
            this.$emit('show', {
                ...this.each,
                gap: this.gap
            });
        },
        showPrivateDeals(){
            this.privateDealsVisibility = true;
        }
    }
}
</script>