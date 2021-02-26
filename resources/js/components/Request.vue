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
                <p class="small-txt amount-modal-confirm">を<span class="normal-txt red-txt">支払い済み</span>にします</p>
                <p class="small-txt">支払いを拒否、または金額を変更する場合は、ウィンドウを閉じて「拒否」ボタンを押してください。<br>支払い済みにした後、支払いリクエスト者が承認すると、割り勘代の支払いがwatteに反映されます。</p>
                <form-button value="支払い済みにする" type="accept" @send="send" v-if="userInfo.userId === fromUser.line_id"></form-button>
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
                <p class="small-txt amount-modal-confirm">を<span class="normal-txt red-txt">支払い拒否</span>します</p>
                <p class="small-txt">支払い済みにする場合は、ウィンドウを閉じて「支払済」ボタンを押してください。<br>支払いを拒否するとリクエストは削除されます。金額変更の場合は拒否してから再度入力し直してください。</p>
                <form-button value="支払いを拒否する" type="deny" @send="deny" v-if="userInfo.userId === fromUser.line_id"></form-button>
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
import allowAccessIfWithGroupIdMixin from '../mixins/allowAccessIfWithGroupIdMixin'
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
            window.axios.delete(`/transaction/delete/${this.transaction.id}`)
            .then(() => {
                let message = this.toUser.display_name + "さんからの" + this.amount + "円の割り勘代支払いリクエストを拒否しました。";
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
            if(!this.transaction.id){
                alert('削除済の支払いリクエストです。');
                window.liff.closeWindow();
            }
            if(this.isSent()){
                alert('支払い済です。');
                window.liff.closeWindow();
            }
            if(this.isApproved()){
                alert('承認済の支払いです。');
                window.liff.closeWindow();
            }
            this.isLoading = false;
        },
        send(){
            this.isApiLoading = true;
            window.axios.put(`/sent/${this.transaction.id}`)
            .then(() => {
                let altText;
                let template;
                altText = '支払い';
                template = {
                    type: 'confirm',
                    text: this.fromUser.display_name + 'さんが' + this.toUser.display_name + 'さんに' + this.amount + "円を支払いました。",
                    actions: [
                        {
                            type: 'uri',
                            label: '承認',
                            uri: `https://liff.line.me/1655325455-B5Zjk37g/payment/${this.transaction.id}?type=accept`
                        },
                        {
                            type: 'uri',
                            label: '拒否',
                            uri: `https://liff.line.me/1655325455-B5Zjk37g/payment/${this.transaction.id}?type=deny`
                        }
                    ]
                };
                this.sendButtonMessage(altText, template)
                this.isApiLoading = false;
            })
            .catch(err => {
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
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin, allowAccessIfWithGroupIdMixin, handleErrMinxin]
}
</script>