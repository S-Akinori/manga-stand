<template>
    <div class="container">
        <p class="error">{{errors.whole}}</p>
        <form @submit.prevent="create" enctype="multipart/form-data">
            <div class="form-group">
                <label for="package">漫画</label>
                <input type="text" id="package" class="form-control" v-model="mangaPackage.title" readonly>
                <p class="error">{{errors.package}}</p>
            </div>

            <div class="form-group">
                <label for="volume">巻</label>
                <select class="form-control" id="volume" v-model="volume">
                    <option v-for="(mangaVolume, index) in mangaVolumes" :key="mangaVolume.id" selected>{{mangaVolume.volume}}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="title">話のタイトル</label>
                <input type="text" id="title" class="form-control" v-model="title">
                <p class="error">{{errors.title}}</p>
            </div>

            <div class="form-group">
                <label for="file">ファイル</label>
                <input type="file" id="file" class="form-control-file" @change="update" accept=".zip">
                <p class="error">{{errors.file}}</p>
            </div>

            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            title: '',
            file: null,
            volume: '',
            mangaPackage: {},
            mangaVolumes: {},
            errors: {},
        }
    },
    mounted() {
        axios.get('/api/manga/' + this.$route.params.id + '/create').then(res => {
            console.log(res);
            this.mangaPackage = res.data.package;
            this.mangaVolumes = res.data.volume;
            this.volume = this.mangaVolumes[0].volume;
        }).catch( error => {
            console.log(error);
        });
    },
    methods: {
        update(event) {
            this.file = event.target.files[0];
        },
        create() {
            this.validation();
            for(var item in this.errors) {
                if(this.errors[item]) {
                    return;
                }
            }
            
            var formData = new FormData();

            formData.append('title', this.title);
            formData.append('file', this.file);
            formData.append('package_id', this.mangaPackage.id);
            formData.append('volume', this.volume);

            axios.post("/api/manga/store", formData)
            .then(response => {
                console.log(response);
            }).catch(error => {
                console.log(error);
                this.errors = error.response.data.errors;
            })
        },
        validation() {
            if(!this.title) {
                this.$set(this.errors, "title", "Title required");
            } else if(this.title.length > 256) {
                this.$set(this.errors, "title", "Letters must be less than 256");
            }

            if(!this.file) {
                this.$set(this.errors, "file", "File required");
            }
            // ファイルの種類をzipのみにする？
        }
    }
}
</script>