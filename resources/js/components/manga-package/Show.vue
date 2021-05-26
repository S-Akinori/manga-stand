<template>
    <div class="container">
        <div class="row items-center mb-3 manga-data">
            <div class="col-4"><img :src="mangaPackage.package_img_path" :alt="mangaPackage.title"></div>
            <div class="col-8">
                <h2 class="mb-3">{{mangaPackage.title}}</h2>
                <p class="mb-3">{{mangaPackage.description}}</p>
                <router-link :to="'/home/' + author.username" class="font-bold">作者: {{author.name}}</router-link>
            </div>
        </div>

        <div class="flex items-center justify-end mb-3">
            <div v-if="user.id == mangaPackage.user_id">
                <router-link :to="'/manga-volume/' + mangaPackage.id + '/create'" class="btn btn-primary">新刊を作る</router-link>
                <router-link :to="'/manga/' + mangaPackage.id + '/create/'" class="btn btn-secondary">最新話を作成</router-link>
                <router-link :to="'/manga-package/' + mangaPackage.id + '/edit/'" class="btn btn-success">編集</router-link>
            </div>
            <div class="mx-3">
                <button @click="setFavorite"><i :class="favClass"></i></button> {{mangaPackage.favorites}}
            </div>
        </div>

        <div>
            <div class="mb-1">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" id="volumeMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Vol.{{vol}}
                    </button>
                    <div class="dropdown-menu" area-labelledby="volumeMenuButton">
                        <button class="btn dropdown-item" v-for="(mangaVolume, index) in mangaVolumes" :key="mangaVolume.id" @click="getStories(mangaVolume.id, index)">
                            Vol.{{mangaVolume.volume}}
                        </button>
                    </div>
                </div>
            </div>

            <div class="list-group">
                <button class="list-group-item" v-for="(item, index) in manga" :key="item.id" data-toggle="modal" data-target="#confirmation" @click="setLink(item.id)">
                    {{item.title}} <router-link :to="'/comment/' + item.id"><i class="far fa-comments"></i></router-link>
                </button>
            </div>
        </div>

        <div class="modal fade" id="confirmation" tabindex="-1" aria-labelledby="confirmation" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <p class="text-center">ポイント {{user.point}}</p>
                        <div>
                            <button class="btn btn-secondary block m-auto" @click="toLink" data-dismiss="modal" :disabled="disabled">ポイントを使って読む 10P</button>
                            <button class="btn block m-auto" data-dismiss="modal">キャンセル</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            mangaPackage: [],
            mangaVolumes: [],
            manga: [],
            vol: '1',
            favClass: '',
            user: '',
            author: '',
            isFavorite: false,
            disabled: false,
            linkId: ''
        }
    },
    mounted() {
        axios.get("/api/user").then(Response => {
            this.user = Response.data;
        });
        axios.get('/api/manga-package/' + this.$route.params.id).then(res => {
            console.log(res);
            this.mangaPackage = res.data.package;
            this.mangaVolumes = res.data.volumes;
            this.manga = res.data.manga;
            this.isFavorite = res.data.isFavorite;
            this.author = res.data.author;
            if(this.isFavorite) {
                this.favClass = 'fas fa-heart';
            } else {
                this.favClass = 'far fa-heart';
            }

            var minPoint = 10;

            if(this.user.point < minPoint) {
                this.disabled = true;
            }
        }).catch(error => {
            console.log(error);
        })
    },
    methods: {
        getStories(volumeId, index) {
            axios.get('/api/manga/' + volumeId + '/index').then(res => {
                console.log(res);
                this.manga = res.data;
                this.vol = this.mangaVolumes[index].volume;
            }).catch(error => {
                console.log(error);
            })
        },
        setFavorite() {
            if(!this.isFavorite) {
                axios.post('/api/manga-package/' + this.mangaPackage.id + '/favorite', {
                    package_id: this.mangaPackage.id
                }).then(res => {
                    console.log(res);
                    this.isFavorite = true;
                    this.favClass = 'fas fa-heart';
                }).error(error => {
                    console.log(error);
                });
            } else {
                axios.delete('/api/manga-package/' + this.mangaPackage.id + '/favorite', {
                package_id: this.mangaPackage.id
                }).then(res => {
                    console.log(res);
                    this.isFavorite = false;
                    this.favClass = 'far fa-heart'
                }).error(error => {
                    console.log(error);
                });
            }
        },
        setLink(id) {
            this.linkId = id;
        },
        toLink(id) {
            var path = '/manga/' + this.linkId;
            this.$router.push(path);
        }
    }
}
</script>

<style lang="scss" scoped>
    header {
        display: none;
    }

    h2 {
        font-weight: bold;
        font-size: 1.3rem;
    }
</style>