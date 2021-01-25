<template>
    <section class="modal-base ratio-modal" @click="close">
        <div class="modal-inner ratio-modal-inner" @click.stop>
            <ul>
                <li v-for="participant in participants" :key="participant.line_id">
                    <profile-block :user="participant" iconSize="30"></profile-block>
                    <div class="form-group ratio-form-block">
                        <p class="small-txt ratio-form-text">現在の比率：</p>
                        <input 
                            type="number" 
                            step="0.01" 
                            :value="participant.pivot.ratio"
                            :ref="'ratio-' + participant.line_id"
                            class="form-control ratio-form-input"
                        >
                    </div>
                </li>
            </ul>
            <form-button value="保存" type="accept" @send="send"></form-button>
            <form-button value="取消" type="deny" @send="cancel"></form-button>
        </div>
    </section>
</template>

<script>
import FormButton from './FormButton';
import ProfileBlock from './ProfileBlock'

export default {
    props: ['participants'],
    components: {
        FormButton,
        ProfileBlock
    },
    data(){
        return {
            formItem: []
        }
    },
    computed: {
        
    },
    methods: {
        cancel(){
            this.participants.forEach(participant => {
                this.$refs[`ratio-${participant.line_id}`][0].value = participant.pivot.ratio
            })
            this.close();
        },
        close(){
            this.$emit('close');
        },
        send(){
            this.formItem = [];
            let invalid = false;
            this.participants.forEach(participant => {
                let ratioValue = this.validateDecimal(this.$refs[`ratio-${participant.line_id}`][0].value);
                if(ratioValue){
                    let sendItem = {
                        line_id: participant.line_id,
                        ratio: ratioValue
                    }
                    this.formItem.push(sendItem)
                }else{
                    invalid = true;
                    return
                }
            });
            if(invalid){
                alert('比率は小数点以下2桁以内の数字のみ入力可能です。')
                return
            }else{
                this.$emit('save', this.formItem)
            }
            this.close()
        },
        validateDecimal(value){
            let result = value.match(/^(\d+)(\.\d{0,2})?/u);
            return result ? Number(result[0]) : false;
        },
    }
}
</script>