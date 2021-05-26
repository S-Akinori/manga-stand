<template>
    <div class="container">
        <p class="error">{{errors.whole}}</p>
        <form @submit.prevent="create" enctype="multipart/form-data">

            <div class="form-group">
                <label for="">最新刊</label>
                <input type="text" v-model="vol" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="description">説明</label>
                <input type="text" id="description" class="form-control" v-model="description">
            </div>

            <div class="form-group">
                <label for="file">表紙画像</label>
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
            file: null,
            vol: 1,
            description: '',
            errors: {},
        }
    },
    mounted() {
        axios(('/api/manga-volume/' + this.$route.params.packageId + '/create')).then(res => {
            console.log(res);
            if(res.data.volume) {
                this.vol = res.data.volume + 1;
            } else {
                this.$router.push(res.data);
            }
        }).catch(error => {
            console.log(error);
        })
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

            formData.append('volume', this.vol);
            formData.append('file', this.file);
            formData.append('description', this.description);
            formData.append('package_id', this.$route.params.packageId);

            axios.post("/api/manga-volume/store", formData)
            .then(response => {
                console.log(response);
                // this.$router.push('/manga-package');
            }).catch(error => {
                console.log(error);
                this.$set(this.errors, error.data.name, error.data.message);
            })
        },
        validation() {
            if(!this.file) {
                this.$set(this.errors, "file", "File required");
            }
            // png, jpgのみ
        }
    }
}
</script>