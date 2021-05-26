<template>
    <div class="container">
        <form @submit.prevent="edit" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">タイトル</label>
                <input type="text" id="title" class="form-control" v-model="title">
                <p class="error">{{errors.title}}</p>
            </div>

            <div class="form-group">
                <label for="description">説明</label>
                <textarea id="description" class="form-control" v-model="description" rows="4"></textarea>
                <p class="error">{{errors.description}}</p>
            </div>

            <div class="form-group">
                <label for="category">カテゴリー</label>
                <select class="form-control" id="category" v-model="category" multiple>
                    <option v-for="(category, index) in categories" :key="index">{{category}}</option>
                </select>
                <p class="error">{{errors.category}}</p>
            </div>

            <div class="form-group">
                <label for="file">画像ファイル</label>
                <input type="file" id="file" class="form-control-file" @change="update" accept=".png, .jpg">
                <p class="error">{{errors.file}}</p>
            </div>

            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            mangaPackage: {},
            categories: ['女性漫画', '少女漫画', '青年漫画', '少年漫画' ,'バトル', 'アクション', '恋愛', 'ギャグ', 'ミステリー', '歴史', 'スポーツ', 'ホラー', 'SF', 'グルメ'],
            title: '',
            description: '',
            file: null,
            category: [],
            errors: {},
        }
    },
    mounted() {
        axios.get('/api/manga-package/' + this.$route.params.id + '/edit').then(res => {
            console.log(res)
            this.mangaPackage = res.data;
            this.title = this.mangaPackage.title;
            this.description = this.mangaPackage.description;
            this.category = this.mangaPackage.category.split(',');
        }).catch(error => {
            console.log(error);
            if(error.response.data.name == 'auth') {
                this.$router.push(error.response.data.redirect)
            }
        });
    },
    methods: {
        update(event) {
            this.file = event.target.files[0];
        },
        edit() {
            this.validation();
            for(var item in this.errors) {
                if(this.errors[item]) {
                    return;
                }
            }

            var formData = new FormData();

            formData.append('title', this.title);
            formData.append('description', this.description);
            formData.append('category', this.category);
            formData.append('file', this.file);
            axios.post("/api/manga-package/" + this.$route.params.id, formData, {
                headers: {
                    'X-HTTP-Method-Override': 'PATCH'
                }
            })
            .then(response => {
                console.log(response);
                // this.$router.push('/manga-package');
            }).catch(error => {
                console.log(error);
                this.$set(this.errors, error.data.name, error.data.message);
            })
        },
        validation() {
            if(!this.title) {
                this.$set(this.errors, "title", "Title required");
            } else if(this.title.length > 256) {
                this.$set(this.errors, "title", "Letters must be less than 256");
            }

            if(!this.description) {
                this.$set(this.errors, "description", "Description required");
            } else if(this.description > 256) {
                this.$set(this.errors, "description", "Description must be less than 256");
            }

            // if(!this.file) {
            //     this.$set(this.errors, "file", "File required");
            // }
            // png, jpgのみ
        }
    }
}
</script>