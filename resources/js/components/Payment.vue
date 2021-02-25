<template>
    <section class="section-inner">
        <article v-if="!isLoading" class="vertical-center-container">
            <template v-if="isAccepting">
                <div class="amount-modal-block">
                    <p class="amount-modal-block--title normal-txt">支払い者:</p>
                    <div class="amount-modal-block--content">
                        <profile-block :user="fromUser" icon-size="40"></profile-block>
                    </div>
                </div>
                <div class="amount-modal-block">
                    <p class="amount-modal-block--title normal-txt">支払われ先:</p>
                    <div class="amount-modal-block--content">
                        <profile-block :user="toUser" icon-size="40"></profile-block>
                    </div>
                </div>
                <div class="amount-modal-block">
                    <p class="amount-modal-block--title normal-txt">金額:</p>
                    <p class="amount-modal-block--content normal-txt">{{amount}}<span class="small-txt">円</span></p>
                </div>
                <p class="small-txt amount-modal-confirm">を<span class="normal-txt red-txt">承認</span>してもいいですか？</p>
                <p class="small-txt">上記の支払いを受け取っていない場合や、金額が間違っている場合はウィンドウを閉じて「拒否」ボタンを押してください。一度承認すると拒否することはできません。<br>承認すると、割り勘代の支払いがwatteに反映されます。</p>
                <form-button value="支払いを承認" type="accept" @send="approve"></form-button>
            </template>
            <template v-else>
                <div class="amount-modal-block">
                    <p class="amount-modal-block--title normal-txt">支払い者:</p>
                    <div class="amount-modal-block--content">
                        <profile-block :user="fromUser" icon-size="40"></profile-block>
                    </div>
                </div>
                <div class="amount-modal-block">
                    <p class="amount-modal-block--title normal-txt">支払われ先:</p>
                    <div class="amount-modal-block--content">
                        <profile-block :user="toUser" icon-size="40"></profile-block>
                    </div>
                </div>
                <div class="amount-modal-block">
                    <p class="amount-modal-block--title normal-txt">金額:</p>
                    <p class="amount-modal-block--content normal-txt">{{amount}}<span class="small-txt">円</span></p>
                </div>
                <p class="small-txt amount-modal-confirm">を<span class="normal-txt red-txt">拒否</span>してもいいですか？</p>
                <p class="small-txt">上記の支払いを受け取っている場合は、ウィンドウを閉じて「承認」ボタンを押してください。<br>支払いは拒否すると削除されます。金額変更の場合は拒否してから再度入力し直してください。</p>
                <form-button value="支払いを拒否" type="deny" @send="deny"></form-button>
            </template>
        </article>
        <loading v-if="isLoading"></loading>
    </section>
</template>

<script>
import ApiLoading from './modules/ApiLoading'
import FormButton from './modules/FormButton';
import Loading from './modules/Loading';
import ProfileBlock from './modules/ProfileBlock';
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'
import handleErrMinxin from '../mixins/handleErrMinxin'

export default {
    props: ['liff', 'deployUrl', 'transaction', 'actionType', 'participants'],
    components: {
        ApiLoading,
        FormButton,
        Loading,
        ProfileBlock
    },
    data(){
        return{
            event: {},
            isApiLoading: false
        }
    },
    computed: {
        isAccepting(){
            return this.actionType === 'accept' ? true : false;
        },
        fromUser(){
            return this.participants.find(participant => participant.line_id === this.transaction.from_user);
        },
        toUser(){
            return this.participants.find(participant => participant.line_id === this.transaction.to_user);
        },
        amount(){
            return String(this.transaction.amount).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
    },
    methods: {
        deny(){
            this.isApiLoading = true;
            window.axios.delete(`transaction/delete/${this.transaction.id}`)
            .then(() => {
                let message = this.fromUser.display_name + "さんからの" + this.amount + "円の割り勘代支払いを拒否しました。";
                this.sendMessage(message)
                this.isApiLoading = false;
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        },
        isSent(){
            return this.transaction.sent ? true : false;
        },
        isApproved(){
            return this.transaction.approved ? true : false;
        },
        hideLoading(){
            if(!this.isSent()){
                alert('支払いリクエストを支払い済みにしてください。');
                location.href = `https://liff.line.me/1655325455-B5Zjk37g/request/${this.transaction.id}?type=accept`;
            }
            if(!this.isApproved()){
                alert('承認済の支払いです。');
                window.liff.closeWindow();
            }
            this.isLoading = false;
        },
        approve(){
            this.isApiLoading = true;
            window.axios.put(`/approve/${this.transaction.id}`)
            .then(({data}) => {
                let message = this.fromUser.display_name + "さんからの" + this.amount + "円の割り勘代支払いを承認しました。";
                this.sendMessage(message)
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
                window.liff.closeWindow();
            })
            .catch((err) => {
                alert(err)
                window.liff.closeWindow();
                this.handleErr(err.response.status)
            })
        },
    },
    mounted(){
        this.event = this.transaction.event;
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