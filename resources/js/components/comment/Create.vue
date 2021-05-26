<template>
    <div class="container">
        <form @submit.prevent="store">
            <label for="content">コメント</label>
            <textarea name="content" id="content" class="form-control" rows="5" v-model="content"></textarea>
            <button class="btn btn-primary" :disabled="disabled">投稿</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            content: '',
            storyId: this.$route.params.storyId,
            disabled: false,
        }
    },
    methods: {
        store() {
            this.disabled = true;
            axios.post('/api/comment', {
                content: this.content,
                story_id: this.storyId,
            }).then(res => {
                console.log(res);
                this.$router.push('/comment/' + this.storyId);
            }).catch(error => {
                console.log(error);
                this.disabled = false;
            });
        }
    }
}
</script>