<template>
    <div class="container">
        <div class="profile flex items-center">
            <div class="profile__image-container">
                <img :src="homeUser.img_path" alt="">
            </div>
            <div class="ml-3">
                <p class="profile__name">{{homeUser.name}}</p>
                <p class="profile__id">@{{homeUser.username}}</p>
            </div>
        </div>

        <div v-if="isMyPage">
            <div class="my-3">
                <router-link to="/manga-package/create" class="btn btn-primary">新しい漫画を作る</router-link>
                <!-- <router-link to="/manga-package/create" class="btn btn-info">Edit Profile</router-link> -->
            </div>
            <router-link to="/settings" class="btn btn-secondary">設定</router-link>
            <router-link :to="'/followings/' + homeUser.id" class="btn btn-secondary">フォロー中の漫画家</router-link>
        </div>

        <div v-else>
            <button class="btn" @click="follow"><i :class="followerIconClass"></i></button>
        </div>

        <div class="manga-container">
            <h2>漫画一覧</h2>
            <div class="row">
                <div class="col-4 py-1" v-for="manga in mangas" :key="manga.id">
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
            homeUser: "",
            user: "",
            mangas: [],
            isMyPage: false,
            hasFollowed: false,
            followerIconClass: ''
        };
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get('/api/user').then(res => {
                this.user = res.data;
                var username = this.$route.params.username;

                if(username) {
                    axios.get("/api/user/" + username).then(res => {
                        this.homeUser = res.data;
                        axios.get("/api/manga-package/author/" + username).then(res => {
                            this.mangas = res.data;
                            axios.get('/api/following/' + this.user.id + '/followed/' + this.homeUser.id).then(res => {
                                this.hasFollowed = res.data;
                                if(this.hasFollowed) {
                                    this.followerIconClass = 'fas fa-user-plus';
                                } else {
                                    this.followerIconClass = 'far fa-user';
                                }
                            })
                        }).catch(error => {
                            console.log(error);
                        });
                    }).catch(error => {
                        console.log(error);
                    });
                } else {
                    this.homeUser = this.user;
                    axios.get("/api/manga-package/author/" + this.homeUser.username).then(res => {
                        this.mangas = res.data;
                        this.isMyPage = true;
                    }).catch(error => {
                        console.log(error);
                    });
                }
            });
        },
        follow() {
            if(!this.hasFollowed) {
                axios.post('/api/following', {
                    'following_id': this.homeUser.id,
                    'followed_id': this.user.id,
                }).then(res => {
                    console.log(res);
                    this.hasFollowed = true;
                    this.followerIconClass = 'fas fa-user-plus';
                }).catch(error => {
                    console.log(error);
                })
            } else {
                axios.delete('/api/following/' + this.homeUser.id + '/followed/' + this.user.id).then(res => {
                    console.log(res);
                    this.hasFollowed = false;
                    this.followerIconClass = 'far fa-user';
                }).catch(error => {
                    console.log(error);
                })
            }
        }
    }
}
</script>