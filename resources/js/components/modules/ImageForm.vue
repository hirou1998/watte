<template>
    <div class="form-group">
        <p class="normal-txt">イベント画像</p>
        <div class="">
            <input 
                @change="imageUploaded"
                type="file" 
                name="image" 
                accept="image/png, image/jpeg" 
                class="form-control"
            >
        </div>
        <img :src="picture.preview" class="image-form-preview">
    </div>
</template>

<script>
import { getDataUrlFromFile } from 'browser-image-compression'
import imageUtilityMixin from '../../mixins/imageUtilityMixin'

export default {
    props: ['picture'],
    data(){
        return {
            image: '',
            preview: '',
        }
    },
    methods: {
        checkImage(image){
            if(!image){
                return false
            }
            if(!image.type.match(/image\/(jpeg|png)/u)){
                return false
            }
            if(image.size > this.sizeLimitation){
                return false
            }
            return true
        },
        async imageUploaded(e){
            const image = e.target.files[0] || e.dataTransfer.files[0]
            let checkResult = this.checkImage(image);
            if(checkResult){
                try{
                    const compressedImg = await this.getCompressImageFileAsync(image);
                    this.image = compressedImg;
                    this.preview = await this.getDataUrlFromImage(compressedImg);
                    this.$emit('uploaded', {
                        preview: this.preview,
                        file: this.image
                    })
                }catch (err){
                    console.log(err)
                }
            }
        },
        resizeImageold(image){
            let img = new Image();
            let reader = new FileReader();
            reader.readAsDataURL(image)
            reader.onload = (e) => {
                img.src = e.target.result
                img.onload = () => {
                    if(img.width > this.maxWith){
                        img.height = Math.round(img.height * this.maxWith / width)
                        img.width = this.maxWith
                    }
                    let canvas = document.createElement('canvas')
                    canvas.width = img.width
                    canvas.height = img.height
                    let ctx = canvas.getContext('2d')
                    ctx.drawImage(img, 0, 0, img.width, img.height)
                    let base64 = canvas.toDataURL('image/jpeg')
                    let bin = atob(base64.split('base64,')[1])
                    let len = bin.length
                    let barr = new Uint8Array(len)
                    let i = 0
                    while(i < len){
                        barr[i] = bin.charCodeAt(i)
                        i++
                    }
                    let blob = new Blob([barr], {type: 'image/jpeg'});
                    this.base64Data = base64
                    this.$emit('uploaded', {
                        preview: base64,
                        file: blob
                    })
                }
            }     
        },
    },
    mixins: [imageUtilityMixin]
}
</script>