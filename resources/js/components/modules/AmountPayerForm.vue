<template>
    <div class="form-group">
        <p class="normal-txt">支払い者</p>
        <select class="form-control payer-select" v-model="payer.userId">
            <option :value="participant.line_id" v-for="participant in participants" :key="participant.line_id">{{participant.display_name}}</option>
        </select>
    </div>
</template>

<script>
export default {
    props: ['user', 'participants'],
    model: {
        prop: 'user',
        event: 'change'
    },
    computed: {
        payer: {
            get(){
                return this.user
            },
            set(user){
                this.updateValue(user)
            }
        }
    },
    methods: {
        updateValue(obj){
            this.$emit('change', {...this.user, ...obj})
        }
    }
}
</script>