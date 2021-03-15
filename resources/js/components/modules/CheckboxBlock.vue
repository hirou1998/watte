<template>
    <div class="form-group checkbox-block">
        <input type="checkbox" class="form-control checkbox-input" id="checkbox-input" v-model="checked">
        <label for="checkbox-input" class="checkbox-label">{{label}}</label>
    </div>
</template>

<script>
export default {
    props: ['label', 'notShowAgainFlg'],
    model: {
        prop: 'notShowAgainFlg',
        event: 'change'
    },
    computed: {
        checked: {
            get(){
                return this.notShowAgainFlg;
            },
            set(notShowAgainFlg){
                this.updateValue(notShowAgainFlg)
            }
        }
    },
    methods: {
        updateValue(value){
            this.$emit('change', value)
        }
    }
}
</script>