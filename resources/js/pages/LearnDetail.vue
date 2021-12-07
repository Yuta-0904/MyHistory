<template>
    <div>
        <div>
            <h1>学習詳細</h1>
            <v-card>
                <v-form ref="card_form">
                    <v-card>
                        <v-card-title>学習タイトル</v-card-title>
                        <v-text-field
                            v-model="editForm.name"
                            value="editForm.name"
                            label="Message"
                            counter
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
                        <v-card-title>学習内容</v-card-title>
                        <v-textarea
                            v-model="editForm.content"
                            value="editForm.content"
                            label="Message"
                            counter
                            full-width
                            height="60px"
                            :rules="contentRules"
                        >
                        </v-textarea>
                    </v-card>
                    <v-card>
                        <v-card-title>学習ステータス</v-card-title>
                        <v-select
                            v-model="editForm.status"
                            :items="items"
                            :label="editForm.status"
                            required
                            full-width
                        ></v-select>
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
            </div>
            <v-btn
                class="d-flex mx-auto mb-3 px-10"
                @click="UpdateCard"
                :class="[
                    isEditing || contentExists
                        ? 'cyan red--text text--lighten-5'
                        : 'indigo darken-4 blue--text text--lighten-5',
                ]"
            >
                LearnUpdate
            </v-btn>
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            learnCard: "",
            editForm: {
                id: this.$route.params.id,
                name: "",
                content: "",
                status: "",
                list_name: "",
            },
            items: ["未着手", "学習中", "保留", "完了"],
            isEditing: false,
            nameRules: [
                (text) => !!text || "学習タイトルを記入してください",
                (text) => text.length <= 50 || "最大文字数は50文字です",
            ],
            contentRules: [
                (text) => !!text || "学習内容を記入してください",
                (text) => text.length <= 1000 || "最大文字数は1000文字です",
            ],
            listNames: [],
        };
    },
    async created() {
        await axios
            .get("/api/learn-card/" + this.editForm.id)
            .then((response) => {
                // console.log(response);
                this.editForm.name = response.data.learnCard.name;
                this.editForm.content = response.data.learnCard.content;
                this.editForm.list_name = response.data.cardListName;

                if (response.data.learnCard.status == 0) {
                    this.editForm.status = "未着手";
                } else if (response.data.learnCard.status == 1) {
                    this.editForm.status = "学習中";
                } else if (response.data.learnCard.status == 2) {
                    this.editForm.status = "保留";
                } else if (response.data.learnCard.status == 3) {
                    this.editForm.status = "完了";
                }

                //リスト名取得
                const listNames = [];
                response.data.learnListsName.forEach(function (ListsName) {
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
                    "learn/learnCardUpdate",
                    this.editForm
                );
                if (this.apiStatus) {
                    this.statusReset();
                    this.$router.push("/learn");
                }
            } else {
                return false;
            }
        },
        startEdit() {
            this.isEditing = true;
        },
        finishEdit() {
            this.isEditing = false;
        },
        async statusReset() {
            await this.$store.dispatch("learn/errorMessageReset");
        },
    },
    computed: {
        contentExists() {
            return (
                this.editForm.name.length > 0 &&
                this.editForm.content.length > 0 &&
                this.editForm.status.length > 0
            );
        },
        ...mapState({
            apiStatus: (state) => state.learn.apiStatus,
            cardAddErrors: (state) => state.learn.errorMessages,
        }),
    },
};
</script>

<style scoped>
li {
    list-style: none;
}
</style>
