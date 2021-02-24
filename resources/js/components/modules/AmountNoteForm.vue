<template>
    <div class="form-group">
        <p class="small-txt form-item-title">メモ</p>
        <input type="text" v-model="memo" class="form-control form-item-content" placeholder="何に払いましたか？">
    </div>
</template>

<script>
export default {
    props: ['note'],
    model: {
        prop: 'note',
        event: 'change'
    },
    computed: {
        memo: {
            get(){
                return this.note
            },
            set(note){
                this.updateValue(note)
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