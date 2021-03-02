<template>
    <div class="profile-block">
        <img 
            class="profile-icon"
            :src="user.picture_url" 
            :alt="user.display_name"
            @error="setDefault"
            :style="{width: iconSize + 'px', height: iconSize + 'px'}"
        >
        <p class="profile-name" :style="{fontSize: nameFontSize}">{{user.display_name}}</p>
    </div>
</template>

<script>
export default {
    props: ['user', 'iconSize', 'nameSize'],
    computed: {
        nameFontSize(){
            return this.nameSize ? this.nameSize :  '1.6rem';
        }
    },
    methods: {
        setDefault(event){
            event.target.src = '/images/watte-icon.png'
        }
    }
}
</script>