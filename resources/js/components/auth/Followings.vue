<template>
    <div class="container">
        <div class="flex items-center" v-for="following in followings" :key="following.id">
            <router-link :to="'/home/' + following.username" class="mr-3">
                <img :src="following.img_path" alt="" class="w-20 h-20 rounded-full">
            </router-link>
            <router-link :to="'/home/' + following.username">
                {{following.name}}
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            followings: [],
        }
    },
    mounted() {
        axios.get('/api/followings/' + this.$route.params.followingId).then(res => {
            this.followings = res.data;
        });
    }
}
</script>