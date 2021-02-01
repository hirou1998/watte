<template>
    <button
        class="btn info-btn"
        @click="showInfo"
        >?
    </button>
</template>

<script>
export default {
    methods: {
        showInfo(){
            this.$emit('show')
        }
    }
}
</script>