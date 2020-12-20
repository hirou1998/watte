<template>
    <li class="amount-item">
        <div class="amount-head-container">
            <img 
                class="amount-payer-icon"
                :src="each.line_friend.picture_url" 
                :alt="each.line_friend.display_name"
            >
            <p class="normal-txt amount-payer">{{each.line_friend.display_name}}</p>
        </div>
        <table class="amount-each-table">
            <tr>
                <th class="small-txt amount-each-head amount-each-item">支払済金額</th>
                <th class="small-txt amount-each-head amount-each-item">差額</th>
            </tr>
            <tr>
                <td class="normal-txt amount-each-number amount-each-item">{{each.amount_sum}} <span class="smaller-txt">円</span></td>
                <td class="normal-txt amount-each-number amount-each-item">{{gap}} <span class="smaller-txt">円</span></td>
            </tr>
        </table>
    </li>
</template>

<script>
export default {
    props: ['each', 'total'],
    computed: {
        gap(){
            let calcGap = Number(this.total) - Number(this.each.amount_sum);
            return isNaN(calcGap) ? 0 : calcGap;
        }
    }
}
</script>