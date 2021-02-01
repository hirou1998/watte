import imageCompression, { getDataUrlFromFile } from 'browser-image-compression'

export default{
    methods: {
        async getCompressImageFileAsync(image){
            const options = {
                maxSizeMB: 0.75,
                maxWidthOrHeight: 400
            };
            try{
                return await imageCompression(image, options);
            }catch (err){
                console.log(err);
                return false;
            }
        },
        async getDataUrlFromImage(image){
            try{
                return await imageCompression.getDataUrlFromFile(image)
            }catch (err){
                console.log(err)
                return false;
            }
        }
    }
}