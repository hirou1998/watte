<template>
    <section>
        <div class="participants-head">
            <h1 class="txt-big">{{event.event_name}}({{participants.length}}人)</h1>
            <div class="refresh-button" role="button" @click="refresh">
                <img src="/images/refresh.png" alt="更新">
                <p class="refresh-button-text">プロフィール画像<br>ユーザー名を更新</p>
            </div>
        </div>
        <ul class="participants-contaier">
            <participant v-for="participant in participants" :participant="participant" :key="participant.line_id"></participant>
        </ul>
    </section>
</template>

<script>
import Participant from './modules/Participant'
import getUserInfoMixin from '../mixins/getUserInfoMixin'

export default {
    props: ['event', 'liff', 'participants'],
    components: {
        Participant
    },
    data(){
        return{
            changeItem: {
                display_name: '',
                picture_url: ''
            }
        }
    },
    methods: {
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
        }
    },
    mounted(){
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