import Vue from "vue";
import VueRouter from "vue-router";
import NotFound from "./pages/errors/NotFound.vue";

// ページコンポーネントをインポートする
import TaskList from "./pages/TaskList.vue";
import Login from "./pages/Login.vue";
import Task from "./pages/Task.vue";
import Learn from "./pages/Learn.vue";
import LearnDetail from "./pages/LearnDetail.vue";

import store from "./store"; // ★　追加
import SystemError from "./pages/errors/System.vue";

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter);

// VueRouterインスタンスを作成する
export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "taskList",
            component: TaskList,
            beforeEnter(to, from, next) {
                ///未ログインであればログインページに返却するよう条件判定
                if (!store.getters["auth/check"]) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/login",
            name: "login",
            component: Login,
            beforeEnter(to, from, next) {
                ///ログイン中であればtopページに返却するよう条件判定
                if (store.getters["auth/check"]) {
                    next("/");
                } else {
                    next();
                }
            },
        },
        {
            path: "/task/:id",
            name: "taskDetail",
            component: Task,
            beforeEnter(to, from, next) {
                ///未ログインであればログインページに返却するよう条件判定
                if (!store.getters["auth/check"]) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/learn",
            name: "learnList",
            component: Learn,
            beforeEnter(to, from, next) {
                ///未ログインであればログインページに返却するよう条件判定
                if (!store.getters["auth/check"]) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/learn/:id",
            name: "learnDetail",
            component: LearnDetail,
            beforeEnter(to, from, next) {
                ///未ログインであればログインページに返却するよう条件判定
                if (!store.getters["auth/check"]) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/500",
            component: SystemError,
        },
        {
            path: "*",
            component: NotFound,
        },
    ],
});
