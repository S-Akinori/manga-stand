<template>
<div>
    <div class="flex items-center">
        <div class="w-32"><img :src="mangaVolume.image_path" alt=""></div>
        <div class="ml-8">
            <p>{{mangaVolume.description}}</p>
        </div>
    </div>
    <router-link to="/manga/create">Add New Story</router-link>
</div>
</template>

<script>
export default {
    data() {
        return {
            mangaVolume: '',
        };
    },
    mounted() {
        axios('/api/manga-volume/' + this.$route.params.id).then( res => {
            console.log(res);
            this.mangaVolume = res.data;
        }).catch( error => {
            console.log(error);
        })
    }
}
</script>