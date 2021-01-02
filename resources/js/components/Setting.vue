<template>
    <section class="setting-container">
        <a :href="participantsUrl" class="setting-menu" role="button">
            <div class="setting-menu-icon">
                <img src="/images/members.png" alt="">
            </div>
            <p class="normal-txt text-center setting-menu-text">参加者の管理</p>
        </a>
        <a :href="participantsUrl" class="setting-menu" role="button">
            <div class="setting-menu-icon">
                <img src="/images/event.png" alt="">
            </div>
            <p class="normal-txt text-center setting-menu-text">イベント情報変更</p>
        </a>
    </section>
</template>

<script>
import getUserInfoMixin from '../mixins/getUserInfoMixin'

export default {
    props: ['liff', 'event'],
    computed: {
        participantsUrl(){
            return `https://liff.line.me/1655325455-B5Zjk37g/participants/${this.event.id}`;
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