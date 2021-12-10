<template>
    <v-form ref="card_form">
        <v-text-field
            v-model="cardForm.name"
            label="TaskTitle"
            clearable
            @focusin="startEdit"
            @focusout="finishEdit"
            class="mx-auto pt-0"
            width="100%"
            counter
            :rules="nameRules"
        ></v-text-field>

        <v-select
            v-model="cardForm.list_name"
            :items="listNames"
            :rules="listRules"
            label="TaskListName"
            @focusin="startEdit"
            @focusout="finishEdit"
            class="mx-auto"
        ></v-select>

        <v-textarea
            v-model="cardForm.content"
            label="TaskContent"
            @focusin="startEdit"
            @focusout="finishEdit"
            class="mx-auto"
            counter
            :rules="contentRules"
        ></v-textarea>
        <v-select
            v-model="cardForm.status"
            :items="items"
            label="TaskStatus"
            :rules="statusRules"
            @focusin="startEdit"
            @focusout="finishEdit"
            class="mx-auto"
        ></v-select>

        <v-menu v-model="menu">
            <template v-slot:activator="{ on, attrs }">
                <v-text-field
                    v-model="text"
                    label="TaskLimit"
                    v-bind="attrs"
                    v-on="on"
                    clearable
                    :rules="limitRules"
                    @focusin="startEdit"
                    @focusout="finishEdit"
                    class="mx-auto"
                ></v-text-field>
            </template>
            <v-date-picker
                v-model="cardForm.limit"
                @input="formatDate(cardForm.limit)"
            ></v-date-picker>
        </v-menu>

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
            class="d-flex mx-auto mb-3 px-10"
            @click="addCard"
            :class="[
                isEditing || contentExists
                    ? 'cyan red--text text--lighten-5'
                    : 'indigo darken-4 blue--text text--lighten-5',
            ]"
        >
            TaskAdd
        </v-btn>
    </v-form>
</template>

<script>
import { mapState } from "vuex";
export default {
    name: "CardAdd",
    props: {
        listNames: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            cardForm: {
                name: "",
                content: "",
                status: "",
                limit: "",
                list_name: "",
            },
            isEditing: false,
            menu: "",
            text: "",
            items: ["未着手", "対応中", "保留", "完了"],
            taskListName: [],
            nameRules: [
                (text) => !!text || "タスク名を記入してください",
                (text) => text.length <= 50 || "最大文字数は50文字です",
            ],
            contentRules: [
                (text) => !!text || "タスク内容を記入してください",
                (text) => text.length <= 300 || "最大文字数は300文字です",
            ],
            listRules: [(text) => !!text || "リストを選択してください"],
            statusRules: [(text) => !!text || "ステータスを選択してください"],
            limitRules: [(text) => !!text || "期限を選択してください"],
        };
    },
    computed: {
        contentExists() {
            return (
                this.cardForm.name.length > 0 &&
                this.cardForm.content.length > 0 &&
                this.cardForm.limit.length > 0 &&
                this.cardForm.list_name.length > 0 &&
                this.cardForm.status.length > 0
            );
        },
        ...mapState({
            apiStatus: (state) => state.task.apiStatus,
            cardAddErrors: (state) => state.task.errorMessages,
        }),
    },
    methods: {
        async addCard() {
            if (this.$refs.card_form.validate()) {
                await this.$store.dispatch(
                    "task/taskCardCreate",
                    this.cardForm
                );
                this.cardForm.name = "";
                this.cardForm.content = "";
                this.cardForm.status = "";
                this.cardForm.limit = "";
                this.cardForm.list_name = "";
                this.menu = false;
                this.text = "";

                this.$refs.card_form.resetValidation();
                this.$emit("dialogClose");
            }
        },
        startEdit() {
            this.isEditing = true;
        },
        finishEdit() {
            this.isEditing = false;
        },
        formatDate(date) {
            if (!date) return null;
            const [year, month, day] = date.split("-");
            this.text = `${year}${month}${day}`;
            this.menu = false;
            return;
        },
    },
};
</script>

<style scoped>
li {
    list-style: none;
}
</style>
