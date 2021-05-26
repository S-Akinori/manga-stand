<template>
    <div class="container">
        <div class="manga-container">
            <h2>お気に入りの漫画</h2>
            <div class="row">
                <div class="col-4" v-for="(manga, index) in mangas" :key="manga.id">
                    <router-link :to="'/manga-package/' + manga.id">
                        <img :src="manga.package_img_path" :alt="manga.title">
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            mangas: [],
        }
    },
    mounted() {
        axios.get('/api/user').then(res => {
            axios.get('/api/manga-package/favorite/' + res.data.id).then(res => {
                console.log(res);
                this.mangas = res.data;
            }).catch(error => {
                console.log(error);
            })
        })
    }
}
</script>