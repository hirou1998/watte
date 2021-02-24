<template>
    <section class="section-inner">
        <article v-if="!isLoading" class="vertical-center-container">
            <template v-if="isJoining">
                <p class="normal-txt text-center">{{userInfo.displayName}}さん<br>イベント: {{ event.event_name }} に参加します。<br>よろしいですか？</p>
                <p class="small-txt">参加しない場合は一度ウィンドウを閉じて「参加しない」ボタンを押してください。</p>
                <form-button value="参加確定" type="accept" @send="send"></form-button>
            </template>
            <template v-else>
                <p class="normal-txt text-center">{{userInfo.displayName}}さん<br>イベント: {{ event.event_name }} に参加しません。<br>よろしいですか？</p>
                <p class="small-txt">参加する場合は一度ウィンドウを閉じて「参加する」ボタンを押してください。</p>
                <form-button value="不参加確定" type="deny" @send="send"></form-button>
            </template>
        </article>
        <loading v-if="isLoading"></loading>
    </section>
</template>

<script>
import FormButton from './modules/FormButton';
import Loading from './modules/Loading';
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'
import allowAccessIfWithGroupIdMixin from '../mixins/allowAccessIfWithGroupIdMixin'
import handleErrMinxin from '../mixins/handleErrMinxin'

export default {
  components: { FormButton, Loading },
    props: ['event', 'join', 'liff'],
    computed: {
        isJoining(){
            return this.join === 'yes' ? true : false;
        }
    },
    methods: {
        send(){
            window.axios.post(`/confirm/register/${this.event.id}`, {
                userId: this.userInfo.userId,
                join: this.join
            })
            .then(({data}) => {
                window.liff.sendMessages([
                    {
                        type: 'text',
                        text: data.message
                    }
                ])
                .then(() => {
                    window.liff.closeWindow();
                })
                .catch((err) => {
                    this.handleErr(err.response.status)
                })
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        }
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