<template>
    <li class="participant-item" @click="showModal">
        <profile-block :user="participant"></profile-block>
        <ratio-block :ratio-num="participant.pivot.ratio"></ratio-block>
    </li>
</template>

<script>
import ProfileBlock from './ProfileBlock'
import RatioBlock from './RatioBlock'

export default {
    props: ['participant'],
    components: {
        ProfileBlock,
        RatioBlock
    },
    methods: {
        showModal(){
            this.$emit('show');
        }
    }
}
</script>