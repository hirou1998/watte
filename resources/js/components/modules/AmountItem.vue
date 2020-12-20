<template>
    <li class="amount-item">
        <div class="amount-head-container">
            <img 
                class="amount-payer-icon"
                :src="amount.line_friend.picture_url" 
                :alt="amount.line_friend.display_name"
            >
            <p class="normal-txt amount-payer">{{amount.line_friend.display_name}}</p>
            <p class="big-txt amount-number">{{amount.amount}}<span class="txt-smaller">円</span></p>
        </div>
        <div>
            <p class="normal-txt amount-memo">{{amount.note}}</p>
        </div>
    </li>
</template>

<script>
export default {
    props: ['amount']
}
</script>