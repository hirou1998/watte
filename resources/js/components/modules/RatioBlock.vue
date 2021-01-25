<template>
    <div class="participant-ratio-block">
        <p class="small-txt ratio-text">比率：<span class="big-txt">{{ratioNum}}</span></p>
        <div 
            v-for="ratio in ratioIcons" 
            :key="random()" 
            :data-ratio="ratio" 
            class="ratio-icon-container"
            :style="{width: 'calc(24px *' + ratio + ')'}"
        >
            <img src="/images/ratio-icon.png" class="ratio-icon">
        </div>
    </div>
</template>

<script>
export default {
    props: {
        ratioNum: {
            type: Number,
        }
    },
    data: function(){
        return{
            ratioArray: []
        }
    },
    computed: {
        ratioIcons(){
            this.ratioArray = [];
            let num = Math.floor(this.ratioNum);
            let decimal = this.ratioNum - num;
            for(var i = 0; i < num; i++){
                this.ratioArray.push(1)
            }
            if(decimal){
                this.ratioArray.push(decimal)
            }
            return this.ratioArray;
        },
    },
    methods: {
        random(){
            return Math.random();
        }
    }
}
</script>