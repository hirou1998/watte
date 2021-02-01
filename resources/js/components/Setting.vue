<template>
    <section>
        <article class="setting-container" v-if="!isLoading">
            <a :href="participantsUrl" class="setting-menu" role="button">
                <div class="setting-menu-icon">
                    <img src="/images/members.png" alt="">
                </div>
                <p class="normal-txt text-center setting-menu-text">参加者確認<br>割り勘比率変更</p>
            </a>
            <a :href="eventEditUrl" class="setting-menu" role="button">
                <div class="setting-menu-icon">
                    <img src="/images/event.png" alt="">
                </div>
                <p class="normal-txt text-center setting-menu-text">イベント情報変更</p>
            </a>
            <div class="setting-menu" role="button" @click="sendInvitation">
                <div class="setting-menu-icon">
                    <img src="/images/plus-icon.png" alt="">
                </div>
                <p class="normal-txt text-center setting-menu-text">参加者の追加</p>
            </div>
        </article>
        <loading v-if="isLoading"></loading>
    </section>
</template>

<script>
import Loading from './modules/Loading';
import checkAccessMixin from '../mixins/checkAccessMixin'
import checkIsAccessingFromCorrectGroupMixin from '../mixins/checkIsAccessingFromCorrectGroupMixin'

export default {
    components: { Loading },
    props: ['liff', 'event'],
    data(){
        return{
            isLoading: true
        }
    },
    computed: {
        participantsUrl(){
            return `https://liff.line.me/1655325455-B5Zjk37g/participants/${this.event.id}?group=${this.groupId}`;
        },
        eventEditUrl(){
            return `https://liff.line.me/1655325455-B5Zjk37g/event/edit/${this.event.id}?group=${this.groupId}`;
        }
    },
    methods: {
        sendInvitation(){
            let url = `https://liff.line.me/1655325455-B5Zjk37g/confirm?type=confirm&id=${this.event.id}`;
            window.liff.sendMessages([
                {
                    type: 'template',
                    altText: `イベント: ${this.event.event_name}\nに参加しますか？`,
                    template: {
                        type: 'confirm',
                        text: `イベント: ${this.event.event_name}\nに参加しますか？`,
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
            ]);
            window.liff.closeWindow();
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
    mixins: [checkAccessMixin, checkIsAccessingFromCorrectGroupMixin]
}
</script>