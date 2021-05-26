<template>
<div class="container">
    <div v-if="isVerified" class="container">
        <p>メール認証されました。以下のリンクからログインしてください。</p>
        <router-link to="/login" class="btn btn-primary">ログイン画面</router-link>
    </div>

    <div v-else>
        <p>認証に失敗しました。再度認証する場合は以下のボタンから新たにメールを受け取ってください。</p>
        <button @click="resend">メールを受け取る</button>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            isVerified: false,
            user: {}
        }
    },
    mounted() {
        axios.get('/api/user/verify/' + this.$route.params.token).then(res => {
            if(res.data.isVerified) {
                this.isVerified = res.data.isVerified;
            }
        })
    },
    methods: {
        resend() {
            axios.post('/api/user/verify/resend', {
                _token: this.csrfToken,
                id: this.user.id
            }).then(res => {
                console.log(res);
            }).catch(error => {
                console.log(error);
            })
        }
    }
}
</script>