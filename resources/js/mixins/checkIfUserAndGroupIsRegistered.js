export default{
    methods: {
        async checkAccess(){
            await this.getUserProfile();
            this.getGroupId();
            let sessionValue = this.checkSession();
            if(sessionValue.lineId === this.userInfo.userId && sessionValue.groupId === this.groupId){
                alert('session あるよ')
                this.hideLoading();
                return
            }else{
                alert('session ないよ')
                await this.checkIfUserAndGroupRegistered();
            }
        },
    }
}