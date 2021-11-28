<template>
    <div>
        <div>
            <h1>学習詳細</h1>
            <v-card>
                <v-card>
                    <v-card-title>学習タイトル</v-card-title>
                    <v-text-field
                        v-model="editForm.name"
                        value="editForm.name"
                        label="Message"
                        counter
                        maxlength="50"
                        full-width
                        height="60px"
                    >
                    </v-text-field>
                </v-card>
                <v-card>
                    <v-card-title>学習内容</v-card-title>
                    <v-textarea
                        v-model="editForm.content"
                        value="editForm.content"
                        label="Message"
                        counter
                        maxlength="120"
                        full-width
                        height="60px"
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
            </v-card>
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
export default {
    data() {
        return {
            learnCard: "",
            editForm: {
                id: this.$route.params.id,
                name: "",
                content: "",
                status: "",
            },
            items: ["未着手", "学習中", "保留", "完了"],
            isEditing: false,
        };
    },
    async created() {
        await axios
            .get("/api/learn-card/" + this.editForm.id)
            .then((response) => {
                this.editForm.name = response.data.learnCard.name;
                this.editForm.content = response.data.learnCard.content;

                if (response.data.learnCard.status == 0) {
                    this.editForm.status = "未着手";
                } else if (response.data.learnCard.status == 1) {
                    this.editForm.status = "学習中";
                } else if (response.data.learnCard.status == 2) {
                    this.editForm.status = "保留";
                } else if (response.data.learnCard.status == 3) {
                    this.editForm.status = "完了";
                }
            })
            .catch((error) => console.log(error));
    },
    methods: {
        async UpdateCard() {
            await this.$store.dispatch("learn/learnCardUpdate", this.editForm);
            this.$router.push("/learn");
        },
        startEdit() {
            this.isEditing = true;
        },
        finishEdit() {
            this.isEditing = false;
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
    },
};
</script>
