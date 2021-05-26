<template>
    <div class="container">
        <p v-if="errors.top" class="error">{{errors.top}}</p>
        <p v-if="message">{{message}}</p>

        <form @submit.prevent="register">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" v-model="name">
                <p class="error">{{errors.name}}</p>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" class="form-control" v-model="userName" pattern="[a-zA-Z1-9]+">
                <p class="error">{{errors.username}}</p>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" v-model="email">
                <p class="error">{{errors.email}}</p>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-model="password">
                <p class="error">{{errors.password}}</p>
            </div>
            <div class="form-group">
                <label for="passwordConfirmation">Confirm Password</label>
                <input type="password" id="passwordConfirmation" class="form-control" v-model="passwordConfirmation">
                <p class="error">{{errors.password_confirmation}}</p>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</template>

<script>

export default {
    data() {
        return {
            name: "",
            userName: "",
            email: "",
            password: "",
            passwordConfirmation: "",
            errors: {},
            message: '',
        }
    },
    methods: {
        register() {
            this.validation();
            
            for(var item in this.errors) {
                if(this.errors[item]) {
                    return;
                }
            }

            axios.post("/api/register", {
                _token: this.csrfToken,
                name: this.name,
                username: this.userName,
                email: this.email,
                password: this.password,
            }).then(response => {
                console.log(response);
                this.message = '認証メールを送信しました。メール内のURLから認証を完了させてください。'
                // this.$router.push("/login");
            }).catch(error => {
                console.log(error);
                this.$set(this.errors, error.data.name, error.data.message);
            })
        },
        validation() {
            if(!this.name) {
                this.$set(this.errors, "name", "Name required");
            } else if(this.name.length > 256) {
                this.$set(this.errors, "name", "Letters must be less than 256");
            }
            var check = /^[A-Za-z1-9]+$/;

            if(!this.userName) {
                this.$set(this.errors, "username", "UserName required");
            } else if(this.userName.length > 25) {
                this.$set(this.errors, "username", "Letters must be less than 25");
            } else if(!check.test(this.userName)) {
                this.$set(this.errors, "username", "Invalid username");
            }

            check = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            if(!this.email) {
                this.$set(this.errors, "email", "Email required");
            } else if(!check.test(this.email)) {
                this.$set(this.errors, "email", "Invalid email");
            }

            var min = 6;
            var max = 255

            check = /^[A-Za-z1-9]+$/;

            if(this.password.length < min) {
                this.$set(this.errors, "password", `Password must be at least ${min}`);
            } else if(this.password.length > max) {
                this.$set(this.errors, "password", `Password must not be greater than ${max}`);
            } else if(!check.test(this.password)) {
                this.$set(this.errors, "password", `Invalid Password`);
            } else if(this.password !== this.passwordConfirmation) {
                this.$set(this.errors, "password_confirmation", `Password Confirmation does not match`);
            }

        }
    }
}
</script>