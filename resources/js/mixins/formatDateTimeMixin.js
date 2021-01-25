export default{
    methods: {
        dateParser(date){
            let year = date.substring(0, 4);
            let month = date.substring(5, 7);
            let day = date.substring(8, 10);
            let hour = Number(date.substring(11, 13));
            let min = date.substring(14, 16);
            let dateValue = new Date(year, month - 1, day, hour + 9, min);
            year = dateValue.getFullYear();
            month = dateValue.getMonth() + 1;
            day = dateValue.getDate();
            hour = dateValue.getHours();
            min = dateValue.getMinutes();
            return `${year}/${this.makeNumberDoubleDigits(month)}/${this.makeNumberDoubleDigits(day)} ${this.makeNumberDoubleDigits(hour)}:${this.makeNumberDoubleDigits(min)}`;
        },
        makeNumberDoubleDigits(num){
            return String(num).length === 1 ? '0' + num : num
        }
    }
}