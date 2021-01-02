<template>
    <li class="participant-item">
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
    mounted(){
        
    }
}
</script>