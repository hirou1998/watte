<template>
    <section class="modal-base ratio-modal" @click="close">
        <div class="modal-inner ratio-modal-inner" @click.stop>
            <ul>
                <li v-for="participant in participants" :key="participant.line_id">
                    <profile-block :user="participant"></profile-block>
                    <div class="form-group ratio-form-block">
                        <p class="small-txt ratio-form-text">現在の比率：</p>
                        <input 
                            type="number" 
                            step="0.1" 
                            :value="participant.pivot.ratio"
                            :ref="'ratio-' + participant.id"
                            class="form-control ratio-form-input"
                        >
                    </div>
                </li>
            </ul>
            
        </div>
    </section>
</template>

<script>
import ProfileBlock from './ProfileBlock'

export default {
    props: ['participants'],
    model: {
        prop: 'participants',
        event: 'change'
    },
    components: {
        ProfileBlock
    },
    computed: {
        // ratio,
        test(){
            return this.participants[0]
        }
    },
    methods: {
        close(){
            this.$emit('close');
        }
    }
}
</script>