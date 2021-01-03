<template>
    <article>
        <section class="start-form-container" v-if="!isLoading">
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
        </section>
        <loading v-if="isLoading"></loading>
    </article>
</template>

<script>
import FormButton from './modules/FormButton';
import Loading from './modules/Loading';

export default {
    components: {
        FormButton,
        Loading
    },
    props: ['liff', 'deployUrl'],
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
        async checkAccess(){
            await this.getUserProfile();
            await this.getGroupId();
        },
        async getUserProfile(){
            await window.liff.getProfile()
                    .then(profile => {
                        this.userInfo = profile;
                        //this.isRegisteredUser();
                    })
                    .catch(e => {
                        alert("403: Forbidden\nスマートフォンのLINEアプリからアクセスしてください。");
                        location.href = `${this.deployUrl}/err/forbidden`;
                        window.liff.closeWindow(); //lineからのアクセス対策
                    })
        },
        async getGroupId(){
            await (function(){
                let context = window.liff.getContext()
                if(context.type === 'group'){
                    this.groupId = context.groupId
                    this.hideLoading();
                }else{
                    alert('403: Forbiddend\nWatteを利用されるグループトーク内でアクセスしてください。');
                    location.href = `${this.deployUrl}/err/forbidden`;
                    window.liff.closeWindow();
                }
            })
        },
        hideLoading(){
            this.isLoading = false;
        },
        isRegisteredUser(){
            axios.get(`/api/linefriend?id=${this.userInfo.userId}`)
            .then(({data}) => {
                alert(data)
            })
            .catch(err => {
                alert(err)
            })
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
    created(){
        window.liff.init({
            liffId: this.liff
        })
        .then((data) => {

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

            this.checkAccess();
        })
    }
}
</script>