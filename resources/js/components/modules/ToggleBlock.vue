<template>
    <div class="toggle-block">
        <p class="normal-txt toggle-block-txt">
            {{text}}
            <info-block
                @show="showInfo">
            </info-block>
        </p>
        <div class="toggle-button" @click="toggle">
            <input id="toggle" type="checkbox" class="toggle-button__input">
            <div class="toggle-button__switch" :data-enabled="enabled ? 'true' : 'false'"></div>
        </div>
    </div>
</template>

<script>
import InfoBlock from './InfoBlock'

export default {
    props: ['isPrivate', 'text'],
    model: {
        prop: 'isPrivate',
        event: 'change'
    },
    components: {
        InfoBlock
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
        showInfo(){
            this.$emit('show')
        },
        toggle(){
            this.enabled = !this.enabled
        }
    }
}
</script>