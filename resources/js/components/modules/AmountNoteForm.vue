<template>
    <div class="form-group">
        <p class="normal-txt">メモ</p>
        <input type="text" v-model="memo" class="form-control" placeholder="何に払いましたか？">
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