<template>
    <li class="transaction-item">
        <div class="transaction-item-inner">
            <div class="transaction-item-left">
                <profile-block :user="fromUser" icon-size="25"></profile-block>
                <p class="normal-txt mb-0"> → </p>
                <profile-block :user="toUser" icon-size="25"></profile-block>
            </div>
            <div>
                <p class="normal-txt transaction-item-text">{{amount}}<span class="small-txt">円</span></p>
            </div>
        </div>
        <p class="small-txt amount-register-date">登録日時：{{dateParser(transaction.created_at)}}</p>
    </li>
</template>

<script>
import ProfileBlock from './ProfileBlock'
import FormatDateTimeMixin from '../../mixins/formatDateTimeMixin'

export default {
    props: ['transaction', 'participants'],
    components: {
        ProfileBlock
    },
    computed: {
        fromUser(){
            return this.participants.find(participant => participant.line_id === this.transaction.from_user);
        },
        toUser(){
            return this.participants.find(participant => participant.line_id === this.transaction.to_user);
        },
        amount(){
            return String(this.transaction.amount).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        },
    },
    mixins: [FormatDateTimeMixin]
}
</script>