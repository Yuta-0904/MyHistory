import { OK, UNPROCESSABLE_ENTITY, CREATED } from "../util";

const state = {
    apiStatus: null,
    errorMessages: null,
    learnLists: [],
    learnCards: [],
};

const getters = {};

const mutations = {
    setLearnLists(state, learnLists) {
        state.learnLists = learnLists;
    },
    setApiStatus(state, status) {
        state.apiStatus = status;
    },
    seterrorMessages(state, messages) {
        state.errorMessages = messages;
    },
};

const actions = {
    //タスクリスト取得
    async learnListsGet(context) {
        const response = await axios.get("/api/learn-list");
        const learnList = response.data.learnList || null;
        context.commit("setLearnLists", learnList);
    },

    ///エラーメッセージリセット
    async errorMessageReset(context) {
        context.commit("seterrorMessages", null);
        context.commit("setApiStatus", null);
    },

    //学習リスト新規作成
    async learnListsCreate(context, data) {
        const responseStatus = await axios.post("/api/learn-list", data);
        const response = await axios.get("/api/learn-list");
        const learnList = response.data.learnList || null;
        context.commit("setLearnLists", learnList);

        if (responseStatus.status === OK) {
            context.commit("setApiStatus", true);
            return false;
        }

        context.commit("setApiStatus", false);
        if (responseStatus.status === UNPROCESSABLE_ENTITY) {
            context.commit("seterrorMessages", responseStatus.data.errors);
        } else {
            context.commit("error/setCode", responseStatus.status, {
                root: true,
            });
        }
    },

    //学習カード新規作成
    async learnCardCreate(context, data) {
        const responseStatus = await axios.post("/api/learn-card", data);
        const response = await axios.get("/api/learn-list");
        const learnList = response.data.learnList || null;
        context.commit("setLearnLists", learnList);

        if (responseStatus.status === OK) {
            context.commit("setApiStatus", true);
            return false;
        }

        context.commit("setApiStatus", false);
        if (responseStatus.status === UNPROCESSABLE_ENTITY) {
            context.commit("seterrorMessages", responseStatus.data.errors);
        } else {
            context.commit("error/setCode", responseStatus.status, {
                root: true,
            });
        }
    },

    //学習カード更新
    async learnCardUpdate(context, data) {
        await axios.patch("/api/learn-card/" + data.id, data);

        const response = await axios.get("/api/learn-list");
        const learnList = response.data.learnList || null;
        context.commit("setLearnLists", learnList);
    },
};

export default {
    //namespacedをtrueにすることでアクション呼び出す際に"モジュール/actions名"の形を第一に引数にできる。
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
