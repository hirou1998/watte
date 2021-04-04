<template>
    <modal-base @close="close" :visibility="visibility">
        <template v-slot:content>
            <p class="big-txt">{{target.event_name}} <span class="small-txt">を<span class="normal-txt red-txt">アーカイブ</span>してもいいですか？</span></p>
            <div>
                <p class="small-txt">※イベントをアーカイブしても割り勘のデータは削除されませんが、「@watte」と入力してもイベントへのボタンは表示されなくなります。アーカイブ後のイベントは「イベント一覧」から確認でき、アーカイブから戻すことも可能です。</p>
            </div>
        </template>
        <template v-slot:button>
            <form-button value="アーカイブ" type="accept" @send="execute"></form-button>
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