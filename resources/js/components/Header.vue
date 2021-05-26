<template>
    <header v-if="$route.name !== 'manga.show'" class="h-16 px-3 flex items-center justify-between bg-white">
        <router-link to="/">Manga Project</router-link>
        <router-link to="/search"><i class="fas fa-search"></i></router-link>
    </header>
</template>

<script>
export default {
    data() {
        return {
            isHeader: true
        }
    }
}
</script>