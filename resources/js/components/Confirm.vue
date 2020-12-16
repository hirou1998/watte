<template>
    <div>
        <template v-if="isJoining">
            <p class="normal-txt text-center">{{userInfo.displayName}}さん<br>イベント: {{ event.event_name }} に参加します。<br>よろしいですか？</p>
            <p class="small-txt text-center">参加しない場合は一度ウィンドウを閉じて「参加しない」ボタンを押してください。</p>
            <form-button value="確定" type="accept" @send="send"></form-button>
        </template>
        <template v-else>
            <p class="normal-txt text-center">{{userInfo.displayName}}さん<br>イベント: {{ event.event_name }} に参加しません。<br>よろしいですか？</p>
            <p class="small-txt text-center">参加する場合は一度ウィンドウを閉じて「参加する」ボタンを押してください。</p>
            <form-button value="確定" type="deny" @send="send"></form-button>
        </template>
    </div>
</template>

<script>
import FormButton from './modules/FormButton';

export default {
  components: { FormButton },
    props: ['event', 'join', 'liff'],
    data: function(){
        return{
            userInfo: {}
        }
    },
    computed: {
        isJoining(){
            return this.join === 'yes' ? true : false;
        }
    },
    methods: {
        getUserProfile(){
            window.liff.getProfile()
            .then(profile => {
                this.userInfo = profile;
            })
            .catch(e => {
                alert('ユーザー情報の取得に失敗しました');
            })
        },
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
                    console.log(err);
                })
            })
            .catch(err => {
                if(err.response){
                    let errText = 'エラーが発生しました。[' + err.response.message + ']';
                }else{
                    let errText = '予期せぬエラーが発生しました。';
                }
                alert(errText);
            })
        }
    },
    mounted(){
        window.liff.init({
            liffId: this.liff
        })
        .then(() => {
            this.getUserProfile();
        })
    }
}
</script>