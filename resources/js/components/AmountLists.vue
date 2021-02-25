<template>
    <section class="section-inner">
        <article v-if="!isLoading">
            <notification
                v-for="(action, index) in doneAction"
                :key="index"
                :action="action.text" 
                :visibility="action.notificationVisibility"
                @close="action.notificationVisibility = false"
            ></notification>
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
                    :user="userInfo"
                    @show="showEachMenuModal"
                ></amount-each-member>
                <article>
                    <p class="normal-txt">割り勘代の支払い履歴</p>
                    <ul class="transaction-container">
                        <transaction-item
                            v-for="transaction in transactions"
                            :key="transaction.id"
                            :transaction="transaction"
                            :participants="participants"
                        ></transaction-item>
                    </ul>
                </article>
            </section>
        </article>
        <loading v-if="isLoading"></loading>
        <amount-item-option-window 
            :visibility="menuModalVisibility"
            :target="targetAmount"
            @close="menuModalVisibility = false"
            @archive="showConfirmModal"
            @delete="showConfirmModal"
            @edit="showEditForm"
            @unarchive="showConfirmModal"
        ></amount-item-option-window>
        <amount-each-option-window
            :visibility="eachMenuModalVisibility"
            @close="eachMenuModalVisibility = false"
            @request="showPaymentModal"
            @settle="showPaymentModal"
        ></amount-each-option-window>
        <amount-confirm-modal
            @close="modalVisibility = false"
            @execute="executeAction"
            :modal-type="modalType.amountItem"
            :target="targetAmount"
            :visibility="modalVisibility"
        ></amount-confirm-modal>
        <amount-each-payment-modal
            @close="eachModalVisibility = false"
            @execute="executeAction"
            :modal-type="modalType.each"
            :target="targetUserInfo"
            :participants="participants"
            :visibility="eachModalVisibility"
        ></amount-each-payment-modal>
        <amount-edit-modal
            @change="changeTargetAmount"
            @close="editModalVisibility = false"
            @execute="saveEditAmount"
            :modal-type="modalType.amountItem"
            :target="targetAmount"
            :visibility="editModalVisibility"
        ></amount-edit-modal>
        <api-loading v-if="isApiLoading"></api-loading>
    </section>
</template>

<script>
import AmountEachMember from './modules/AmountEachMember'
import AmountItem from './modules/AmountItem'
import AmountItemOptionWindow from './modules/AmountItemOptionWindow'
import AmountEachOptionWindow from './modules/AmountEachOptionWindow'
import AmountEditModal from './modules/AmountEditModal'
import AmountConfirmModal from './modules/AmountConfirmModal'
import AmountEachPaymentModal from './modules/AmountEachPaymentModal'
import AmountTab from './modules/AmountTab'
import ApiLoading from './modules/ApiLoading'
import Loading from './modules/Loading'
import TransactionItem from './modules/TransactionItem'
import Notification from './modules/Notification'
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'
import handleErrMinxin from '../mixins/handleErrMinxin'

