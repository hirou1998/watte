<template>
    <section>
        <article v-if="!isLoading">
            <h1 class="amount-show-title txt-big">{{event.event_name}}</h1>
            <ul class="amount-tab-container" :data-selected="activeTab">
                <amount-tab
                    v-for="tab in tabList"
                    :value="tab.value"
                    :id="tab.id"
                    :key="tab.id"
                    :selected="activeTab"
                    @select="selectTab"
                ></amount-tab>
            </ul>
            <section v-show="activeTab == 0" class="amount-section">
                <ul>
                    <amount-item 
                        v-for="amount in amounts"
                        :key="amount.id"
                        :amount="amount"
                    ></amount-item>
                </ul>
            </section>
            <section v-show="activeTab == 1" class="amount-section">
                <p class="small-txt amount-result-head">1人当たり: <span class="big-txt">{{PaymentPerPersonDivided}}</span> 円 (合計金額: <span class="big-txt"> {{sumDivided}} </span>円)</p>
                <amount-each-member
                    v-for="item in each"
                    :each="item"
                    :total-amount="sum"
                    :total-ratio="totalRatio"
                    :participants="participants"
                    :key="item.friend_id"
                ></amount-each-member>
            </section>
        </article>
        <loading v-if="isLoading"></loading>
    </section>
</template>

<script>
import AmountEachMember from './modules/AmountEachMember'
import AmountItem from './modules/AmountItem'
import AmountTab from './modules/AmountTab'
import Loading from './modules/Loading'
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'

export default {
    components: {
        AmountEachMember,
        AmountItem,
        AmountTab,
        Loading
    },
    props: ['amounts', 'each', 'event', 'participants'],
    data: function(){
        return{
            tabList: [
                {
                    id: 0,
                    value: '支払いごと'
                },
                {
                    id: 1,
                    value: 'ユーザーごと'
                }
            ],
            activeTab: 0,
            isLoading: false
        }
    },
    computed: {
        sum(){
            let sumAmount = 0;
            this.each.forEach(item => {
                sumAmount += Number(item.sum);
            });
            return sumAmount;
        },
        sumDivided(){
            return String(this.sum).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
        PaymentPerPerson(){
            let divided = Math.round(this.sum / this.each.length);
            return divided;
        },
        PaymentPerPersonDivided(){
            return String(this.PaymentPerPerson).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
        totalRatio(){
            let total = 0;
            this.each.forEach(item => {
                let ratio = item.line_friend.pivot.ratio;
                total += ratio;
            })
            return total;
        }
    },
    methods: {
        selectTab(tab){
            this.activeTab = tab
        }
    },
    mounted(){
        window.liff.init({
            liffId: this.liff
        })
        .then((data) => {
            //this.checkAccess();
        })
    },
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin]
}
</script>