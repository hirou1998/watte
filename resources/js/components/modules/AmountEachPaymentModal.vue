<template>
    <modal-base @close="close" :visibility="visibility">
        <template v-slot:content>
            <div class="each-payment-modal-users each-payment-modal-block" :data-reverse="modalType === 'settle' ? 'true' : 'false'">
                <div>
                    <amount-user-form 
                        v-model="partner" 
                        :participants="availablePartner"
                        :text="inputTitle"
                    ></amount-user-form>
                </div>
                <p class="big-txt">↓</p>
                <div class="each-payment-modal-owner">
                    <p class="small-txt mr-5">{{modalType === 'request' ? '支払われ先' : '支払い人'}}</p>
                    <profile-block :user="target.line_friend" iconSize="30"></profile-block>
                </div>
            </div>
            <div class="each-payment-modal-amount each-payment-modal-block">
                <amount-number-form
                    v-model="amountValue"
                ></amount-number-form>
            </div>
            <p class="note-txt amount-modal-note" v-if="modalType == 'request'">※上記の金額の支払いをリクエストします。</p>
            <p class="note-txt amount-modal-note" v-if="modalType == 'settle'">※上記の金額の支払いを記録します。</p>
        </template>
        <template v-slot:button>
            <form-button :value="buttonValue" type="accept" @send="execute"></form-button>
            <form-button value="取消" type="cancel" @send="cancel"></form-button>
        </template>
    </modal-base>
</template>

<script>
import AmountUserForm from './AmountUserForm'
import AmountNumberForm from './AmountNumberForm'
import FormButton from './FormButton'
import ModalBase from './ModalBase'
import FormatDateTimeMixin from '../../mixins/formatDateTimeMixin'
import handleErrMinxin from '../../mixins/handleErrMinxin'
import formValidatorMixin from '../../mixins/formValidatorMixin'

export default {
    components: {
        AmountUserForm,
        AmountNumberForm,
        FormButton,
        ModalBase
    },
    props: ['modalType', 'target', 'participants', 'visibility'],
    data(){
        return{
            partner: {},
            paymentAmount: ''
        }
    },
    computed: {
        inputTitle(){
            if(this.modalType === 'request'){
                return '誰に割り勘代の支払いをリクエストしますか？';
            }else if(this.modalType === 'settle'){
                return '誰に割り勘代を支払いますか？';
            }
        },
        buttonValue(){
            if(this.modalType === 'request'){
                return 'リクエスト';
            }else if(this.modalType === 'settle'){
                return '送信';
            }
        },
        availablePartner(){
            return this.participants.filter(participant => participant.line_id !== this.target.line_friend.line_id);
        },
        amountValue: {
            get(){
                this.paymentAmount = Math.abs(this.target.gap);
                return Math.abs(this.target.gap);
            },
            set(value){
                this.paymentAmount = value;
            }
        }
    },
    methods: {
        cancel(){
            this.close();
        },
        close(){
            this.$emit('close');
        },
        execute(){
            if(!this.ValidateNumber(this.paymentAmount)){
                alert('金額は数字で入力してください');
                return
            }else if(!Object.keys(this.partner).length){
                alert('支払い相手、もしくはリクエスト相手を選択してください');
                return
            }else{
                this.$emit('execute', {
                    fromUser: this.target.line_friend,
                    toUser: this.participants.find(participant => participant.line_id === this.partner.userId),
                    amount: this.paymentAmount,
                    type: this.modalType
                });
            }
        }
    },
    mixins: [FormatDateTimeMixin, formValidatorMixin, handleErrMinxin]
}
</script>