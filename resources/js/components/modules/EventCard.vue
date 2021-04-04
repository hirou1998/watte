<template>
    <li class="card-item">
        <div class="event-card-head">
            <p class="normal-txt">{{event.event_name}}</p>
            <p class="normal-txt"><span class="small-txt light-txt">合計金額: </span>{{event.sum}}<span class="small-txt">円</span></p>
        </div>
        <ul class="event-card-participants">
            <li class="event-card-participants-item" v-for="participant in event.line_friends" :key="participant.line_id">
                <profile-block :user="participant" icon-size="20" name-size="1.2rem"></profile-block>
            </li>
        </ul>
        <div class="event-card-bottom">
            <p class="small-txt light-txt mb-0">作成: {{dateParser(event.created_at)}}</p>
            <div class="event-card-bottom-links">
                <a :href="toAmountListUrl">
                    <img src="/images/to-amount-list.png" alt="">
                </a>
                <a :href="toSettingUrl">
                    <img src="/images/to-setting.png" alt="">
                </a>
            </div>
        </div>
    </li>
</template>

<script>
import ProfileBlock from './ProfileBlock';
import FormatDateTimeMixin from '../../mixins/formatDateTimeMixin'

export default {
    props: {
        event: Object
    },
    components: {
        ProfileBlock
    },
    computed: {
        toAmountListUrl(){
            return `https://liff.line.me/1655325455-B5Zjk37g/amounts/show/${this.event.id}`;
        },
        toSettingUrl(){
            return `https://liff.line.me/1655325455-B5Zjk37g/setting/${this.event.id}`;
        }
    },
    mixins: [FormatDateTimeMixin]
}
</script>