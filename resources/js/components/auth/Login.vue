<template>
    <div class="container">
        <div v-if="!isVerified">
            <p>このアカウントがまだ認証されていません。以下のボタンからメールを受けとり、アカウント認証を完了させてください。</p>
            <button @click="resend" class="btn btn-primary">認証メールを受け取る</button>
        </div>
        <form @submit.prevent="login">
            <div class="form-group">
                <label for="email">email</label>
                <input type="text" id="email" class="form-control" v-model="email">
                <p v-if="errors.email">{{errors.email[0]}}</p>
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" class="form-control" v-model="password">
                <p v-if="errors.password">{{errors.password[0]}}</p>
            </div>
            <button class="btn btn-primary">ログイン</button>
        </form>

        <router-link to="/register" class="btn btn-secondary">Register</router-link>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: "",
            errors: [],
            user: {},
            isVerified: true
        };
    },
    methods: {
        login() {
            axios.get("/sanctum/csrf-cookie").then(response => {
                axios.post("/api/login", {
                    email: this.email,
                    password: this.password
                })
                .then(res => {
                    console.log(res);
                    if(res.data.email_verified_at) {
                        localStorage.setItem("auth", "true");
                        this.$router.push("/");
                    } else {
                        this.user = res.data;
                        this.isVerified = false;
                    }
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
            });
        },
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
};
</script>