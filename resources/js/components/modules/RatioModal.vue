<template>
    <modal-base @close="close" :visibility="visibility">
        <template v-slot:content>
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
        </template>
        <template v-slot:button>
            <form-button value="保存" type="accept" @send="send"></form-button>
            <form-button value="取消" type="deny" @send="cancel"></form-button>
        </template>
    </modal-base>
</template>

<script>
import FormButton from './FormButton';
import ModalBase from './ModalBase'
import ProfileBlock from './ProfileBlock'
import formValidatorMixin from '../../mixins/formValidatorMixin'

export default {
    props: ['participants', 'visibility'],
    components: {
        FormButton,
        ModalBase,
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
                console.log(this.$refs[`ratio-${participant.line_id}`][0].value)
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
    },
    mixins: [formValidatorMixin]
}
</script>