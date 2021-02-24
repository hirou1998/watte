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
                <form-button value="支払いを承認" type="accept" @send="send"></form-button>
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
        isSent(){
            return this.transaction.sent ? true : false;
        },
        hideLoading(){
            this.isLoading = false;
            if(!this.isSent()){
                alert('支払いリクエストを支払い済みにしてください。');
                location.href = `https://liff.line.me/1655325455-B5Zjk37g/request/${this.transaction.id}?type=accept`;
            }
        },
        send(){
            this.isApiLoading = true;
            window.axios.put(`/approve/${this.transaction.id}`)
            .then(({data}) => {
                let message = this.fromUser.display_name + "さんからの" + this.amount + "円の割り勘代支払いを承認しました。";
                this.sendButtonMessage(altText, template)
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
    },
    mounted(){
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