<template>
    <modal-base @close="close" :visibility="visibility">
        <template v-slot:content>
            <p class="small-txt">「個人の貸し借りを記録する」ボタンを有効にすると、イベントに参加している全員にではなく、個人間の支払いを記録することができます。合計支払額を入力してください。<br>個人間の貸し借りとして記録した場合、全体の支払い金額合計及び一人当たり金額には含まれませんが、各ユーザーの支払い済金額及び割り勘代には加算されます。</p>
            <img src="/images/private-info-example.png" alt="" class="info-picture">
            <p class="small-txt">※一度個人の支払いとして登録すると、全体の支払いへの変更及び支払い相手の変更はできません。</p>
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