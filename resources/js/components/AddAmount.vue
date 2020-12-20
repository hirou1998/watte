<template>
    <section>
        <amount-payer-form v-model="userInfo" :participants="participants"></amount-payer-form>
        <amount-number-form v-model="amount"></amount-number-form>
        <amount-note-form v-model="note"></amount-note-form>
        <form-button value="追加" type="accept" @send="add"></form-button>
    </section>
</template>

<script>
import AmountPayerForm from './modules/AmountPayerForm'
import AmountNumberForm from './modules/AmountNumberForm'
import AmountNoteForm from './modules/AmountNoteForm'
import FormButton from './modules/FormButton'
import getUserInfoMixin from '../mixins/getUserInfoMixin'

export default {
    components: {
        AmountPayerForm,
        AmountNumberForm,
        AmountNoteForm,
        FormButton
    },
    props: ['event', 'liff', 'participants'],
    data: function(){
        return{
            amount: 0,
            note: ''
        }
    },
    methods: {
        add(){
            window.axios.post(`/amounts/add/${this.event.id}`, {
                userId: this.userInfo.userId,
                amount: this.amount,
                note: this.note
            })
            .then(({data}) => {
                window.liff.sendMessages([
                    {
                        type: 'text',
                        text: 'イベント: ' + this.event.event_name +  data.amount + '円' + '(' + data.note + ')を追加しました。'
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
        }
    },
    created(){
        window.liff.init({
            liffId: this.liff
        })
        .then(() => {
            this.getUserProfile();
        })
    },
    mixins: [getUserInfoMixin]
}
</script>