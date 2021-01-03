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
        async checkAccess(){
            await this.getUserProfile();
            this.getGroupId();
        },
        async getUserProfile(){
            await window.liff.getProfile()
                    .then(profile => {
                        this.userInfo = profile;
                        //this.isRegisteredUser();
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
                this.hideLoading();
            }else{
                alert('403: Forbiddend\nWatteを利用されるグループトーク内でアクセスしてください。');
                location.href = `${this.deployUrl}/err/forbidden`;
                window.liff.closeWindow();
            }
        },
        hideLoading(){
            this.isLoading = false;
        }
    }
}