<template>
    <button class="btn hamburger-button" role="button" :data-expanded="state" @click="clickButton">
        <span class="hamburger-button--bar"></span>
    </button>
</template>

<script>
export default {
    props: ['isClicked'],
    computed: {
        state(){
            return this.isClicked
        }
    },
    methods: {
        clickButton(){
            this.$emit('change')
        }
    }
}
</script>