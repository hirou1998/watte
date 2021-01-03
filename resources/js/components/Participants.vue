<template>
    <section>
        <article v-if="!isLoading">
            <div class="participants-head">
                <h1 class="txt-big">{{event.event_name}}({{participants.length}}人)</h1>
                <div class="refresh-button" role="button" @click="refresh">
                    <img src="/images/refresh.png" alt="更新">
                    <p class="refresh-button-text">プロフィール画像<br>ユーザー名を更新</p>
                </div>
            </div>
            <ul class="participants-contaier">
                <participant 
                    v-for="participant in participants" 
                    :participant="participant" 
                    :key="participant.line_id"
                    @show="showModal"
                ></participant>
            </ul>
            <ratio-modal
                v-show="modalVisibility"
                v-model="participants"
                @close="modalVisibility = false"
            ></ratio-modal>
        </article>
        <loading v-if="isLoading"></loading>
    </section>
</template>

<script>
import Loading from './modules/Loading'
import Participant from './modules/Participant'
import RatioModal from './modules/RatioModal'
import checkAccessMixin from '../mixins/checkAccessMixin'

export default {
    props: ['event', 'liff', 'participants'],
    components: {
        Loading,
        Participant,
        RatioModal,
    },
    data(){
        return{
            changeItem: {
                display_name: '',
                picture_url: ''
            },
            modalVisibility: false,
        }
    },
    methods: {
        getGroupId(){
            let context = window.liff.getContext()
            if(context.type === 'none'){ //正規ルートはcontextがnone
                let param = location.search;
                let paramObj = this.makeObjectFromSearchParam(param)
                let paramGroupId = paramObj['group'];
                this.groupId = paramGroupId;
                this.hideLoading();
            }else{
                if(context.type === 'group'){
                    this.groupId = context.groupId
                    //groupIdが正しいかのチェックを後で実装
                    this.hideLoading();
                }else{
                    alert('403: Forbiddend\nWatteを利用されるグループトーク内でアクセスしてください。');
                    location.href = `${this.deployUrl}/err/forbidden`;
                    window.liff.closeWindow();
                }
            }
        },
        makeObjectFromSearchParam(param){
            param = param.substring(1);
            param = param.split('&');
            let paramObj = {};
            param.forEach(p => {
                let dividedParam = p.split('=');
                let paramKey = dividedParam[0];
                let paramValue = dividedParam[1];
                paramObj = {
                    [paramKey]: paramValue
                }
            });
            return paramObj;
        },
        refresh(){
            let accessUser = this.participants.filter(p => p.line_id === this.userInfo.userId)[0];
            if(accessUser.display_name !== this.userInfo.displayName){
                this.changeItem = {
                    ...this.changeItem,
                    display_name: this.userInfo.displayName
                }
            }
            if(accessUser.picture_url !== this.userInfo.pictureUrl){
                this.changeItem = {
                    ...this.changeItem,
                    picture_url: this.userInfo.pictureUrl
                }
            }
            if(this.changeItem.display_name === '' && this.changeItem.picture_url === ''){
                alert(`${this.userInfo.displayName}さんのユーザー情報は最新です。`)
            }else{
                let display_name = (this.changeItem.display_name === '')
                    ? accessUser.display_name
                    : this.changeItem.display_name;
                let picture_url = (this.changeItem.picture_url === '')
                    ? accessUser.picture_url
                    : this.changeItem.picture_url;
                axios.put(`/participant/info/update/${accessUser.line_id}`, {
                    display_name: display_name,
                    picture_url: picture_url,
                    event: this.event.id
                })
                .then(({data}) => {
                    
                })
                .catch((err) => {

                })
            }
        },
        showModal(){
            this.modalVisibility = true
        }
    },
    mounted(){
        window.liff.init({
            liffId: this.liff
        })
        .then(() => {
            this.checkAccess();
        })
    },
    mixins: [checkAccessMixin]
}
</script>