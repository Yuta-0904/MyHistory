import Vue from "vue";
import Vuex from "vuex";

import auth from "./auth";
import error from "./error"; // ★ 追加
import task from "./task";
import learn from "./learn";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        auth,
        error,
        task,
        learn,
    },
});

export default store;
