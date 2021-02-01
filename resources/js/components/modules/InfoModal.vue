<template>
    <modal-base @close="close" :visibility="visibility">
        <template v-slot:content>
            <p class="small-txt">Watteでは、支払い追加時、精算/未精算時、削除時に以下のようにトークに通知します。<br>通知は上記の操作を行ったユーザーの発言として投稿されます。</p>
            <img src="/images/info-example.png" alt="" class="info-picture">
            <p class="small-txt">Watte利用時のトーク通知をオフにすると通知は行われません。<br>設定の変更は、各イベントの 設定 > イベント情報設定 から行えます。</p>
        </template>
        <template v-slot:button>
            <form-button value="OK" type="accept" @send="close"></form-button>
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
    props: ['visibility'],
    methods: {
        close(){
            this.$emit('close');
        },
    }
}
</script>