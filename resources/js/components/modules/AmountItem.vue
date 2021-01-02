<template>
    <li class="amount-item">
        <div class="amount-head-container">
            <profile-block :user="amount.line_friend"></profile-block>
            <p class="big-txt amount-number">{{number}} <span class="txt-smaller">円</span></p>
        </div>
        <div>
            <p class="normal-txt amount-memo">{{amount.note}}</p>
        </div>
        <p class="small-txt amount-register-date">登録日時：{{dateParser(amount.created_at)}}</p>
    </li>
</template>

<script>
import FormatDateTimeMixin from '../../mixins/formatDateTimeMixin'
import ProfileBlock from './ProfileBlock'

export default {
    props: ['amount'],
    components: {
        ProfileBlock
    },
    computed: {
        number(){
            return String(this.amount.amount).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
        }
    },
    mixins: [FormatDateTimeMixin]
}
</script>