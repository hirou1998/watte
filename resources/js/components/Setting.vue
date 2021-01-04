<template>
    <section>
        <article class="setting-container" v-if="!isLoading">
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
    computed: {
        participantsUrl(){
            return `https://liff.line.me/1655325455-B5Zjk37g/participants/${this.event.id}?group=${this.groupId}`;
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