<template>
    <section class="modal-base ratio-modal" @click="close">
        <div class="modal-inner ratio-modal-inner" @click.stop>
            <ul>
                <li v-for="participant in participants" :key="participant.line_id">
                    <profile-block :user="participant"></profile-block>
                    <div class="form-group">
                        <p class="small-txt">現在の比率：</p>
                        <input type="number" v-model="participant.pivot.ratio">
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
        // ratio
    },
    methods: {
        close(){
            this.$emit('close');
        }
    }
}
</script>