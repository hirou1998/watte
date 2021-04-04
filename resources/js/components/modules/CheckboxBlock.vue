<template>
    <div class="form-group checkbox-block">
        <input type="checkbox" class="form-control checkbox-input" id="checkbox-input" v-model="checked">
        <label for="checkbox-input" class="checkbox-label small-txt">{{label}}</label>
    </div>
</template>

<script>
export default {
    props: ['label', 'checkFlg'],
    model: {
        prop: 'checkFlg',
        event: 'change'
    },
    computed: {
        checked: {
            get(){
                return this.checkFlg;
            },
            set(checkFlg){
                this.updateValue(checkFlg)
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