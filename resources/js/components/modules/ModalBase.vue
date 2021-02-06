<template>
    <transition name="fade">
        <section class="modal-base" @click="close" v-if="visibility">
            <div id="modal-inner" class="modal-inner js-slide-up-down" :data-visibility="visibility === true ? 'true' : 'false'" @click.stop>
                <div class="modal-inner-content">
                    <slot name="content"></slot>
                </div>
                <div class="modal-inner-button">
                    <slot name="button"></slot>
                </div>
            </div>
        </section>
    </transition>
</template>

<script>
export default {
    props: ['visibility'],
    methods: {
        close(){
            document.getElementById('modal-inner').dataset.visibility = 'false';
            this.$emit('close');
        }
    }
}
</script>