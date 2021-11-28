<template>
    <div class="task-lists">
        <h1>学習記録</h1>

        <v-card-text>
            <LearnCardAdd :listNames="listNames" />
        </v-card-text>
        <v-row>
            <v-col cols="4" class="ml-6 mb-3 mt-2">
                <learn-list-add />
            </v-col>
        </v-row>

        <v-card
            class="d-flex justify-center flex-wrap"
            color="grey lighten-2 my-5 py-5"
        >
            <List
                v-for="learnList in learnLists"
                :key="learnList.id"
                :name="learnList.name"
                :listIndex="learnList.id"
                :learnCards="learnList.cards"
            />
        </v-card>
    </div>
</template>

<script>
import { mapState } from "vuex";
import LearnListAdd from "../components/learn/LearnListAdd.vue";
import LearnCardAdd from "../components/learn/LearnCardAdd.vue";
import List from "../components/learn/List.vue";
export default {
    components: {
        LearnListAdd,
        List,
        LearnCardAdd,
    },
    data() {
        return {
            learnLists: [],
            learnList: {
                name: "",
            },
            listNames: [],
        };
    },
    methods: {
        async learnListsGet() {
            // authストアのloginアクションを呼び出す
            const response = await axios.get("/api/learn-list");
            this.learnLists = response.data.learnList;

            const listNames = [];
            this.learnLists.forEach(function (learnList) {
                listNames.push(learnList.name);
            });
            this.listNames = listNames;
        },
    },
    computed: {
        ...mapState({
            stateLearnLists: (state) => state.learn.learnLists,
            stateLearnCards: (state) => state.learn.learnCards,
        }),
    },
    watch: {
        $route: {
            async handler() {
                this.learnListsGet();
                await this.$store.dispatch("learn/learnListsGet");
            },
            immediate: true,
        },
        stateLearnLists: {
            handler() {
                this.learnListsGet();
            },
            deep: true,
        },
        stateLearnCards: {
            handler() {
                this.learnListsGet();
            },
            deep: true,
        },
    },
};
</script>
