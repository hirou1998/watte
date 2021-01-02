export default{
    methods: {
        dateParser(date){
            let year = date.substring(0, 4);
            let month = date.substring(5, 7);
            let day = date.substring(8, 10);
            let hour = Number(date.substring(11, 13)) + 9;
            let min = date.substring(14, 16);
            return `${year}/${month}/${day} ${hour}:${min}`;
        }
    }
}