export default {
    components: {
        AmountEachMember,
        AmountItem,
        AmountItemOptionWindow,
        AmountEachOptionWindow,
        AmountEditModal,
        AmountConfirmModal,
        AmountEachPaymentModal,
        AmountTab,
        ApiLoading,
        Loading,
        TransactionItem,
        Notification
    },
    props: ['event', 'participants'],
    data: function(){
        return{
            amounts: undefined,
            activeTab: 0,
            doneAction: [],
            each: undefined,
            eachMenuModalVisibility: false,
            eachModalVisibility: false,
            editModalVisibility: false,
            isApiLoading: true,
            isLoading: true,
            menuModalVisibility: false,
            modalVisibility: false,
            modalType: {
                amountItem: '',
                each: ''
            },
            targetAmount: {},
            targetUserInfo: {},
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
            transactions: undefined
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
            this.isApiLoading = true;
            let url
            let archive_flg_state
            let formula
            let action
            if(type === 'archive'){
                url = `/amount/archive/${this.targetAmount.id}`
                archive_flg_state = 1
                formula = 1
                action = '精算済に'
            }else if(type === 'unarchive'){
                url = `/amount/unarchive/${this.targetAmount.id}`
                archive_flg_state = 0
                formula = -1
                action = '未精算に'
            }
            
            window.axios.put(url)
            .then(() => {
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
                    let messageText = "イベント: " + this.event.event_name + "\n" + this.targetAmount.amount + "円（" + this.targetAmount.note + "）\n" + "支払い者: " + this.targetAmount.line_friend.display_name + "\nを" + action + "しました。";
                    this.sendMessage(messageText)
                }
                this.targetAmount = {}
                this.modalVisibility = false;
                this.isApiLoading = false;
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        },
        archiveAmount(){
            this.archiveRelatedAction('archive')
        },
        changeTargetAmount(newValue){
            this.targetAmount = newValue
        },
        deleteAmount(){
            this.isApiLoading = true;
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
                    let messageText = "イベント: " + this.event.event_name + "\n" + this.targetAmount.amount + "円（" + this.targetAmount.note + "）\n" + "支払い者: " + this.targetAmount.line_friend.display_name + "\nを" + '削除' + "しました。";
                    this.sendMessage(messageText)
                }
                this.targetAmount = {}
                this.modalVisibility = false;
                this.isApiLoading = false;
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        },
        executeAction(data){
            if(data.type === '精算'){
                this.archiveAmount()
            }else if(data.type === '削除'){
                this.deleteAmount()
            }else if(data.type === '未精算'){
                this.unarchiveAmount()
            }else if(data.type == 'request'){
                this.sendPaymentRequest(data)
            }else if(data.type == 'settle'){
                this.settlePayment(data)
            }
        },
        getAmountsData(){
            window.axios.get(`/api/amount/lists/${this.event.id}`)
            .then(({data}) => {
                this.amounts = data.amount_lists;
                this.each = data.each;
                this.isApiLoading = false;
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        },
        getTransactions(){
            window.axios.get(`/api/transactions/${this.event.id}`)
            .then(({data}) => {
                this.transactions = data;
            })
        },
        hideLoading(){
            this.isLoading = false
            this.getAmountsData();
            this.getTransactions();
        },
        saveEditAmount(){
            this.isApiLoading = true;
            window.axios.put(`/amount/edit/${this.targetAmount.id}`, {
                amount: this.targetAmount.amount,
                note: this.targetAmount.note
            })
            .then(() => {
                this.amounts = this.amounts.map(amount => {
                    if(amount.id === this.targetAmount.id){
                        return this.targetAmount
                    }else{
                        return amount
                    }
                })
                if(this.event.notification){
                    let messageText = "イベント: " + this.event.event_name + "\n" + this.targetAmount.amount + "円（" + this.targetAmount.note + "）\n" + "支払い者: " + this.targetAmount.line_friend.display_name + "\nを" + '編集' + "しました。";
                    this.sendMessage(messageText)
                }
                this.targetAmount = {}
                this.editModalVisibility = false;
                this.isApiLoading = false;
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        },
        sendMessage(message){
            window.liff.sendMessages([
                {
                    type: 'text',
                    text: message
                }
            ])
            .then(() => {
                
            })
            .catch((err) => {
                this.handleErr(err.response.status)
            })
        },
        sendButtonMessage(altText, template){
            window.liff.sendMessages([
                {
                    type: 'template',
                    altText: altText,
                    template: template
                }
            ])
            .then(() => {
            })
            .catch((err) => {
                this.handleErr(err.response.status)
            })
        },
        selectTab(tab){
            this.activeTab = tab
        },
        sendPaymentRequest(sentData){
            this.isApiLoading = true;
            window.axios.post(`/transaction/add/${this.event.id}`, {
                from_user: sentData.fromUser.line_id,
                to_user: sentData.toUser.line_id,
                amount: sentData.amount,
                type: sentData.type
            })
            .then(({data}) => {
                console.log(data)
                let altText;
                let template;
                altText = '支払いリクエスト';
                template = {
                    type: 'confirm',
                    text: sentData.toUser.display_name + "さんが" + sentData.fromUser.display_name + "さんに" + data.amount + "円の支払いリクエストを送信しました。",
                    actions: [
                        {
                            type: 'uri',
                            label: '支払済',
                            uri: `https://liff.line.me/1655325455-B5Zjk37g/request/${data.id}?type=accept`
                        },
                        {
                            type: 'uri',
                            label: '拒否',
                            uri: `https://liff.line.me/1655325455-B5Zjk37g/request/${data.id}?type=deny`
                        }
                    ]
                };
                this.sendButtonMessage(altText, template)
                this.doneAction.push({text: 'リクエストを送信', notificationVisibility: true});
                setTimeout(() => {
                    this.$set(this.doneAction, doneActionNumber, {notificationVisibility: false})
                }, 2000)
                this.isApiLoading = false;
            })
            .catch((err) => {
                this.handleErr(err.response.status)
            })
            this.eachModalVisibility = false;
        },
        settlePayment(sentData){
            this.isApiLoading = true;
            window.axios.post(`/transaction/add/${this.event.id}`, {
                from_user: sentData.fromUser.line_id,
                to_user: sentData.toUser.line_id,
                amount: sentData.amount,
                type: sentData.type
            })
            .then(({data}) => {
                let altText;
                let template;

                altText = '支払い';
                template = {
                    type: 'confirm',
                    text: sentData.fromUser.display_name + 'さんが' + sentData.toUser.display_name + 'さんに' + data.amount + "円を支払いました。",
                    actions: [
                        {
                            type: 'uri',
                            label: '承認',
                            uri: `https://liff.line.me/1655325455-B5Zjk37g/payment/${data.id}?type=accept`
                        },
                        {
                            type: 'uri',
                            label: '拒否',
                            uri: `https://liff.line.me/1655325455-B5Zjk37g/payment/${data.id}?type=deny`
                        }
                    ]
                };
 
                this.sendButtonMessage(altText, template)

                this.doneAction.push({text: '支払いを送信', notificationVisibility: true})
                let doneActionNumber = this.doneAction.length - 1;
                setTimeout(() => {
                    this.$set(this.doneAction, doneActionNumber, {notificationVisibility: false})
                }, 2000)
                this.isApiLoading = false;
            })
            .catch((err) => {
                this.handleErr(err.response.status)
            })
            this.eachModalVisibility = false;
        },
        showConfirmModal(type){
            this.modalType.amountItem = type;
            this.modalVisibility = true;
            this.menuModalVisibility = false;
        },
        showEditForm(type){
            this.modalType.amountItem = type;
            this.editModalVisibility = true;
            this.menuModalVisibility = false;
        },
        showMenuModal(amount){
            this.menuModalVisibility = true;
            this.targetAmount = amount;
        },
        showEachMenuModal(each){
            this.eachMenuModalVisibility = true;
            this.targetUserInfo = each
        },
        showPaymentModal(type){
            this.modalType.each = type;
            this.eachModalVisibility = true;
            this.eachMenuModalVisibility = false;
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
        .then(() => {
            this.checkAccess();
        })
    },
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin, handleErrMinxin]
}
</script>