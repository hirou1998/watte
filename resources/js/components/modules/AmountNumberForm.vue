<template>
    <div class="form-group">
        <p class="normal-txt">金額</p>
        <input type="number" v-model="number" class="form-control">円
    </div>
</template>

<script>
export default {
    props: ['amount'],
    model: {
        prop: 'amount',
        event: 'change'
    },
    computed: {
        number: {
            get(){
                return this.amount
            },
            set(amount){
                this.updateValue(amount)
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