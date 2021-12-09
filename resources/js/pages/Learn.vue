<template>
    <div class="task-lists">
        <h1>学習記録</h1>

        <v-row class="justify-center my-3">
            <v-dialog v-model="dialogList" width="500">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        outlined
                        class="me-5"
                        color="cyan darken-4"
                        elevation="6"
                        dark
                        v-bind="attrs"
                        v-on="on"
                    >
                        listAdd
                    </v-btn>
                </template>
                <v-card class="p-5">
                    <LearnListAdd @dialogClose="dialogCloseList" />
                </v-card>
            </v-dialog>

            <template>
                <div class="text-center">
                    <v-dialog v-model="dialogCard" width="500">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                outlined
                                color="cyan darken-4"
                                elevation="6"
                                dark
                                v-bind="attrs"
                                v-on="on"
                            >
                                cardAdd
                            </v-btn>
                        </template>
                        <v-card class="p-5">
                            <LearnCardAdd
                                :listNames="listNames"
                                @dialogClose="dialogCloseCard"
                            />
                        </v-card>
                    </v-dialog>
                </div>
            </template>
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
            tweet: "api",
            dialogCard: false,
            dialogList: false,
        };
    },
    methods: {
        async learnListsGet(sort) {
            const response = await axios.get(`/api/learn-list?sort=${sort}`);
            this.learnLists = response.data.learnList;

            const listNames = [];
            this.learnLists.forEach(function (learnList) {
                listNames.push(learnList.name);
            });
            this.listNames = listNames;
        },
        async statusReset() {
            await this.$store.dispatch("learn/errorMessageReset");
        },
        dialogCloseList() {
            this.dialogList = false;
        },
        dialogCloseCard() {
            this.dialogCard = false;
        },
    },
    computed: {
        ...mapState({
            stateLearnLists: (state) => state.learn.learnLists,
            stateLearnCards: (state) => state.learn.learnCards,
            errorMessages: (state) => state.learn.errorMessages,
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
                this.learnListsGet("created_at");
            },
            deep: true,
        },
        stateLearnCards: {
            handler() {
                this.learnListsGet("created_at");
            },
            deep: true,
        },
        dialogList() {
            if (!this.dialogList) {
                //ダイアログが閉じた時の処理
                if (this.errorMessages) {
                    this.statusReset();
                }
            }
        },
        dialogCard() {
            if (!this.dialogCard) {
                //ダイアログが閉じた時の処理
                if (this.errorMessages) {
                    this.statusReset();
                }
            }
        },
    },
};
</script>
