<template>
    <section class="option-window js-slide-up-down" :data-visibility="visibility">
        <slot></slot>
    </section>
</template>

<script>
export default {
    props: ['visibility'],
}
</script>