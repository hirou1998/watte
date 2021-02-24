<template>
    <div class="form-group">
        <p class="small-txt form-item-title">金額</p>
        <div class="d-flex amount-number-block form-item-content">
            <input type="tel" v-model="number" class="form-control" placeholder="金額を数字で入力してください">
            <p class="small-txt mb-0 ml-2">円</p>
        </div>
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