<template>
    <div class="container">
        <div>
            <h2>漫画</h2>
            <div class="row">
                <div class="col-4" v-for="manga in mangas" :key="manga.id">
                    <router-link :to="'/manga-package/' + manga.id">
                        <img :src="manga.package_img_path" :alt="manga.title">
                    </router-link>
                </div>
            </div>
        </div>

        <h2>ユーザー</h2>
        <div class="row">
            <div class="col-4 flex items-center" v-for="user in users" :key="user.id">
                <router-link :to="'/home/' + user.username" class="mr-3">
                    <img :src="user.img_path" alt="" class="w-20 h-20 rounded-full">
                </router-link>
                <router-link :to="'/home/' + user.username">
                    {{user.name}}
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            mangas: [],
            users: []
        }
    },
    mounted() {
        var keyword = this.$route.params.keyword;
        axios.get('/api/manga-package/title/' + keyword).then(res => {
            console.log(res);
            this.mangas = res.data;
            axios.get('/api/user/name/' + keyword).then(res => {
                this.users = res.data;
            })
            // axios.get('/api/manga-package/author/' + keyword).then(res => {
            //     this.mangas.push(res.data);
            //     axios.get('/api/user/name/' + keyword).then(res => {
            //         this.user = res.data;
            //     })
            // });
        })
    }
}
</script>