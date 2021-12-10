<template>
    <div>
        <div>
            <h1>タスク詳細</h1>
            <v-card>
                <v-form ref="card_form">
                    <v-card>
                        <v-card-title>タスク名</v-card-title>
                        <v-text-field
                            v-model="editForm.name"
                            value="editForm.name"
                            label="Message"
                            counter
                            maxlength="50"
                            full-width
                            height="60px"
                            :rules="nameRules"
                        >
                        </v-text-field>
                    </v-card>
                    <v-card>
                        <v-card-title>リスト名</v-card-title>
                        <v-select
                            v-model="editForm.list_name"
                            :items="listNames"
                            value="editForm.list_name"
                        ></v-select>
                    </v-card>
                    <v-card>
                        <v-card-title>タスク内容</v-card-title>
                        <v-textarea
                            v-model="editForm.content"
                            value="editForm.content"
                            label="Message"
                            counter
                            maxlength="120"
                            full-width
                            height="60px"
                            :rules="contentRules"
                        >
                        </v-textarea>
                    </v-card>
                    <v-card>
                        <v-card-title>タスクステータス</v-card-title>
                        <v-select
                            v-model="editForm.status"
                            :items="items"
                            :label="editForm.status"
                            full-width
                        ></v-select>
                    </v-card>
                    <v-card>
                        <v-menu v-model="menu">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="text"
                                    :label="date"
                                    prepend-icon="mdi-calendar"
                                    v-bind="attrs"
                                    v-on="on"
                                    clearable
                                    class="mx-auto"
                                ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="editForm.limit"
                                @input="formatDate(editForm.limit)"
                            ></v-date-picker>
                        </v-menu>
                    </v-card>
                </v-form>
            </v-card>
            <!-- エラー結果表示 -->

            <div v-if="cardAddErrors">
                <ul v-if="cardAddErrors.name">
                    <li
                        v-for="msg in cardAddErrors.name"
                        :key="msg"
                        class="red--text"
                    >
                        {{ msg }}
                    </li>
                </ul>
                <ul v-if="cardAddErrors.list_name">
                    <li
                        v-for="msg in cardAddErrors.list_name"
                        :key="msg"
                        class="red--text"
                    >
                        {{ msg }}
                    </li>
                </ul>
                <ul v-if="cardAddErrors.content">
                    <li
                        v-for="msg in cardAddErrors.content"
                        :key="msg"
                        class="red--text"
                    >
                        {{ msg }}
                    </li>
                </ul>
                <ul v-if="cardAddErrors.status">
                    <li
                        v-for="msg in cardAddErrors.status"
                        :key="msg"
                        class="red--text"
                    >
                        {{ msg }}
                    </li>
                </ul>
                <ul v-if="cardAddErrors.limit">
                    <li
                        v-for="msg in cardAddErrors.limit"
                        :key="msg"
                        class="red--text"
                    >
                        {{ msg }}
                    </li>
                </ul>
            </div>
            <v-btn
                class="d-flex mx-auto mt-5 px-10"
                @click="UpdateCard"
                :class="[
                    isEditing || contentExists
                        ? 'cyan red--text text--lighten-5'
                        : 'indigo darken-4 blue--text text--lighten-5',
                ]"
            >
                Update
            </v-btn>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            taskCard: "",
            editForm: {
                id: this.$route.params.id,
                name: "",
                content: "",
                status: "",
                limit: "",
            },
            items: ["未着手", "対応中", "保留", "完了"],
            menu: "",
            text: "",
            date: "",
            isEditing: false,
            nameRules: [
                (text) => !!text || "タスク名を記入してください",
                (text) => text.length <= 50 || "最大文字数は50文字です",
            ],
            contentRules: [
                (text) => !!text || "タスク内容を記入してください",
                (text) => text.length <= 300 || "最大文字数は300文字です",
            ],
            listNames: [],
        };
    },
    async created() {
        await axios
            .get("/api/task-card/" + this.editForm.id)
            .then((response) => {
                this.$store.dispatch("task/taskapiStatus", response.status);
                this.editForm.name = response.data.taskCard.name;
                this.editForm.content = response.data.taskCard.content;
                this.editForm.list_name = response.data.cardListName;

                this.date = response.data.date;
                this.editForm.limit = response.data.taskCard.limit;
                if (response.data.taskCard.status == 0) {
                    this.editForm.status = "未着手";
                } else if (response.data.taskCard.status == 1) {
                    this.editForm.status = "対応中";
                } else if (response.data.taskCard.status == 2) {
                    this.editForm.status = "保留";
                } else if (response.data.taskCard.status == 3) {
                    this.editForm.status = "完了";
                }
                //リスト名取得
                const listNames = [];
                response.data.taskListsName.forEach(function (ListsName) {
                    listNames.push(ListsName.name);
                });
                this.listNames = listNames;
            })
            .catch((error) => console.log(error));
    },
    methods: {
        async UpdateCard() {
            if (this.$refs.card_form.validate()) {
                await this.$store.dispatch(
                    "task/taskCardUpdate",
                    this.editForm
                );
                if (this.apiStatus) {
                    this.statusReset();
                    this.$router.push("/");
                }
            }
        },
        formatDate(date) {
            if (!date) return null;
            const [year, month, day] = date.split("-");
            this.text = `${year}年${month}月${day}日`;
            this.menu = false;
            return;
        },
        startEdit() {
            this.isEditing = true;
        },
        finishEdit() {
            this.isEditing = false;
        },
        async statusReset() {
            await this.$store.dispatch("task/errorMessageReset");
        },
    },
    computed: {
        contentExists() {
            return (
                this.editForm.name.length > 0 &&
                this.editForm.content.length > 0 &&
                this.editForm.limit.length > 0 &&
                this.editForm.status.length > 0
            );
        },
        ...mapState({
            apiStatus: (state) => state.task.apiStatus,
            cardAddErrors: (state) => state.task.errorMessages,
        }),
    },
};
</script>

<style scoped>
li {
    list-style: none;
}
</style>
