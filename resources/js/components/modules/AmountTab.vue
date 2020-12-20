<template>
    <li 
        class="amount-tab normal-txt"
        :class="[selected == this.id ? 'selected' : 'non-selected']"
        @click="select"
    >{{value}}</li>
</template>

<script>
export default {
    props: ['value', 'id', 'selected'],
    methods: {
        select(){
            this.$emit('select', this.id);
        }
    }
}
</script>