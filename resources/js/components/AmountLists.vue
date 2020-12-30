<template>
    <article>
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
        <section v-if="activeTab == 0" class="amount-section">
            <ul>
                <amount-item 
                    v-for="amount in amounts"
                    :key="amount.id"
                    :amount="amount"
                ></amount-item>
            </ul>
        </section>
        <section v-else class="amount-section">
            <p class="normal-txt">一人当たり: <span class="txt-bigger">{{eachTotalDivided}}</span>円(合計金額: <span class="txt-bigger">{{sumDivided}}</span>円)</p>
            <amount-each-member
                v-for="item in each"
                :each="item"
                :total="eachTotal"
                :key="item.friend_id"
            ></amount-each-member>
        </section>
    </article>
</template>

<script>
import AmountEachMember from './modules/AmountEachMember'
import AmountItem from './modules/AmountItem'
import AmountTab from './modules/AmountTab'

export default {
    components: {
        AmountEachMember,
        AmountItem,
        AmountTab
    },
    props: ['amounts', 'each'],
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
            activeTab: 0
        }
    },
    computed: {
        sum(){
            let sumAmount = 0;
            this.each.forEach(item => {
                sumAmount += Number(item.amount_sum);
            });
            return sumAmount;
        },
        sumDivided(){
            return String(this.sum).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
        eachTotal(){
            let divided = Math.round(this.sum / this.each.length);
            return divided;
        },
        eachTotalDivided(){
            return String(this.eachTotal).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        }
    },
    methods: {
        selectTab(tab){
            this.activeTab = tab
        }
    }
}
</script>