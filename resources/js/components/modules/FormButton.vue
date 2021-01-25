<template>
    <button 
        :class="btnClass"
        class="btn form-btn mt-2" 
        @click="send"
    >{{value}}</button>
</template>

<script>
export default {
    props: ['value', 'type'],
    computed: {
        btnClass(){
            switch (this.type){
                case 'accept':
                    return 'btn-info'
                    break
                case 'deny':
                    return 'btn-danger'
                    break
                case 'cancel':
                    return 'btn-light'
                    break
                case 'default':
                    return 'btn-info'
            }
        }
    },
    methods: {
        send(){
            this.$emit('send');
        }
    }
}
</script>