<template>
    <div class="start-form-container">
        <loading v-if="isLoading"></loading>
        <template v-if="isStartView">
            <p class="normal-txt text-center">{{userInfo.displayName}}さん<br>watteのご利用ありがとうございます！</p>
            <form @submit.prevent class="w-100">
                <div class="form-group mt-5">
                    <p class="normal-txt">何を割り勘しますか？</p>
                    <input type="text" class="form-control" v-model="eventName" placeholder="例：〇〇旅行、〇〇飲み会">
                </div>
                <template v-if="eventName !== ''">
                    <form-button value="送信" type="accept" @send="send"></form-button>
                </template>
            </form>
        </template>
        <template v-else>
            <p class="normal-txt text-center" v-if="isConfirmView">参加確認画面にリダイレクトします。</p>
            <p class="normal-txt text-center" v-if="isAmountAddView">割り勘追加画面にリダイレクトします。</p>
            <p class="normal-txt text-center" v-if="isAmountListView">一覧画面にリダイレクトします。</p>
        </template>
    </div>
</template>

<script>
import FormButton from './modules/FormButton';
import Loading from './modules/Loading';

export default {
    components: {
        FormButton,
        Loading
    },
    props: ['liff'],
    data: function(){
        return{
            userInfo: {},
            groupId: '',
            eventName: '',
            registerd: {},
            isLoading: true,
            isStartView: false,
            isConfirmView: false,
            isAmountAddView: false,
            isAmountListView: false
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
                let text = "イベント: 「" + data.event_name + "」 を作成しました。\n参加しますか？";
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
            this.isLoading = false;

            let param = location.search;
            //alert(param)
            if(param){
                this.isStartView = false;
                switch(true){
                    case param.indexOf('confirm') !== -1:
                        this.isConfirmView = true
                        break
                    case param.indexOf('add') !== -1:
                        this.isAmountAddView = true
                        break
                    case param.indexOf('show') !== -1:
                        this.isAmountListView = true
                        break
                    default:
                        this.isStartView = true
                        break
                }

            }else{
                this.isStartView = true;
            }

            this.getUserProfile();
            this.getGroupId();
        })
    }
}
</script>