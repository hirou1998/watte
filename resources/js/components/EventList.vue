<template>
    <section class="section-inner">
        <div class="show-condition-container">
            <div class="show-condition-block">
                <p class="small-txt light-txt">表示するイベント</p>
                <div class="d-flex">
                    <checkbox-block 
                        label="未アーカイブ" 
                        v-model="unarchivedEventVisibility" 
                        @change="changeEventVisibility"
                        ref="unarchived-check"
                    ></checkbox-block>
                    <checkbox-block label="アーカイブ済" v-model="archivedEventVisibility" @change="changeEventVisibility"></checkbox-block>
                </div>
            </div>
            <div class="show-condition-block">
                <p class="small-txt light-txt">イベントの表示順</p>
                <div class="form-group">
                    <select class="form-control" v-model="selectedOrderOptionId" @change="sortEvents">
                        <option v-for="option in orderOptions" :key="option.id" :value="option.id">{{option.value}}</option>
                    </select>
                </div>
            </div>
        </div>
        <ul>
            <event-card
                v-for="event in eventList"
                :event="event"
                :key="event.id"
            ></event-card>
        </ul>
        <api-loading v-if="isApiLoading"></api-loading>
    </section>
</template>

<script>
import ApiLoading from './modules/ApiLoading'
import CheckboxBlock from './modules/CheckboxBlock'
import EventCard from './modules/EventCard'
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'
import handleErrMinxin from '../mixins/handleErrMinxin'

export default {
    props: {
        liff: String,
        deployUrl: String,
    },
    components: {
        ApiLoading,
        CheckboxBlock,
        EventCard
    },
    data(){
        return{
            archivedEventVisibility: false,
            eventList: [],
            isApiLoading: true,
            orderOptions: [
                {
                    id: 0, 
                    value: '作成日が新しい順',
                    key: 'created_at',
                    desc: true
                },
                {
                    id: 1, 
                    value: '作成日が古い順',
                    key: 'created_at',
                    desc: false
                },
                {
                    id: 2, 
                    value: '合計金額が多い順',
                    key: 'sum',
                    desc: true
                },
                {
                    id: 3, 
                    value: '合計金額が少ない順',
                    key: 'sum',
                    desc: false
                },
                {
                    id: 4, 
                    value: '参加人数が多い順',
                    key: 'participants',
                    desc: true
                },
                {
                    id: 5, 
                    value: '参加人数が少ない順',
                    key: 'participants',
                    desc: false
                }
            ],
            selectedOrderOptionId: 0,
            unarchivedEventVisibility: true,
        }
    },
    methods: {
        changeEventVisibility(category){
            if(!this.unarchivedEventVisibility && !this.archivedEventVisibility){
                alert('どちらか必ず選択してください。')
                this.unarchivedEventVisibility = true;
                this.$nextTick();
            }
        },
        getEventList(){
            //window.axios.get(`/api/event/list/${this.groupId}`)
            window.axios.get(`/api/event/list/C770bf141bae7bc3206716627ca5bb87f`)
            .then(({data}) => {
                this.eventList = data;
                this.isApiLoading = false;
            })
            .catch(err => {
                this.handleErr(err.response.status)
            })
        },
        hideLoading(){
            this.isLoading = false;
            this.getEventList();
        },
        sortEvents(){
            this.isApiLoading = true;
            let option = this.orderOptions[this.selectedOrderOptionId]
            let key = option['key'];
            let type = option['desc'];
            if(type){
                this.eventList.sort((a, b) => {
                    if(a[key] > b[key]) return -1;
                    if(a[key] < b[key]) return 1;
                    return 0;
                })
            }else{
                this.eventList.sort((a, b) => {
                    if(a[key] < b[key]) return -1;
                    if(a[key] > b[key]) return 1;
                    return 0;
                })
            }
            this.isApiLoading = false;
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
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin, handleErrMinxin]
}
</script>