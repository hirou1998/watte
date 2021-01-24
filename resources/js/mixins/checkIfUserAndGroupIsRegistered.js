export default{
    methods: {
        async checkAccess(){
            await this.getUserProfile();
            this.getGroupId();
            let sessionValue = this.checkSession();
            if(sessionValue.lineId === this.userInfo.userId && sessionValue.groupId === this.groupId){
                this.hideLoading();
                return
            }else{
                await this.checkIfUserAndGroupRegistered();
            }
        },
    }
}