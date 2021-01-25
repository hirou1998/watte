export default{
    props: ['liff', 'deployUrl'],
    data: function(){
        return{
            userInfo: {},
            groupId: '',
            isLoading: true,
        }
    },
    methods: {
        async checkIfUserAndGroupRegistered(){
            await axios.post('/auth/user-and-group', {
                lineId: this.userInfo.userId,
                groupId: this.groupId
            })
            .then(() => {
                this.hideLoading();
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
        checkSession(){
            let sessionLineId = document.querySelector('meta[name="line-id"]').getAttribute('content') ?? '';
            let sessionGroupId = document.querySelector('meta[name="group-id"]').getAttribute('content') ?? '';
            return {
                lineId: sessionLineId,
                groupId: sessionGroupId
            }
        },
        async getUserProfile(){
            await window.liff.getProfile()
                    .then(profile => {
                        this.userInfo = profile;
                    })
                    .catch(e => {
                        alert("403: Forbidden\nスマートフォンのLINEアプリからアクセスしてください。");
                        location.href = `${this.deployUrl}/err/forbidden`;
                        window.liff.closeWindow(); //lineからのアクセス対策
                    })
        },
        getGroupId(){
            let context = window.liff.getContext()
            if(context.type === 'group'){
                this.groupId = context.groupId
            }else{
                alert('403: Forbiddend\nWatteを利用されるグループトーク内でアクセスしてください。');
                location.href = `${this.deployUrl}/err/forbidden`;
                window.liff.closeWindow();
            }
        },
        hideLoading(){
            this.isLoading = false;
        },
    }
}