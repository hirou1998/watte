<template>
    <modal-base @close="close" :visibility="visibility">
        <template v-slot:content>
            <p class="big-txt">{{target.event_name}} <span class="small-txt">を<span class="normal-txt red-txt">削除</span>してもいいですか？</span></p>
            <div>
                <p class="small-txt">※イベントを削除するとデータが全て削除され、元に戻すことができません。</p>
            </div>
        </template>
        <template v-slot:button>
            <form-button value="削除" type="deny" @send="execute"></form-button>
            <form-button value="取消" type="cancel" @send="cancel"></form-button>
        </template>
    </modal-base>
</template>

<script>
import FormButton from './FormButton'
import ModalBase from './ModalBase'

export default {
    components: {
        FormButton,
        ModalBase
    },
    props: ['target', 'visibility'],
    methods: {
        cancel(){
            this.close();
        },
        close(){
            this.$emit('close');
        },
        execute(){
            this.$emit('execute');
        }
    },
}
</script>