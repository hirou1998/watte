<template>
    <li class="participant-item">
        <profile-block :user="participant"></profile-block>
        <div class="participant-ratio-block">
            <p class="small-txt ratio-text">比率：<span class="big-txt">{{participant.pivot.ratio}}</span></p>
            <div 
                v-for="ratio in ratioIcons" 
                :key="random" 
                :data-ratio="ratio" 
                class="ratio-icon-container"
                :style="{width: 'calc(30px *' + ratio + ')'}"
            >
                <img src="/images/ratio-icon.png" class="ratio-icon">
            </div>
        </div>
    </li>
</template>

<script>
import ProfileBlock from './ProfileBlock'

export default {
    props: ['participant'],
    components: {
        ProfileBlock
    },
    data: function(){
        return{
            ratioArray: []
        }
    },
    computed: {
        ratioIcons(){
            let num = Math.floor(this.participant.pivot.ratio);
            let decimal = String(this.participant.pivot.ratio).split('.')[1];
            for(var i = 0; i < num; i++){
                this.ratioArray.push(1)
            }
            if(decimal){
                this.ratioArray.push(decimal)
            }
            return this.ratioArray;
        },
        random(){
            return Math.random();
        }
    },
    mounted(){
        document
    }
}
</script>