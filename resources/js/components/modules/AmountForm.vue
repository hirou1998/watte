<template>
    <section>
        <div class="form-group">
            <p class="normal-txt">支払い者</p>
            <select class="form-control" v-model="user.userId">
                <option :value="participant.line_id" v-for="participant in participants" :key="participant.line_id">{{participant.display_name}}</option>
            </select>
        </div>
        <div class="form-group">
            <p class="normal-txt">金額</p>
            <input type="number" v-model="amount" class="form-control">円
        </div>
        <div class="form-group">
            <p class="normal-txt">メモ</p>
            <input type="text" v-model="memo" class="form-control" placeholder="何に使いましたか？">
        </div>
    </section>
</template>

<script>
export default {
    props: ['formItem', 'participants'],
    model: {
        prop: 'formItem',
        event: 'change'
    },
    computed: {
        user: {
            get(){
                return this.formItem.user;
            },
            set(user){
                this.updateUser(user);
            }
        },
        amount: {
            get(){
                return this.formItem.amount;
            },
            set(amount){
                this.updateValue({amount});
            }
        },
        note: {
            get(){
                return this.formItem.note;
            },
            set(note){
                this.updateValue({note});
            }
        }
    },
    methods: {
        updateUser(obj){
            this.$emit('change', 
                {
                    ...this.formItem,
                    user: {
                        ...user
                    }
                }
            )
        },
        updateValue(value){
            this.$emit('change', {
                ...this.formItem,
                ...value
            })
        }
    }
}
</script>