import { createRouter, createWebHistory } from "vue-router";
import index from "../components/index.vue";
import indexLogin from "../components/IndexLogin.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "index",
            component: index,
        },
        {
            path: "/login",
            name: "indexLogin",
            component: indexLogin,
        },
    ],
});

export default router;
