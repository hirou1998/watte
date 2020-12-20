export default{
    data: function(){
        return{
            userInfo: {}
        }
    },
    methods: {
        getUserProfile(){
            window.liff.getProfile()
            .then(profile => {
                this.userInfo = profile;
            })
            .catch(e => {
                alert('ユーザー情報の取得に失敗しました');
            })
        },
    }
}