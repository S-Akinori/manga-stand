<template>
    <nav v-if="$route.name !== 'manga.show'" class="fixed bottom-0 w-full h-16 bg-white flex justify-around items-center">
        <router-link to="/">HOME</router-link>
        <router-link to="/category/all">カテゴリー</router-link>
        <router-link to="/favorite">お気に入り</router-link>
        <router-link to="/home/">アカウント</router-link>
    </nav>
</template>