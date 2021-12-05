<template>
    <div class="task-lists">
        <h1>タスクリスト一覧</h1>

        <v-row class="justify-center my-3">
            <v-dialog v-model="dialogCard" width="500">
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
                    <TaskListAdd />
                </v-card>
            </v-dialog>

            <template>
                <div class="text-center">
                    <v-dialog v-model="dialogList" width="500">
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
                            <TaskCardAdd :listNames="listNames" />
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
                v-for="taskList in taskLists"
                :key="taskList.id"
                :name="taskList.name"
                :listIndex="taskList.id"
                :taskCards="taskList.cards"
            />
        </v-card>
    </div>
</template>

<script>
import { mapState } from "vuex";
import TaskListAdd from "../components/TaskListAdd.vue";
import TaskCardAdd from "../components/TaskCardAdd.vue";
import List from "../components/List.vue";
export default {
    components: {
        TaskListAdd,
        List,
        TaskCardAdd,
    },
    data() {
        return {
            taskLists: [],
            taskList: {
                name: "",
            },
            listNames: [],
            dialogCard: false,
            dialogList: false,
        };
    },
    methods: {
        async taskListsGet() {
            // authストアのloginアクションを呼び出す
            const response = await axios.get("/api/task-list");
            this.taskLists = response.data.taskList;

            const listNames = [];
            this.taskLists.forEach(function (taskList) {
                listNames.push(taskList.name);
            });
            this.listNames = listNames;
        },
    },
    computed: {
        ...mapState({
            stateTaskLists: (state) => state.task.taskLists,
            stateTaskCards: (state) => state.task.taskCards,
        }),
    },
    watch: {
        $route: {
            async handler() {
                this.taskListsGet();
                await this.$store.dispatch("task/taskListsGet");
            },
            immediate: true,
        },
        stateTaskLists: {
            handler() {
                this.taskListsGet();
            },
            deep: true,
        },
        stateTaskCards: {
            handler() {
                this.taskListsGet();
            },
            deep: true,
        },
    },
};
</script>
