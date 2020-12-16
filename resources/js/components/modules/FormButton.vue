<template>
    <button 
        :class="[type === 'accept' ? 'btn-info' : 'btn-danger']"
        class="btn form-btn mt-2" 
        @click="send"
    >{{value}}</button>
</template>

<script>
export default {
    props: ['value', 'type'],
    methods: {
        send(){
            this.$emit('send');
        }
    }
}
</script>