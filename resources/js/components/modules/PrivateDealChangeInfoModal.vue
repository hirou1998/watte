<template>
    <modal-base @close="close" :visibility="visibility">
        <template v-slot:content>
            <p class="small-txt">アップデート情報 2021.3.16</p>
            <p class="small-txt">個人間の支払いの場合でも金額には<span>合計支払額</span>を入力するように変更しました。</p>
            <checkbox-block
                label="次回から表示しない"
                v-model="notShowAgainFlg"
            ></checkbox-block>
        </template>
        <template v-slot:button>
            <form-button value="OK" type="accept" @send="close"></form-button>
        </template>
    </modal-base>
</template>

<script>
import CheckboxBlock from './CheckboxBlock'
import FormButton from './FormButton'
import ModalBase from './ModalBase'
import handleErrMinxin from '../../mixins/handleErrMinxin'

export default {
    components: {
        CheckboxBlock,
        FormButton,
        ModalBase
    },
    props: ['visibility', 'user'],
    data(){
        return {
            notShowAgainFlg: false
        }
    },
    methods: {
        close(){
            if(this.notShowAgainFlg){
                if(this.user.userId){
                    window.axios.put(`/linefriend/check-not-show/update/${this.user.userId}`)
                    .then(({data}) => {
                        console.log(data)
                    })
                    .catch(err => {
                        this.handleErr(err.response.status)
                    })
                }else{
                    alert('ユーザー情報が取得できませんでした。');
                }
            }
            this.$emit('close');
        },
    },
    mixins: [handleErrMinxin]
}
</script>