<template>
    <div class="form-group">
        <p class="small-txt form-item-title">{{text}}</p>
        <select class="form-control payer-select form-item-content" v-model="payer.userId">
            <option disabled selected v-if="placeHolder" class="form-placeholder">{{placeHolder}}</option>
            <option :value="participant.line_id" v-for="participant in participants" :key="participant.line_id">{{participant.display_name}}</option>
        </select>
    </div>
</template>

<script>
export default {
    props: ['user', 'participants', 'text', 'placeHolder'],
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