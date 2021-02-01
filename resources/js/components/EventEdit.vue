<template>
    <section class="section-inner">
        <article v-if="!isLoading">
            <h1 class="amount-show-title txt-big">{{event.event_name}}</h1>
            <div class="form-group">
                <p class="normal-txt">イベント名</p>
                <input type="text" v-model="event.event_name" class="form-control">
            </div>
            <image-form 
                :picture="url"
                @uploaded="changeImage"
            ></image-form>
            {{event}}
        </article>
    </section>
</template>

<script>
import ApiLoading from './modules/ApiLoading'
import ImageForm from './modules/ImageForm';
import Loading from './modules/Loading'
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'
import allowAccessIfWithGroupIdMixin from '../mixins/allowAccessIfWithGroupIdMixin'

export default {
    props: ['liff', 'deployUrl', 'eventId'],
    components: {
        ApiLoading,
        ImageForm,
        Loading,
    },
    data(){
        return {
            event: {},
            isLoading: true
        }
    },
    computed: {
        url(){
            if(this.event.file_path){
                return '/' + this.event.file_path;
            }else{
                return '';
            }
        }
    },
    methods: {
        changeImage(){

        },
        getEventInfo(){
            axios.get(`/api/event/${this.eventId}`)
            .then(({data}) => {
                this.event = data;
            })
            .catch(err => {
                console.log(err)
            })
        }
    },
    mounted(){
        window.liff.init({
            liffId: this.liff
        })
        .then(() => {
            this.getEventInfo();
            this.checkAccess();
        })
    },
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin, allowAccessIfWithGroupIdMixin]
}
</script>