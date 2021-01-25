<template>
    <section class="modal-base">
        <option-window :visibility="visibility">
            <template v-slot:default>
                <ul class="option-block">
                    <li class="option-button">支払い内容を編集</li>
                    <template>
                        <li class="option-button" @click="archiveAmount" v-if="target.archive_flg == 0">精算済にする</li>
                        <li class="option-button" @click="unarchiveAmount" v-else>未精算に戻す</li>
                    </template>
                    <li class="option-button btn-danger" @click="deleteAmount">削除する</li>
                </ul>
                <div class="option-block option-button" data-type="cancel" @click="close">取消</div>
            </template>
        </option-window>
    </section>
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
        archiveAmount(){
            this.$emit('archive', '精算')
        },
        close(){
            this.$emit('close')
        },
        deleteAmount(){
            this.$emit('delete', '削除')
        },
        unarchiveAmount(){
            this.$emit('unarchive', '未精算')
        }
    }
}
</script>