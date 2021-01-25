<template>
    <modal-base @close="close">
        <template v-slot:default>
            <div class="amount-modal-inner">
                <div class="amount-modal-block">
                    <p class="amount-modal-block--title">支払い者:</p>
                    <p class="amount-modal-block--content">{{target.line_friend.display_name}}</p>
                </div>
                <div class="amount-modal-block">
                    <p class="amount-modal-block--title">金額:</p>
                    <p class="amount-modal-block--content">{{target.amount}} 円</p>
                </div>
                <div class="amount-modal-block">
                    <p class="amount-modal-block--title">内容:</p>
                    <p class="amount-modal-block--content">{{target.note}}</p>
                </div>
                <div class="amount-modal-block">
                    <p class="amount-modal-block--title">登録日時:</p>
                    <p class="amount-modal-block--content">{{dateParser(target.created_at)}}</p>
                </div>
                <p class="small-txt amount-modal-confirm">を<span class="normal-txt red-txt">{{modalType}}</span>してもいいですか？</p>
            </div>
            <p class="note-txt amount-modal-note" v-if="modalType == '精算'">※支払いを精算すると、参加者全員の支払額に精算額÷参加人数の金額が加算されます。イベントの合計金額は変わりません。一度精算済にした支払いを未精算に戻すことは可能です。</p>
            <p class="note-txt amount-modal-note" v-if="modalType == '削除'">※支払いを削除すると元に戻すことはできません。イベントの支払い一覧や合計金額も変化します。</p>
            <div class="amount-modal-button-container">
                <form-button :value="modalType + 'する'" :type="modalType == '精算' ? 'accept' : 'deny'" @send="execute"></form-button>
                <form-button value="取消" type="cancel" @send="cancel"></form-button>
            </div>
        </template>
    </modal-base>
</template>

<script>
import FormButton from './FormButton'
import ModalBase from './ModalBase'
import FormatDateTimeMixin from '../../mixins/formatDateTimeMixin'

export default {
    components: {
        FormButton,
        ModalBase
    },
    props: ['modalType', 'target'],
    methods: {
        cancel(){
            this.close();
        },
        close(){
            this.$emit('close');
        },
        execute(){
            this.$emit('execute', this.modalType);
        }
    },
    mixins: [FormatDateTimeMixin]
}
</script>