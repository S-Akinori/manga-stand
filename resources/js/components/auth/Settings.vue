<template>
    <div class="accordion" id="settings">
        <div class="card">
            <div class="card-header" id="heading1">
                <h2>
                    <button class="btn btn-block text-left" data-toggle="collapse" data-target="#profile" aria-expanded="true" aria-controls="profile">
                        プロフィール設定
                    </button>
                </h2>
            </div>
            <div id="profile" class="collapse" aria-labelledby="heading1" data-parent="#settings">
                <div class="card-body">
                    <form @submit.prevent="updateProfile">
                        <div class="form-group">
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
                                <label for="photo">プロフィール画像</label>
                                <input type="file" id="photo" class="form-control-file" accept=".jpg,.png" @change="setImage">
                                <div v-if="!isCropper" class="my-3">
                                    <img :src="imgSrc" alt="Profile Image" class="w-32 h-32 rounded-full">
                                </div>
                                <div v-else class="my-3">
                                    <vue-cropper 
                                            ref="cropper"
                                            :guides="true"
                                            :view-mode="2"
                                            drag-mode="crop"
                                            :auto-crop-area="0.5"
                                            :min-container-width="250"
                                            :min-container-height="250"
                                            :background="true"
                                            :rotatable="false"
                                            :src="imgSrc"
                                            :img-style="{ 'max-height': '200px'}"
                                            :aspect-ratio="1"
                                    >
                                    </vue-cropper>
                                </div>
                                
                                <p class="error">{{errors.photo}}</p>
                            </div>
                        </div>

                        <button class="btn btn-primary">更新</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading2">
                <h2>
                    <button class="btn btn-block text-left" data-toggle="collapse" data-target="#email" aria-expanded="true" aria-controls="email">
                        メールアドレス変更
                    </button>
                </h2>
            </div>
            <div id="email" class="collapse" aria-labelledby="heading2" data-parent="#settings">
                <div class="card-body">
                    <p class="text-success">{{messages.email}}</p>
                    <form @submit.prevent="updateEmail">
                        <div class="form-group">
                            <label for="currentEmail">Current Email</label>
                            <input type="email" id="currentEmail" class="form-control" v-model="currentEmail" readonly>
                        </div>
                        <div class="form-group">
                            <label for="newEmail">New Email</label>
                            <input type="email" id="newEmail" class="form-control" v-model="newEmail">
                            <p class="error">{{errors.email}}</p>
                        </div>
                        <button class="btn btn-primary">更新</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading3">
                <h2>
                    <button class="btn btn-block text-left" data-toggle="collapse" data-target="#password" aria-expanded="true" aria-controls="password">
                        パスワード変更
                    </button>
                </h2>
            </div>
            <div id="password" class="collapse" aria-labelledby="heading3" data-parent="#settings">
                <div class="card-body">
                    <form @submit.prevent="updatePassword">
                        <div class="form-group">
                            <label for="currentPassword">Current Password</label>
                            <input type="password" id="currentPassword" class="form-control" v-model="currentPassword">
                            <p class="error">{{errors.currentPassword}}</p>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                            <input type="password" id="newPassword" class="form-control" v-model="newPassword">
                            <p class="error">{{errors.newPassword}}</p>
                        </div>
                        <div class="form-group">
                            <label for="passwordConfirmation">Confirm Password</label>
                            <input type="password" id="passwordConfirmation" class="form-control" v-model="passwordConfirmation">
                            <p class="error">{{errors.passwordConfirmation}}</p>
                        </div>
                        <button class="btn btn-primary">更新</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="heading4">
                <h2>
                    <button class="btn btn-block text-left" @click="logout">
                        ログアウト
                    </button>
                </h2>
            </div>
        </div>
    </div>
</template>

<script>
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';

export default {
    components: {
        VueCropper
    },
    data() {
        return {
            user: {},
            name: "",
            userName: "",
            currentEmail: "",
            newEmail: '',
            currentPassword: "",
            newPassword: '',
            passwordConfirmation: "",
            filename: '',
            imgSrc: '',
            isCropper: false,
            errors: {},
            messages: {},
            disabled: false,
        }
    },
    mounted() {
        axios.get('/api/user').then(res => {
            this.user = res.data;
            this.name = this.user.name;
            this.userName = this.user.username;
            this.currentEmail = this.user.email;
            this.imgSrc = res.data.img_path;
        });
    },
    methods: {
        logout() {
            axios.post("/api/logout")
            .then(res => {
                console.log(res);
                localStorage.removeItem("auth");
                this.$router.push("/login");
            })
            .catch(error => {
                console.log(error);
            });
        },
        setImage(e) {
            var file = e.target.files[0];
            this.filename = file.name;
            if(!file.type.includes('image/')) {
                alert('画像ファイルを選択してください');
                return;
            }
            if(typeof FileReader === 'function') {
                var reader = new FileReader();
                reader.onload = event => {
                    this.imgSrc = event.target.result;
                    this.$refs.cropper.replace(event.target.result);
                };
                reader.readAsDataURL(file);

                this.isCropper = true;
            } else {
                alert("エラーが発生しました。再度操作をお願いします。");
            }
        },
        updateProfile() {

        },
        updateEmail() {
            // validation

            axios.post('/api/user/update/email', {
                id: this.user.id,
                current_email: this.currentEmail,
                new_email: this.newEmail
            }).then(res => {
                this.$set(this.messages, 'email', res.data.message);
                this.currentEmail = this.newEmail;
                this.newEmail = '';
            }).catch(error => {
                console.log(error);
            })
        },
        updatePassword() {
            //validation
            if(this.newPassword !== this.passwordConfirmation) {
                this.$set(this.errors, 'currentPassword', '新しいパスワードと確認用パスワードが一致しません');
                return;
            }

            axios.post('/api/user/update/password', {
                id: this.user.id,
                current_password: this.currentPassword,
                new_password: this.newPassword,
                passwordConfirmation: this.passwordConfirmation,
            }).then(res => {
                console.log(res);
                this.$set(this.messages, 'password', res.data.message);
                this.currentPassword = '';
                this.newPassword = '';
                this.passwordConfirmation = '';
            }).catch(error => {

            })
        }
    },
}
</script>

<style lang="scss" scoped>
    img {
        display: block;
        max-width: 100%;
    }

    .cropper-container {
        .cropper-crop-box {
            border-radius: 50% !important;
            .cropper-view-box {
                border-radius: 50% !important;
            }
        }
    }
</style>