export default{
    methods: {
        getGroupId(){
            let context = window.liff.getContext()
            if(context.type === 'none'){ //contextがnoneの時はパラメータを確認する
                let param = location.search;
                let paramObj = this.makeObjectFromSearchParam(param)
                let paramGroupId = paramObj['group'];
                if(!paramGroupId){
                    alert('403: Forbiddend\nWatteを利用されるグループトーク内でアクセスしてください。');
                    location.href = `${this.deployUrl}/err/forbidden`;
                    window.liff.closeWindow();
                }
                this.groupId = paramGroupId;
            }else{
                if(context.type === 'group'){
                    this.groupId = context.groupId
                }else{
                    alert('403: Forbiddend\nWatteを利用されるグループトーク内でアクセスしてください。');
                    location.href = `${this.deployUrl}/err/forbidden`;
                    window.liff.closeWindow();
                }
            }
        },
        makeObjectFromSearchParam(param){
            param = param.substring(1);
            param = param.split('&');
            let paramObj = {};
            param.forEach(p => {
                let dividedParam = p.split('=');
                let paramKey = dividedParam[0];
                let paramValue = dividedParam[1];
                paramObj = {
                    [paramKey]: paramValue
                }
            });
            return paramObj;
        },
    }
}