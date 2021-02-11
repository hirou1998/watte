<template>
    <transition name="fade">
        <section class="notification-container" v-if="visibility">
            <div class="notification-block">
                <span class="notification-icon">&#10004;</span>
                <p class="notification-text">{{action}}しました。</p>
            </div>
            <button class="btn notification-close" @click.stop="close">×</button>
        </section>
    </transition>
</template>

<script>
export default {
    props: ['action', 'visibility'],
    methods: {
        close(){
            this.$emit('close')
        }
    }
}
</script>