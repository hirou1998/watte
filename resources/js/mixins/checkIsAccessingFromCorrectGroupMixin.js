export default{
    methods: {
        async checkAccess(){
            await this.getUserProfile();
            this.getGroupId();
            let sessionValue = this.checkSession();
            if(sessionValue.lineId === this.userInfo.userId && sessionValue.groupId === this.groupId){
                if(sessionValue.groupId === this.event.group_id){
                    this.hideLoading();
                    return
                }else{
                    this.checkIsGroupIdCorrect();
                }
            }else{
                await this.checkIfUserAndGroupRegistered();
                await this.checkIsGroupIdCorrect();
            }
        },
        async checkIfUserAndGroupRegistered(){
            await axios.post('/auth/user-and-group', {
                lineId: this.userInfo.userId,
                groupId: this.groupId
            })
            .then(() => {
                
            })
            .catch((err) => {
                if(String(err).indexOf('401') !== -1){
                    alert("401: Unauthorized\nWatteアカウントが参加しているグループ内でアクセスしてください。");
                    location.href = `${this.deployUrl}/err/forbidden`;
                }else{
                    alert("500: Server Error\n予期せぬエラーが発生しました。");
                    location.href = `${this.deployUrl}/err/servererror`;
                }
                window.liff.closeWindow(); //lineからのアクセス対策
            })
        },
        async checkIsGroupIdCorrect(){
            await axios.post('/auth/event-group', {
                    'groupId': this.groupId,
                    'eventId': this.event.id || this.eventId,
                    'lineId': this.userInfo.userId,
                })
                .then(() => {
                    this.hideLoading();
                })
                .catch((err) => {
                    if(String(err).indexOf('401') !== -1){
                        alert("401: Unauthorized\nグループ外のデータにはアクセスできません。");
                        location.href = `${this.deployUrl}/err/forbidden`;
                    }else{
                        alert("500: Server Error\n予期せぬエラーが発生しました。");
                        location.href = `${this.deployUrl}/err/servererror`;
                    }
                    window.liff.closeWindow(); //lineからのアクセス対策
                })
        },
    }
}