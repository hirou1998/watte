<template>
    <li class="amount-item">
        <div class="amount-head-container">
            <profile-block :user="each.line_friend"></profile-block>
            <div>
                <div class="amount-times">
                    <p class="normal-txt mb-0">{{times}}<span class="txt-smaller">倍</span></p>
                </div>
                <ratio-block :ratio-num="ratio"></ratio-block>
            </div>
        </div>
        <table class="amount-each-table">
            <tr>
                <th class="small-txt amount-each-head amount-each-item">支払済金額</th>
                <th class="small-txt amount-each-head amount-each-item">割り勘代</th>
            </tr>
            <tr>
                <td class="normal-txt amount-each-number amount-each-item">{{sum}} <span class="smaller-txt">円</span></td>
                <td 
                    class="normal-txt amount-each-number amount-each-item"
                    :data-deficit="[gap < 0 ? 'true' : 'false']"
                >{{gapDivided}} <span class="smaller-txt">円</span></td>
            </tr>
        </table>
    </li>
</template>

<script>
import ProfileBlock from './ProfileBlock';
import RatioBlock from './RatioBlock'

export default {
    props: ['each', 'totalAmount', 'totalRatio', 'participantsNum'],
    components: {
        ProfileBlock,
        RatioBlock
    },
    computed: {
        mustPayment(){
            return Math.ceil(Number(this.totalAmount) / Number(this.totalRatio) * Number(this.ratio));
        },
        gap(){
            let calcGap = Number(this.each.amount_sum) - this.mustPayment;
            return isNaN(calcGap) ? 0 : calcGap;
        },
        gapDivided(){
            return String(this.gap).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
        sum(){
            return String(this.each.amount_sum).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
        ratio(){
            return this.each.line_friend.events[0].pivot.ratio;
        },
        times(){
            return Math.round(this.participantsNum * this.ratio / this.totalRatio * 100) / 100;
        }
    }
}
</script>