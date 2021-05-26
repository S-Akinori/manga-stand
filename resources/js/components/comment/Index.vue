<template>
    <div class="container">
        <div v-for="comment in comments" :key="comment.id">
            <p>{{comment.name}}</p>
            <p>{{comment.content}}</p>
        </div>
        <router-link :to="'/comment/create/' + $route.params.storyId" class="btn btn-warning rounded-full"><i class="far fa-comment"></i></router-link>
    </div>
</template>

<script>
export default {
    data() {
        return {
            comments: [],
        }
    },
    mounted() {
        axios.get('/api' + this.$route.path).then(res => {
            this.comments = res.data;
        })
    }
}
</script>