<template>
    <div>
        Here is a package page !
        <div>
            <div v-for="(mangaPackage, index) in mangaPackages" :key="mangaPackage">
                <router-link :to="'/manga-package/' + mangaPackage.id" >{{mangaPackage.title}}</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            mangaPackages: [],
        };
    },
    mounted() {
        axios('/api/manga-package/index').then(res => {
            console.log(res);
            this.mangaPackages = res.data;
        }).catch(error => {
            console.log(error);
        });
    }
}
</script>