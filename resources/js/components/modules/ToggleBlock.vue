<template>
    <div class="toggle-block">
        <p class="normal-txt toggle-block-txt">{{text}}</p>
        <div class="toggle-button" @click="toggle">
            <input id="toggle" type="checkbox" class="toggle-button__input">
            <div class="toggle-button__switch" :data-enabled="enabled ? 'true' : 'false'"></div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['isPrivate', 'text'],
    model: {
        prop: 'isPrivate',
        event: 'change'
    },
    computed: {
        enabled: {
            get(){
                return this.isPrivate
            },
            set(value){
                this.$emit('change', value)
            }
        }
    },
    methods: {
        toggle(){
            this.enabled = !this.enabled
        }
    }
}
</script>