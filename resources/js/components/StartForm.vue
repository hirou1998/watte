<template>
    <article class="section-inner">
        <section class="start-form-container" v-if="!isLoading">
            <template v-if="isStartView">
                <p class="normal-txt text-center">{{userInfo.displayName}}さん<br>watteのご利用ありがとうございます！</p>
                <form @submit.prevent class="w-100">
                    <div class="form-group mt-5">
                        <p class="normal-txt">何を割り勘しますか？</p>
                        <input type="text" class="form-control" v-model="eventName" placeholder="例：〇〇旅行、〇〇飲み会">
                    </div>
                    <image-form 
                        :picture="preview"
                        @uploaded="changeImage"
                    ></image-form>
                    <toggle-block 
                        v-model="isNotificationOn" 
                        text="Watte利用時のトーク通知"
                        @show="showInfo"
                    ></toggle-block>
                    <template v-if="eventName !== ''">
                        <form-button value="送信" type="accept" @send="send"></form-button>
                    </template>
                </form>
            </template>
            <template v-else>
                <p class="normal-txt text-center" v-if="isRedirectView">リダイレクトします。</p>
            </template>
        </section>
        <info-modal 
            :visibility="infoModalVisibility"
            @close="infoModalVisibility = false"
        ></info-modal>
        <loading v-if="isLoading"></loading>
        <api-loading v-if="isApiLoading"></api-loading>
    </article>
</template>

<script>
import ApiLoading from './modules/ApiLoading'
import FormButton from './modules/FormButton';
import InfoModal from './modules/InfoModal';
import ImageForm from './modules/ImageForm';
import Loading from './modules/Loading';
import ToggleBlock from './modules/ToggleBlock.vue'
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIfUserAndGroupIsRegistered from '../mixins/checkIfUserAndGroupIsRegistered'
import handleErrMinxin from '../mixins/handleErrMinxin'

export default {
    components: {
        FormButton,
        InfoModal,
        ImageForm,
        Loading,
        ToggleBlock
    },
    props: ['liff', 'deployUrl'],
    data: function(){
        return{
            userInfo: {},
            groupId: '',
            eventName: '',
            preview: '',
            file: '',
            registerd: {},
            infoModalVisibility: false,
            isLoading: true,
            isApiLoading: false,
            isNotificationOn: true,
            isStartView: false,
            isRedirectView: false,
            otherPath: ['confirm', 'add', 'show', 'setting', 'participants', 'edit', 'list'],
            session: {
                line_id: '',
                group_id: ''
            }
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
        changeImage(data){
            this.preview = data.preview
            this.file = data.file
        },
        send(){
            this.isApiLoading = true;
            let params = new FormData()
            let notification = this.isNotificationOn ? 1 :0;
            params.append('file', this.file)
            params.append('event_name', this.eventName)
            params.append('group_id', this.groupId)
            params.append('creator_id', this.userInfo.userId)
            params.append('notification', notification)
            window.axios.post('/create/new', params,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(({data}) => {
                let text = "イベント: 「" + data.event_name + "」 を作成しました。\n参加しますか？";
                let eventId = data.id;
                this.isApiLoading = false;
                this.askJoin(text, eventId);
                window.liff.closeWindow();
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        },
        showInfo(){
            this.infoModalVisibility = true;
        }
    },
    mounted(){
        window.liff.init({
            liffId: this.liff
        })
        .then((data) => {
            let param = location.search;
            
            if(param){
                this.otherPath.forEach(p => {
                    if(param.indexOf(p) !== -1){
                        this.isRedirectView = true;
                        return
                    }
                })
                if(!this.isRedirectView){
                    this.isStartView = true;
                    this.checkAccess();
                }
            }else{
                this.isStartView = true;
                this.checkAccess();
            }
        })
    },
    mixins: [checkAccessMixin, checkIfUserAndGroupIsRegistered, handleErrMinxin]
}
</script>