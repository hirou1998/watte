export default{
    methods: {
        isTextFilled(field){
            return field !== '' && field.match(/\S/g) ? true : false;
        },
        ValidateNumber(field){
            let numberValue = field.replace(/\D/g, '');
            return numberValue !== '' ? true : false;
        },
        validateDecimal(value){
            let result = value.match(/^(\d+)(\.\d{0,2})?/u);
            return result ? Number(result[0]) : false;
        },
    }
}