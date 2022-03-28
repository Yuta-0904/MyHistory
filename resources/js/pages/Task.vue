<template>
    <div class="task-lists">
        <h1>TaskList</h1>

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
                    <TaskListAdd @dialogClose="dialogCloseList" />
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
                            <TaskCardAdd
                                :listNames="listNames"
                                @dialogClose="dialogCloseCard"
                            />
                        </v-card>
                    </v-dialog>
                </div>
            </template>
        </v-row>

        <div class="d-flex justify-center flex-wrap my-5 py-5">
            <List
                v-for="taskList in taskLists"
                :key="taskList.id"
                :name="taskList.name"
                :listIndex="taskList.id"
                @cardSort="taskListsGet"
            />
        </div>
        <div class="main-content">
            <div class="alert alert-danger mb-3" role="alert">
                <h4 class="alert-heading">Zoomとの連携が行われていません。</h4>
                <p>
                    このシステムをご利用する場合、Zoomとの連携を行ってください。
                </p>
                <a :href="zoomOuthLink" class="btn btn-danger">Zoomと連携</a>
            </div>
            <div>
                <h2 class="my-5 py-5">予約一覧</h2>

                <v-form class="my-5 py-5">
                    <v-text-field
                        v-model="cardForm.Email"
                        label="MeetingEmail"
                        class="mx-auto pt-0"
                    ></v-text-field>

                    <v-text-field
                        v-model="cardForm.YourName"
                        label="MeetingYourName"
                        class="mx-auto"
                    ></v-text-field>

                    <v-textarea
                        v-model="cardForm.CompanyName"
                        label="MeetingCompanyName"
                        class="mx-auto"
                    ></v-textarea>
                    <v-textarea
                        v-model="cardForm.Content"
                        label="MeetingContent"
                        class="mx-auto"
                    ></v-textarea>
                    <v-menu v-model="menu">
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                                v-model="text"
                                label="MeetingStartAt"
                                v-bind="attrs"
                                v-on="on"
                                class="mx-auto"
                            ></v-text-field>
                        </template>
                        <v-date-picker
                            v-model="cardForm.StartAt"
                            @input="formatDate(cardForm.StartAt)"
                        ></v-date-picker>
                    </v-menu>

                    <v-btn
                        class="d-flex mx-auto mb-3 px-10"
                        @click="addMeeting"
                    >
                        MeetingAdd
                    </v-btn>
                </v-form>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
import TaskListAdd from "../components/task/TaskListAdd.vue";
import TaskCardAdd from "../components/task/TaskCardAdd.vue";
import List from "../components/task/List.vue";
export default {
    components: {
        TaskListAdd,
        List,
        TaskCardAdd,
    },
    data() {
        return {
            taskLists: [],
            listNames: [],
            dialogCard: false,
            dialogList: false,
            noZoomCode: true,
            zoomOuthLink: "",
            cardForm: {
                Email: "",
                YourName: "",
                CompanyName: "",
                Content: "",
                StartAt: "",
            },
            menu: "",
            text: "",
        };
    },
    methods: {
        async taskListsGet() {
            const response = await axios.get("/api/task-list");
            this.taskLists = response.data.taskList;
            this.noZoomCode = response.data.noZoomCode;
            this.zoomOuthLink = response.data.zoomOuthLink;

            const listNames = [];
            this.taskLists.forEach(function (taskList) {
                listNames.push(taskList.name);
            });
            this.listNames = listNames;
        },
        async statusReset() {
            await this.$store.dispatch("task/errorMessageReset");
        },
        dialogCloseList() {
            this.dialogList = false;
        },
        dialogCloseCard() {
            this.dialogCard = false;
        },
        async addMeeting() {
            await axios.post("/api/add-meeting", this.cardForm);
            this.cardForm.Email = "";
            this.cardForm.YourName = "";
            this.cardForm.CompanyName = "";
            this.cardForm.Content = "";
            this.cardForm.StartAt = "";
        },
    },
    computed: {
        ...mapState({
            stateTaskLists: (state) => state.task.taskLists,
            stateTaskCards: (state) => state.task.taskCards,
            errorMessages: (state) => state.task.errorMessages,
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

        dialogList() {
            if (!this.dialogList) {
                //ダイアログが閉じた時
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
