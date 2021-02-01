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
                        :user="userInfo"
                        @show="showMenuModal"
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
        <amount-item-option-window 
            :visibility="menuModalVisibility"
            :target="targetAmount"
            @close="menuModalVisibility = false"
            @archive="showConfirmModal"
            @delete="showConfirmModal"
            @unarchive="showConfirmModal"
        ></amount-item-option-window>
        <amount-menu-modal
            @close="modalVisibility = false"
            @execute="executeAction"
            :modal-type="modalType"
            :target="targetAmount"
            :visibility="modalVisibility"
        ></amount-menu-modal>
    </section>
</template>

<script>
import AmountEachMember from './modules/AmountEachMember'
import AmountItem from './modules/AmountItem'
import AmountItemOptionWindow from './modules/AmountItemOptionWindow'
import AmountMenuModal from './modules/AmountMenuModal'
import AmountTab from './modules/AmountTab'
import Loading from './modules/Loading'
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'

export default {
    components: {
        AmountEachMember,
        AmountItem,
        AmountItemOptionWindow,
        AmountMenuModal,
        AmountTab,
        Loading
    },
    props: ['event', 'participants'],
    data: function(){
        return{
            amounts: undefined,
            each: undefined,
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
            isLoading: true,
            targetAmount: {},
            modalVisibility: false,
            menuModalVisibility: false,
            modalType: '',
        }
    },
    computed: {
        sum(){
            let sumAmount = 0;
            this.amounts.forEach(item => {
                sumAmount += Number(item.amount);
            });
            return sumAmount;
        },
        sumDivided(){
            return String(this.sum).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
        PaymentPerPerson(){
            let divided = Math.ceil(this.sum / this.each.length);
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
        archiveRelatedAction(type){
            let url
            let archive_flg_state
            let formula
            let action
            if(type === 'archive'){
                url = `/amount/archive/${this.targetAmount.id}`
                archive_flg_state = 1
                formula = 1
                action = '精算'
            }else if(type === 'unarchive'){
                url = `/amount/unarchive/${this.targetAmount.id}`
                archive_flg_state = 0
                formula = -1
                action = '未精算'
            }
            
            window.axios.put(url)
            .then(({data}) => {
                let archivedAmountDivided = Math.ceil(this.targetAmount.amount / this.participants.length)
                this.amounts = this.amounts.map(amount => {
                    if(amount.id === this.targetAmount.id){
                        return {
                            ...amount,
                            archive_flg: archive_flg_state
                        }
                    }else{
                        return amount
                    }
                });
                this.each = this.each.map(item => {
                    if(item.line_friend.line_id === this.targetAmount.line_friend.line_id){
                        return {
                            ...item,
                            sum: item.sum + (formula * archivedAmountDivided + formula * this.targetAmount.amount * -1)
                        }
                    }else{
                        return {
                            ...item,
                            sum: item.sum + formula * archivedAmountDivided
                        }
                    }
                });
                this.sortArray(this.amounts, 'created_at', -1)
                this.sortArray(this.amounts, 'archive_flg', 1)
                if(this.event.notification){
                    this.sendMessage(action)
                }
                this.targetAmount = {}
            })
            .catch(err => {
                alert(err)
            })
        },
        archiveAmount(){
            this.archiveRelatedAction('archive')
        },
        deleteAmount(){
            window.axios.delete(`/amount/delete/${this.targetAmount.id}`)
            .then(() => {
                this.amounts = this.amounts.filter(amount => amount.id !== this.targetAmount.id);
                this.each = this.each.map(item => {
                    if(item.line_friend.line_id === this.targetAmount.line_friend.line_id){
                        return {
                            ...item,
                            sum: item.sum - this.targetAmount.amount
                        }
                    }else{
                        return item
                    }
                })
                if(this.event.notification){
                    this.sendMessage('削除')
                }
                this.targetAmount = {}
            })
            .catch(err => {
                alert(err)
            })
        },
        executeAction(type){
            if(type === '精算'){
                this.archiveAmount()
            }else if(type === '削除'){
                this.deleteAmount()
            }else if(type === '編集'){
                this.saveEditAmount()
            }else if(type === '未精算'){
                this.unarchiveAmount()
            }
        },
        getAmountsData(){
            window.axios.get(`/api/amount/lists/${this.event.id}`)
            .then(({data}) => {
                this.amounts = data.amount_lists;
                this.each = data.each;
            })
            .catch(err => {
                console.log(err)
            })
        },
        saveEditAmount(){

        },
        sendMessage(action){
            let messageText = "イベント: " + this.event.event_name + "\n" + this.targetAmount.amount + "円（" + this.targetAmount.note + "）\n" + "支払い者: " + this.targetAmount.line_friend.display_name + "\nを" + action + "しました。";
            window.liff.sendMessages([
                {
                    type: 'text',
                    text: messageText
                }
            ])
            .then(() => {
                //window.liff.closeWindow();
            })
            .catch((err) => {
                alert(err)
            })
        },
        selectTab(tab){
            this.activeTab = tab
        },
        showConfirmModal(type){
            this.modalType = type;
            this.modalVisibility = true;
            this.menuModalVisibility = false;
        },
        showMenuModal(amount){
            this.menuModalVisibility = true;
            this.targetAmount = amount;
        },
        sortArray(targetArray, key, order){
            targetArray.sort((a, b) => {
                if(a[key] < b[key]) return -1 * order;
                if(a[key] > b[key]) return 1 * order;
                return 0;
            })
        },
        unarchiveAmount(){
            this.archiveRelatedAction('unarchive')
        }
    },
    created(){
        window.liff.init({
            liffId: this.liff
        })
        .then((data) => {
            this.getAmountsData();
            this.checkAccess();
        })
    },
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin]
}
</script>