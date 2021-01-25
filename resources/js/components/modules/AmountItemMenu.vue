<template>
    <button 
        class="btn amount-item-menu-button"
        @click="send"
        :class="[type === 'archive' ? 'btn-success' : 'btn-danger']"
        :data-type="type"
    >{{text}}</button>
</template>

<script>
export default {
    props: ['text', 'type'],
    methods: {
        send(){
            this.$emit('send')
        }
    }
}
</script>