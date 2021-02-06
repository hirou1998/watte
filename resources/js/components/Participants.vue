<template>
    <section class="section-inner">
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
                :visibility="modalVisibility"
                :participants="participants"
                @save="changeRatio"
                @close="modalVisibility = false"
            ></ratio-modal>
        </article>
        <loading v-if="isLoading"></loading>
        <api-loading v-if="isApiLoading"></api-loading>
    </section>
</template>

<script>
import ApiLoading from './modules/ApiLoading'
import Loading from './modules/Loading'
import Participant from './modules/Participant'
import RatioModal from './modules/RatioModal'
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'
import allowAccessIfWithGroupIdMixin from '../mixins/allowAccessIfWithGroupIdMixin'
import handleErrMinxin from '../mixins/handleErrMinxin'

export default {
    props: ['event', 'liff'],
    components: {
        ApiLoading,
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
            isLoading: true,
            isApiLoading: true,
            participants: {},
        }
    },
    methods: {
        changeRatio(param){
            let changedItems = param.filter(item => {
                let currentUserInfo = this.participants.find(participant => participant.line_id === item.line_id)
                let newRatio = item.ratio
                let currentRatio = currentUserInfo.pivot.ratio
                if(item.ratio !== currentUserInfo.pivot.ratio){
                    return item
                }
            })
            if(changedItems.length > 0){
                this.isApiLoading = true
                window.axios.put(`/ratio/update/${this.event.id}`, {
                    value: changedItems
                })
                .then(({data}) => {
                    this.participants = data;
                    this.isApiLoading = false
                })
                .catch(err => {
                    console.log(err)
                    this.isApiLoading = false
                })
            }else{
                alert('変更された比率がありませんでした。')
            }
        },
        getParticipants(){
            window.axios.get(`/api/participants/${this.event.id}`)
            .then(({data}) => {
                this.isApiLoading = false
                this.participants = data;
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        },
        hideLoading(){
            this.isLoading = false;
            this.getParticipants();
        },
        refresh(){
            let accessUser = this.participants.find(p => p.line_id === this.userInfo.userId);
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
                    this.handleErr(err.response.status)
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
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin, allowAccessIfWithGroupIdMixin, handleErrMinxin]
}
</script>