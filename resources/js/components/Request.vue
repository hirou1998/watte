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
                <p class="small-txt amount-modal-confirm">を<span class="normal-txt red-txt">支払い済み</span>にしてもいいですか？</p>
                <p class="small-txt">支払いを拒否、または金額を変更する場合は、ウィンドウを閉じて「支払いを拒否」ボタンを押してください。<br>支払い済みにした後、支払いリクエスト者が承認すると、割り勘代の支払いがwatteに反映されます。</p>
                <form-button value="支払い済みにする" type="accept" @send="send"></form-button>
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
        hideLoading(){
            this.isLoading = false;
        },
        send(){
            this.isApiLoading = true;
            window.axios.put(`/sent/${this.transaction.id}`)
            .then(({data}) => {
                let altText;
                let template;
                altText = '割り勘代支払いの承認';
                template = {
                    type: 'confirm',
                    text: sentData.fromUser.display_name + "さんが" + sentData.toUser.display_name + "さんに\n" + data.amount + "円\nを支払いました。",
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
            })
            .catch((err) => {
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