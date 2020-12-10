<template>
    <div class="start-form-container">
        <template v-if="isStartView">
            <p class="normal-txt text-center">{{userInfo.displayName}}さん<br>watteのご利用ありがとうございます！</p>
            <form @submit.prevent class="w-100">
                <div class="form-group mt-5">
                    <p class="normal-txt">新規イベント名を入力してください。</p>
                    <input type="text" class="form-control" v-model="eventName">
                </div>
                <template v-if="eventName !== ''">
                    <form-button value="送信" type="accept" @send="send"></form-button>
                </template>
            </form>
        </template>
        <template v-else>
            <p class="normal-txt text-center">参加確認画面にリダイレクトします。</p>
        </template>
    </div>
</template>

<script>
import FormButton from './modules/FormButton';

export default {
    components: {
        FormButton
    },
    props: ['liff'],
    data: function(){
        return{
            userInfo: {},
            groupId: '',
            eventName: '',
            registerd: {},
            isStartView: true
        }
    },
    methods: {
        askJoin(text, id){
            let url = 'https://liff.line.me/1655325455-B5Zjk37g/confirm?type=confirm&id=' + id;
            window.liff.sendMessages([
                {
                    type: 'template',
                    altText: text,
                    template: {
                        type: 'confirm',
                        text: text,
                        actions: [
                            {
                                type: 'uri',
                                label: '参加する',
                                uri: url + '&join=yes'
                            },
                            {
                                type: 'uri',
                                label: '参加しない',
                                uri: url + '&join=no'
                            }
                        ]
                    }
                }
            ])
        },
        getUserProfile(){
            window.liff.getProfile()
            .then(profile => {
                this.userInfo = profile;
            })
            // .catch(e => {
            //     alert('ユーザー情報の取得に失敗しました');
            // })
        },
        getGroupId(){
            let context = window.liff.getContext()
            console.log(context)
            if(context.type === 'group'){
                this.groupId = context.groupId
            }else{
                alert('スマートフォンで起動してください')
            }
        },
        send(){
            window.axios.post('/create/new/', {
                event_name: this.eventName,
                group_id: this.groupId,
                creator_id: this.userInfo.userId
            })
            .then(({data}) => {
                let text = "イベント: " + data.event_name + "を作成しました。\n参加しますか？";
                let eventId = data.id;
                this.askJoin(text, eventId);
                window.liff.closeWindow();
            })
            .catch(e => {
                this.sendErr();
                window.liff.closeWindow();
            })
        },
        sendErr(){
            window.liff.sendMessages([
                {
                    type: 'text',
                    text: 'エラーが発生しました'
                }
            ])
        }
    },
    mounted(){
        window.liff.init({
            liffId: this.liff
        })
        .then((data) => {
            let param = location.search;
            if(param){
                this.isStartView = false;
            }
            this.getUserProfile();
            this.getGroupId();
        })
    }
}
</script>