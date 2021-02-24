<template>
    <transition name="fade">
        <section class="modal-base" v-if="visibility">
            <option-window :visibility="visibility">
                <template v-slot:default>
                    <ul class="option-block">
                        <li class="option-button" @click="requestPayment">割り勘代の支払いリクエスト</li>
                        <li class="option-button" @click="settlePayment">割り勘代を支払う</li>
                    </ul>
                    <div class="option-block option-button" data-type="cancel" @click="close">取消</div>
                </template>
            </option-window>
        </section>
    </transition>
</template>

<script>
import ModalBase from './ModalBase'
import OptionWindow from './OptionWindow'

export default {
    components: {
        ModalBase,
        OptionWindow
    },
    props: ['visibility', 'target'],
    data(){
        return{

        }
    },
    methods: {
        close(){
            this.$emit('close')
        },
        requestPayment(){
            this.$emit('request', 'request')
        },
        settlePayment(){
            this.$emit('settle', 'settle')
        }
    }
}
</script>