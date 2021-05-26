<template>
    <div class="container">
        <input type="text" class="form-control" placeholder="title, author..." @focus="toggleFocus(true)" v-model="keyword">
        <div class="list-group" v-if="isFocused">
            <router-link class="list-group-item" v-for="(item, index) in filteredItems" :key="index" :to="'/search/' + item">
                {{item}}
            </router-link>
        </div>
        <button @click="search" class="btn btn-primary">search</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            keyword: '',
            authors: [],
            mangaTitles: [],
            isFocused: false,
        }
    },
    mounted() {
        axios.get('/api/').then(res =>{
            console.log(res);
            this.authors = res.data.authors;
            this.mangaTitles = res.data.mangaTitles;
        })
    },
    computed: {
        filteredItems() {
            var items = [];
            if(!this.keyword) {
                return;
            }
            for(var i in this.mangaTitles) {
                var item = this.mangaTitles[i];
                if(item.indexOf(this.keyword) !== -1) {
                    items.push(item);
                }
            }

            for(var i in this.authors) {
                var item = this.authors[i];
                if(item.indexOf(this.keyword) !== -1) {
                    items.push(item);
                }
            }

            return items;
        },
    },
    methods: {
        toggleFocus(value) {
            this.isFocused = value;
        },
        update(keyword) {
            this.keyword = keyword;
            this.toggleFocus(false);
        },
        search() {
            this.$router.push('/search/' + this.keyword);
        }
    }
}
</script>