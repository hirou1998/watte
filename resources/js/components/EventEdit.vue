<template>
    <section class="section-inner">
        <notification
            v-for="(action, index) in doneAction"
            :key="index"
            :action="action.text" 
            :visibility="action.notificationVisibility"
            @close="action.notificationVisibility = false"
        ></notification>
        <article v-if="!isLoading">
            <form @submit.prevent class="w-100">
                <div class="form-group">
                    <p class="normal-txt">イベント名</p>
                    <input type="text" v-model="editingData.event_name" class="form-control" required>
                </div>
                <image-form 
                    :picture="url"
                    @uploaded="changeImage"
                ></image-form>
                <toggle-block
                    v-model="editingData.notification" 
                    text="Watte利用時のトーク通知"
                    @show="showInfo"
                >
                </toggle-block>
                <div class="">
                    <form-button value="保存" type="accept" @send="send" v-if="isChanged"></form-button>
                    <form-button value="取消" type="deny" @send="cancel" v-if="isChanged"></form-button>
                </div>
            </form>
        </article>
        <info-modal 
            :visibility="infoModalVisibility"
            @close="infoModalVisibility = false"
        ></info-modal>
        <api-loading v-if="isApiLoading"></api-loading>
    </section>
</template>

<script>
import ApiLoading from './modules/ApiLoading'
import FormButton from './modules/FormButton';
import ImageForm from './modules/ImageForm';
import InfoModal from './modules/InfoModal';
import Loading from './modules/Loading';
import Notification from './modules/Notification';
import ToggleBlock from './modules/ToggleBlock.vue';
import checkAccessMixin from '../mixins/checkAccessMixin';
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin';
import allowAccessIfWithGroupIdMixin from '../mixins/allowAccessIfWithGroupIdMixin';
import handleErrMinxin from '../mixins/handleErrMinxin';
import formValidatorMixin from '../mixins/formValidatorMixin';

export default {
    props: ['liff', 'deployUrl', 'eventId'],
    components: {
        ApiLoading,
        FormButton,
        ImageForm,
        InfoModal,
        Loading,
        Notification,
        ToggleBlock
    },
    data(){
        return {
            doneAction: [],
            event: {},
            editingData: {
                event_name: null,
                file_path: null,
                file: null,
                notification: null
            },
            isApiLoading: true,
            isLoading: false,
            infoModalVisibility: false,
        }
    },
    computed: {
        isChanged(){
            if(this.event.event_name !== this.editingData.event_name || this.editingData.file || this.event.notification !== this.editingData.notification){
                return true;
            }else{
                return false;
            }
        },
        url(){
            if(this.editingData.file_path){
                if(this.editingData.file_path.match(/^storage.*/)){
                    return '/' + this.editingData.file_path;
                }else{
                    return this.editingData.file_path;
                }
            }else{
                return '';
            }
        }
    },
    methods: {
        cancel(){
            this.editingData = {...this.event, file: null};
        },
        changeImage(data){
            this.editingData.file_path = data.preview;
            this.editingData.file = data.file;
        },
        getEventInfo(){
            axios.get(`/api/event/${this.eventId}`)
            .then(({data}) => {
                this.event = {
                    ...data,
                    notification: data.notification == '0' ? false : true
                };
                this.setEditingData();
                this.isApiLoading = false
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        },
        hideLoading(){
            this.isLoading = false;
            this.getEventInfo();
        },
        setEditingData(){
            this.editingData.event_name = this.event.event_name;
            this.editingData.file_path = this.event.file_path;
            this.editingData.notification = this.event.notification == '0' ? false : true;
            this.editingData.file = null
        },
        showInfo(){
            this.infoModalVisibility = true;
        },
        send(){
            if(!this.isTextFilled(this.editingData.event_name)){
                alert('イベント名が空欄です。');
                return
            }
            this.isApiLoading = true;
            let sendData = new FormData();
            let notification = this.editingData.notification ? 1 :0;
            sendData.append('event_name', this.editingData.event_name);
            if(this.editingData.file){
                sendData.append('file', this.editingData.file);
            }
            sendData.append('notification', notification)
            window.axios.post(`/event/edit/${this.eventId}`, sendData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(({data}) => {
                this.event = {
                    ...data,
                    notification: data.notification == '0' ? false : true
                };
                this.setEditingData();
                this.isApiLoading = false;
                this.doneAction.push({text: '編集を保存', notificationVisibility: true})
                let doneActionNumber = this.doneAction.length - 1;
                setTimeout(() => {
                    this.$set(this.doneAction, doneActionNumber, {notificationVisibility: false})
                }, 2000)
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        }
    },
    mounted(){
        window.liff.init({
            liffId: this.liff
        })
        .then(() => {
            this.hideLoading()
            //this.checkAccess();
        })
        .catch(err => {
            alert('データの取得に失敗しました')
        })
    },
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin, allowAccessIfWithGroupIdMixin, handleErrMinxin, formValidatorMixin]
}
</script>