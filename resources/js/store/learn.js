const state = {
    learnLists: [],
    learnCards: [],
};

const getters = {};

const mutations = {
    setLearnLists(state, learnLists) {
        state.learnLists = learnLists;
    },
};

const actions = {
    //タスクリスト取得
    async learnListsGet(context) {
        const response = await axios.get("/api/learn-list");
        const learnList = response.data.learnList || null;
        context.commit("setLearnLists", learnList);
    },

    //学習リスト新規作成
    async learnListsCreate(context, data) {
        await axios.post("/api/learn-list", data);

        const response = await axios.get("/api/learn-list");
        const learnList = response.data.learnList || null;
        context.commit("setLearnLists", learnList);
    },

    //学習カード新規作成
    async learnCardCreate(context, data) {
        await axios.post("/api/learn-card", data);

        const response = await axios.get("/api/learn-list");
        const learnList = response.data.learnList || null;
        context.commit("setLearnLists", learnList);
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
