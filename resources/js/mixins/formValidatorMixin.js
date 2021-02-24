export default{
    methods: {
        isTextFilled(field){
            return field !== '' && String(field).match(/\S/g) ? true : false;
        },
        ValidateNumber(field){
            let numberValue = String(field).replace(/\D/g, '');
            return numberValue !== '' ? true : false;
        },
        validateDecimal(value){
            let result = String(value).match(/^(\d+)(\.\d{0,2})?/u);
            return result ? Number(result[0]) : false;
        },
    }
}