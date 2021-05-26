<template>
    <div class="container">
        <swiper class="swiper news" :options="swiperOption">
            <swiper-slide class="flex justify-center items-center"><router-link to="/">ニュース1</router-link></swiper-slide>
            <swiper-slide class="flex justify-center items-center"><router-link to="/">ニュース2</router-link></swiper-slide>
            <swiper-slide class="flex justify-center items-center"><router-link to="/">ニュース3</router-link></swiper-slide>
            <swiper-slide class="flex justify-center items-center"><router-link to="/">ニュース4</router-link></swiper-slide>
            <div class="swiper-pagination" slot="pagination"></div>
        </swiper>

        <section class="manga-container">
            <h2>新着マンガ</h2>
            <div class="row">
                <div class="col-4 py-1" v-for="manga in mangas.latest" :key="manga.id">
                    <router-link :to="'/manga-package/' + manga.id">
                        <p><img :src="manga.package_img_path" :alt="manga.title"></p>
                        <p>{{manga.title}}</p>
                    </router-link>
                </div>
            </div>
        </section>

        <section class="manga-container">
            <h2>人気のマンガ</h2>
            <div class="row">
                <div class="col-4 py-1" v-for="manga in mangas.favorite" :key="manga.id">
                    <router-link :to="'/manga-package/' + manga.id">
                        <p><img :src="manga.package_img_path" :alt="manga.title"></p>
                        <p>{{manga.title}}</p>
                    </router-link>
                </div>
            </div>
        </section>

        <section class="manga-container">
            <h2>青年漫画</h2>
            <div class="row">
                <div class="col-4 py-1" v-for="manga in mangas.category" :key="manga.id">
                    <router-link :to="'/manga-package/' + manga.id">
                        <p><img :src="manga.package_img_path" :alt="manga.title"></p>
                        <p>{{manga.title}}</p>
                    </router-link>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {
            swiperOption: {
                loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.swiper-pagination'
                },
            },
            mangas: {},
            latestMangas: [],
            popularMangas: [],
            startScrollYOffset: 0,
            apiData: [
                {api: 'latest', key: ''},
                {api: 'favorite', key: ''},
                {api: 'category', key: '青年漫画'},
            ],
            apiNum: 0
        };
    },
    mounted() {
        window.onscroll = () => {
            var htmlHeight = document.documentElement.offsetHeight;
            var windowHeight = window.innerHeight;
            var topPos = document.documentElement.scrollTop
            var bottomPos = htmlHeight - windowHeight - topPos;

            var triggerPos = 300;

            if(bottomPos < triggerPos && this.apiNum < this.apiData.length) {
                this.getManga(this.apiData[this.apiNum].api, this.apiData[this.apiNum].key);
                this.apiNum++;
            }
        }
        axios.get('/api/manga-package/latest').then(res => {
            // console.log(res);
            this.$set(this.mangas, 'latest', res.data);
            this.apiNum++;
            axios.get('/api/manga-package/favorite/').then(res => {
                // console.log(res);
                this.$set(this.mangas, 'favorite', res.data);
                this.apiNum++;
            });
        });
        this.startScrollYOffset = Math.floor(window.innerHeight / 3);
    },
    methods: {
        getManga(api, key) {
            axios.get('/api/manga-package/' + api + '/' + key).then(res => {
                // console.log(res);
                this.$set(this.mangas, api, res.data);
            });
        }
    }
};

</script>