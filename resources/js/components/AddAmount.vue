<template>
    <section class="section-inner">
        <article v-if="!isLoading">
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
            <form-button value="追加" type="accept" @send="add"></form-button>
        </article>
        <loading v-if="isLoading"></loading>
    </section>
</template>

<script>
import AmountUserForm from './modules/AmountUserForm'
import AmountNumberForm from './modules/AmountNumberForm'
import AmountNoteForm from './modules/AmountNoteForm'
import Loading from './modules/Loading'
import FormButton from './modules/FormButton'
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'
import ToggleBlock from './modules/ToggleBlock.vue'

export default {
    components: {
        AmountUserForm,
        AmountNumberForm,
        AmountNoteForm,
        Loading,
        FormButton,
        ToggleBlock
    },
    props: ['event', 'liff', 'participants'],
    data: function(){
        return{
            amount: 0,
            note: '',
            isLoading: true,
            isPrivate: false,
            partner: [
                {
                    user: {
                        userId: '誰に払いましたか？'
                    }
                }
            ]
        }
    },
    computed: {
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
        }
    },
    methods: {
        add(){
            this.isParticipated();
            let formItem;
            if(this.isPrivate){
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
                    console.log(err);
                })
            })
            .catch(err => {
                alert(err)
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
    },
    async created(){
        window.liff.init({
            liffId: this.liff
        })
        .then(() => {
            this.checkAccess();
        })
    },
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin]
}
</script>