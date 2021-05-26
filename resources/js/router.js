import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

import Login from "./components/auth/Login.vue";
import Home from "./components/auth/Home.vue";
import Register from "./components/auth/Register.vue";
import Followings from "./components/auth/Followings.vue";
import Verified from "./components/auth/Verified.vue";
import Settings from "./components/auth/Settings.vue";

import Index from "./components/Index.vue";
import CategoryIndex from "./components/CategoryIndex.vue";
import FavoriteIndex from "./components/FavoriteIndex.vue";
import Search from "./components/Search.vue";
import IndexBySearch from "./components/IndexBySearch.vue";

import MangaShow from "./components/manga/Show.vue";
import MangaCreate from "./components/manga/Create.vue";

import MangaPackageIndex from "./components/manga-package/Index.vue";
import MangaPackageCreate from "./components/manga-package/Create.vue";
import MangaPackageShow from "./components/manga-package/Show.vue";
import MangaPackageEdit from "./components/manga-package/Edit.vue";

import MangaVolumeCreate from "./components/manga-volume/Create.vue";
import MangaVolumeShow from "./components/manga-volume/Show.vue";

import CommentIndex from "./components/comment/Index.vue"
import CommentCreate from "./components/comment/Create.vue"

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "index",
            component: Index,
        },
        {
            path: "/category/:category?",
            name: "category",
            component: CategoryIndex,
        },
        {
            path: "/favorite",
            name: "favorite",
            component: FavoriteIndex,
            meta: {authOnly: true}
        },
        {
            path: "/search",
            name: "search",
            component: Search,
        },
        {
            path: "/search/:keyword",
            name: "search.index",
            component: IndexBySearch,
        },
        {
            path: "/login",
            name: "login",
            component: Login,
            meta: {guestOnly: true}
        },
        {
            path: "/home/:username?",
            name: "home.name",
            component: Home,
            meta: {authOnly: true}
        },
        {
            path: "/settings",
            name: "settings",
            component: Settings,
            meta: {authOnly: true}
        },
        {
            path: "/register",
            name: "register",
            component: Register,
            meta: {guestOnly: true}
        },
        {
            path: "/user/verify/:token",
            name: "user.verified",
            component: Verified,
        },
        {
            path: "/followings/:followingId",
            name: "followings",
            component: Followings,
            meta: {authOnly: true}
        },
        {
            path: "/manga/:id/create",
            name: "manga.create",
            component: MangaCreate,
            meta: {authOnly: true}
        },
        {
            path: "/manga/:id",
            name: "manga.show",
            component: MangaShow,
            meta: {authOnly: true}
        },
        {
            path: "/manga-package",
            name: "manga-package.index",
            component: MangaPackageIndex,
        },
        {
            path: "/manga-package/create",
            name: "manga-package.create",
            component: MangaPackageCreate,
            meta: {authOnly: true}
        },
        {
            path: "/manga-package/:id/edit",
            name: "manga-package.edit",
            component: MangaPackageEdit,
            meta: {authOnly: true}
        },
        {
            path: "/manga-package/:id",
            name: "manga-package.show",
            component: MangaPackageShow,
        },
        {
            path: "/manga-volume/:packageId/create",
            name: "manga-volume.create",
            component: MangaVolumeCreate,
            meta: {authOnly: true}
        },
        {
            path: "/manga-volume/:id",
            name: "manga-volume.show",
            component: MangaVolumeShow,
        },
        {
            path: "/comment/manga/:package_id",
            name: "comment-manga.show",
            component: CommentIndex,
        },
        {
            path: "/comment/:storyId",
            name: "comment.index",
            component: CommentIndex,
        },
        {
            path: "/comment/create/:storyId",
            name: "comment.create",
            component: CommentCreate,
        },
    ]
});

function isLoggedIn() {
    return localStorage.getItem("auth");
}

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.authOnly)) {
        if(!isLoggedIn()) {
            next("/login");
        } else {
            next();
        }
    } else if(to.matched.some(record => record.meta.guestOnly)) {
        if(isLoggedIn()) {
            next("/home");
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;