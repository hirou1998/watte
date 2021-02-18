<template>
    <modal-base @close="close" :visibility="visibility">
        <template v-slot:content>
            <div class="amount-modal-block">
                <p class="amount-modal-block--title normal-txt">支払い者:</p>
                <p class="amount-modal-block--content normal-txt">{{target.line_friend.display_name}}</p>
            </div>
            <amount-number-form 
                v-model="amount"
            ></amount-number-form>
            <amount-note-form 
                v-model="note"
            ></amount-note-form>
            <p class="note-txt amount-modal-note">※支払い者の変更及び個人間の貸し借りへの変更はできません。その場合は支払いを削除して追加し直してください。</p>
        </template>
        <template v-slot:button>
            <form-button value="編集を保存" type="accept" @send="execute"></form-button>
            <form-button value="取消" type="cancel" @send="cancel"></form-button>
        </template>
    </modal-base>
</template>

<script>
import AmountNumberForm from './AmountNumberForm'
import AmountNoteForm from './AmountNoteForm'
import FormButton from './FormButton'
import ModalBase from './ModalBase'
import FormatDateTimeMixin from '../../mixins/formatDateTimeMixin'

export default {
    components: {
        AmountNumberForm,
        AmountNoteForm,
        FormButton,
        ModalBase
    },
    props: ['modalType', 'target', 'visibility'],
    computed: {
        amount: {
            get(){
                return this.target.amount
            },
            set(amount){
                this.updateValue({amount})
            }
        },
        note: {
            get(){
                return this.target.note
            },
            set(note){
                this.updateValue({note})
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
            this.$emit('execute', this.modalType);
        },
        updateValue(value){
            this.$emit('change', {...this.target, ...value})
        }
    },
    mixins: [FormatDateTimeMixin]
}
</script>