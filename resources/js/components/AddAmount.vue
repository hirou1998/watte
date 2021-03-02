<template>
    <section class="section-inner">
        <article v-if="!isLoading">
            <!-- <div class="image-form-block amount-event-head">
                <img :src="previewUrl" alt="" class="image-form-preview amount-event-img">
                <h1 class="amount-show-title txt-big amount-event-title">{{event.event_name}}</h1>
            </div> -->
            <amount-user-form 
                v-model="userInfo"
                :participants="participants"
                text="支払い者"
            ></amount-user-form>
            <amount-number-form 
                v-model="amount"
            ></amount-number-form>
            <amount-note-form 
                v-model="note"
            ></amount-note-form>
            <toggle-block 
                v-model="isPrivate" 
                text="個人間の貸し借りを記録する"
                v-if="participants.length > 2"
                @show="privateDealsInfoVisibility = true"
            ></toggle-block>
            <section class="private-amount-container" v-if="isPrivate">
                <template v-for="(user, index) in partner">
                    <amount-user-form
                        v-model="user.user"
                        :participants="partnerLists"
                        :text="'支払い相手' + (index + 1)"
                        place-holder="誰に払いましたか？"
                        :key="index"
                    ></amount-user-form>
                </template>
                <button class="btn btn-success amount-user-add-button" @click="addPertner" v-if="enableToAddPartner">+支払い相手を追加する</button>
            </section>
            <form-button v-if="isFilled" value="追加" type="accept" @send="add"></form-button>
        </article>
        <loading v-if="isLoading"></loading>
        <api-loading v-if="isApiLoading"></api-loading>
        <private-deal-info-modal 
            :visibility="privateDealsInfoVisibility"
            @close="privateDealsInfoVisibility = false"
        ></private-deal-info-modal>
    </section>
</template>

<script>
import AmountUserForm from './modules/AmountUserForm'
import AmountNumberForm from './modules/AmountNumberForm'
import AmountNoteForm from './modules/AmountNoteForm'
import ApiLoading from './modules/ApiLoading'
import Loading from './modules/Loading'
import FormButton from './modules/FormButton'
import PrivateDealInfoModal from './modules/PrivateDealInfoModal'
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'
import ToggleBlock from './modules/ToggleBlock.vue'
import handleErrMinxin from '../mixins/handleErrMinxin'
import formValidatorMixin from '../mixins/formValidatorMixin'

export default {
    components: {
        AmountUserForm,
        AmountNumberForm,
        AmountNoteForm,
        ApiLoading,
        Loading,
        FormButton,
        ToggleBlock,
        PrivateDealInfoModal
    },
    props: ['event', 'liff', 'participants'],
    data: function(){
        return{
            amount: '',
            note: '',
            isLoading: true,
            isApiLoading: false,
            isPrivate: false,
            partner: [
                {
                    user: {
                        userId: '誰に払いましたか？'
                    }
                }
            ],
            privateDealsInfoVisibility: false
        }
    },
    computed: {
        isFilled(){
            if(this.ValidateNumber(this.amount) && this.isTextFilled(this.note)){
                return true
            }else{
                return false
            }
        },
        partnerLists(){
            let partnerList = this.participants.filter(participant => participant.line_id !== this.userInfo.userId);
            return partnerList;
        },
        enableToAddPartner(){
            if(this.partner.length < this.participants.length - 2){
                return true
            }else{
                return false
            }
        },
        previewUrl(){
            if(this.event.file_path){
                return '/' + this.event.file_path;
            }else{
                return '/images/logo.png';
            }
        }
    },
    methods: {
        add(){
            this.isApiLoading = true;
            let formItem;
            let isNumber = this.ValidateNumber(this.amount)
            if(!this.isFilled){
                alert('未入力の項目があります。')
                this.isApiLoading = false;
                return
            }
            if(!isNumber){
                alert('金額には数字以外を入力しないでください。')
                this.isApiLoading = false;
                return
            }

            if(this.isPrivate){
                if(this.arePartnersDuplicated()){
                    alert('支払い相手が重複しています')
                    this.isApiLoading = false;
                    return
                }
                formItem = {
                    userId: this.userInfo.userId,
                    amount: this.amount,
                    note: this.note,
                    private: true,
                    partner: this.partner
                }
            }else{
                formItem = {
                    userId: this.userInfo.userId,
                    amount: this.amount,
                    note: this.note,
                    private: false
                }
            }

            window.axios.post(`/amounts/add/${this.event.id}`, formItem)
            .then(({data}) => {
                if(this.event.notification){
                    this.sendMessage(data)
                }else{
                    window.liff.closeWindow();
                }
                this.isApiLoading = false;
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        },
        addPertner(){
            if(this.enableToAddPartner){
                this.partner.push({
                    user: {
                        userId: '誰に払いましたか？'
                    }
                })
            }else{
                alert('これ以上相手を増やせません')
            }
        },
        arePartnersDuplicated(){
            let passedUsers = [];
            let isDuplicated = false;
            this.partner.forEach(user => {
                if(passedUsers.indexOf(user.user.userId) === -1){
                    passedUsers.push(user.user.userId);
                }else{
                    isDuplicated = true
                }
            })
            return isDuplicated;
        },
        hideLoading(){
            this.isLoading = false;
            this.isParticipated();
        },
        isParticipated(){
            let friend = this.participants.find(p => p.line_id === this.userInfo.userId);
            if(!friend){
                alert('イベント: 「' + this.event.event_name + '」に参加してから登録してください。');
                location.href = `https://liff.line.me/1655325455-B5Zjk37g/confirm?type=confirm&id=${this.event.id}&join=yes&group=${this.groupId}`;
            }
        },
        sendMessage(data){
            let returnText;
            let payer = this.participants.find(participant => participant.line_id == data.friend_id);
            let payerName = payer.display_name;
            if(data.private){
                returnText = "【個人】イベント: " + this.event.event_name + "\n" + data.amount + "円（" + data.note + "）\n" + "支払い者: " + payerName + "\nを追加しました。";
            }else{
                returnText = "【全体】イベント: " + this.event.event_name + "\n" + data.amount + "円（" + data.note + "）\n" + "支払い者: " + payerName + "\nを追加しました。";
            }
            window.liff.sendMessages([
                {
                    type: 'text',
                    text: returnText
                }
            ])
            .then(() => {
                window.liff.closeWindow();
            })
            .catch((err) => {
                this.handleErr(err.response.status)
            })
        },
    },
    created(){
        window.liff.init({
            liffId: this.liff
        })
        .then(() => {
            this.checkAccess();
        })
    },
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin, formValidatorMixin, handleErrMinxin]
}
</script>