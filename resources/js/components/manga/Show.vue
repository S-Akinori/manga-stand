<template>
<div>
    <div :class="{hidden: isHidden}" class="header fixed top-0 w-full h-12 flex aitems-center">
        <button @click="back" class="btn ml-2"><i class="fas fa-arrow-left"></i></button>
        <h2 class="mx-auto flex items-center">{{manga.title}}</h2>
    </div>
    <swiper class="swiper manga-reader my-5" :options="swiperOption" dir="rtl">
        <swiper-slide v-for="(mangaFile, index) in mangaFiles" :key="index">
            <img @click.self="toggleScrollbar" :src="'/' + mangaFile" alt="" class="swiper-lazy">
        </swiper-slide>
        <swiper-slide @click.self="toggleScrollbar" class="flex justify-center items-center">
            <router-link :to="'/comment/' + manga.id">コメント</router-link>
            <button @click="next(mangaNext.id)"  class="btn btn-secondary" :disabled="disabled">続きを読む</button>
        </swiper-slide>

        <div class="swiper-scrollbar" v-bind:class="{hidden: isHidden}" slot="scrollbar"></div>
        <div class="swiper-pagination swiper-pagination-fraction" ref="pagination" v-bind:class="{hidden: isHidden}" slot="pagination"></div>
        <div class="swiper-button-prev" slot="button-prev">
            <div class="swiper-button-icon" v-bind:class="{hidden: isHidden}"><i class="fas fa-chevron-right fa-2x"></i></div>
        </div>
        <div class="swiper-button-next" slot="button-next">
            <div class="swiper-button-icon" v-bind:class="{hidden: isHidden }"><i class="fas fa-chevron-left fa-2x"></i></div>
        </div>
</swiper>
</div>
</template>

<script>
export default {
    data() {
        return {
            manga: {},
            mangaNext: {},
            mangaFiles: [],
            user: {},
            disabled: false,
            isHidden: true,
            swiperOption: {
                lazy: true,
                pagination: {
                    el: '.swiper-pagination',
                    type: 'fraction',
                    // hideOnClick: true,
                },
                scrollbar: {
                    el: '.swiper-scrollbar',
                    draggable: true,
                    // hideOnClick: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                    // hideOnClick: true,
                }
            }
        };
    },
    mounted() {
        axios.get("/api/user").then(Response => {
            this.user = Response.data;
        });
        axios.get('/api/manga/' + this.$route.params.id).then(res => {
            console.log(res);
            this.manga = res.data.manga;
            this.mangaNext = res.data.mangaNext;
            this.mangaFiles = res.data.files;
            var minPoint = 10;
            if(this.user.point < minPoint) {
                this.disabled = true;
            }
        }).catch(error => {
            console.log(error);
        });
    },
    methods: {
        next(id) {
            var path = '/manga/' + id;
            console.log(path);
            this.$router.push(path);
            this.$router.go({path: path});
        },
        back() {
            this.$router.back();
        },
        toggleScrollbar() {
            this.isHidden = !this.isHidden;
        }
    }
}
</script>

<style lang="scss" scoped>
.manga-reader {
    width: 100%;
    height: auto;
    margin: 90px auto;
    padding: 32px 0;
    img {
        width: 100%;
        height: auto;
    }
    @media screen and (min-width: 576px) {
        width: 300px;
    }
    
}

.hidden {
    display: none !important;
}
.swiper-button-next, .swiper-button-prev {
    height: 100%;
    width: 40px;
    top: 15px;
    .swiper-button-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        background: #333;
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
}
.swiper-button-next:after {
    content: '' !important;
}
.swiper-button-prev:after {
    content: '' !important;
}
</style>