export default{
    methods: {
        handleErr(status){
            if(status === 401){
                alert("401: Unauthorized\nアクセス権限がありません。");
                location.href = `${this.deployUrl}/err/unauthorized`;
            }else if(status === 403){
                alert("403: Forbidden\nアクセスが拒否されました。");
                location.href = `${this.deployUrl}/err/fobidden`;
            }else if(status === 404){
                alert("404: Not Found\nデータが見つかりませんでした。");
                location.href = `${this.deployUrl}/err/notfound`;
            }else if(status === 500){
                alert("500: Server Error\nデータベースに接続できません。");
                location.href = `${this.deployUrl}/err/servererror`;
            }else{
                alert("予期せぬエラーが発生しました。");
                location.href = `${this.deployUrl}/err/servererror`;
            }
            window.liff.closeWindow(); //lineからのアクセス対策
        }
    }
}