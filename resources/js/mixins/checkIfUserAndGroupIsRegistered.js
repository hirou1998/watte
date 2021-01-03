export default{
    methods: {
        async checkIfUserAndGroupRegistered(){
            await axios.post('/auth/user-and-group', {
                lineId: this.userInfo.userId,
                groupId: this.groupId
            })
            .then(({data}) => {
                
            })
            .catch((err) => {

            })
        },
        checkSession(){
            let sessionLineId = document.querySelector('meta[name="line-id"]').getAttribute('content') ?? '';
            let sessionGroupId = document.querySelector('meta[name="group-id"]').getAttribute('content') ?? '';
            return {
                lineId: sessionLineId,
                groupId: sessionGroupId
            }
        }
    }
}