<template>
    <section class="modal-base" @click="close">
        <div class="modal-inner">
            <slot></slot>
        </div>
    </section>
</template>

<script>
export default {
    methods: {
        close(){
            this.$emit('close');
        }
    }
}
</script>