<template>
    <div class="profile-block">
        <img 
            class="profile-icon"
            :src="user.picture_url" 
            :alt="user.display_name"
            @error="setDefault"
        >
        <p class="normal-txt profile-name">{{user.display_name}}</p>
    </div>
</template>

<script>
export default {
    props: ['user'],
    methods: {
        setDefault(event){
            event.target.src = '/images/watte-icon.png'
        }
    }
}
</script>