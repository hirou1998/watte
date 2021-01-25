<template>
    <transition name="slide">
        <section class="option-window" v-show="visibility">
            <slot></slot>
        </section>
    </transition>
</template>

<script>
export default {
    props: ['visibility']
}
</script>