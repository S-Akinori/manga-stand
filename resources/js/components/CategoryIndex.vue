<template>
    <div class="container">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" id="categoryButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{selectedCategory}}
            </button>
            <div class="dropdown-menu" area-labelledby="categoryButton">
                <button class="btn dropdown-item" @click="getCategory('all')">全て</button>
                <button class="btn dropdown-item" v-for="(category, index) in categories" :key="index" @click="getCategory(category)">
                    {{category}}
                </button>
            </div>
        </div>
        <div class="manga-container">
            <div class="row">
                <div class="col-4 py-1" v-for="manga in mangas" :key="manga.id">
                    <router-link :to="'/manga-package/' + manga.id">
                        <p><img :src="manga.package_img_path" :alt="manga.title"></p>
                        <p>{{manga.title}}</p>
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
            categories: ['女性漫画', '少女漫画', '青年漫画', '少年漫画' ,'バトル', 'アクション', '恋愛', 'ギャグ', 'ミステリー', '歴史', 'スポーツ', 'ホラー', 'SF', 'グルメ'],
            mangas: [],
            selectedCategory: '',
            category: '',
        }
    },
    mounted() {
        var category = 'all';
        if(this.$route.params.category) {
           category = this.$route.params.category
        }
        this.getCategory(category);
    },
    watch: {
        $route(to, from) {
            this.getCategory(this.$route,params.category)
        }
    },
    methods: {
        getCategory(category) {
            axios.get('/api/manga-package/category/' + category).then(res => {
                // console.log(res);
                this.mangas = res.data;
                if(category == 'all') {
                    this.selectedCategory = 'すべて';
                } else {
                    this.selectedCategory = category
                }
            }).catch(error => {
                console.log(error);
            })
        }
    }
}
</script>