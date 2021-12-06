<template>
    <form>
        <v-text-field
            v-model="learnList.name"
            label="LearnList"
            required
            clearable
            @focusin="startEdit"
            @focusout="finishEdit"
            counter
            :rules="nameRules"
        ></v-text-field>
        <!-- エラー結果表示 -->
        <ul v-if="listAddErrors">
            <li v-for="msg in listAddErrors.name" :key="msg" class="red--text">
                {{ msg }}
            </li>
        </ul>
        <v-btn
            class="d-flex mx-auto"
            @click="addList"
            :class="[
                isEditing || contentExists
                    ? 'cyan red--text text--lighten-5'
                    : 'indigo darken-4 blue--text text--lighten-5',
            ]"
        >
            submit
        </v-btn>
    </form>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            learnList: {
                name: "",
            },
            isEditing: false,
            nameRules: [
                (text) => text.length <= 50 || "最大文字数は50文字です",
            ],
        };
    },
    methods: {
        async addList() {
            await this.$store.dispatch(
                "learn/learnListsCreate",
                this.learnList
            );
            this.learnList.name = "";
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
            return this.learnList.name.length > 0;
        },
        ...mapState({
            listAddErrors: (state) => state.learn.errorMessages,
        }),
    },
};
</script>

<style scoped>
li {
    list-style: none;
}
</style>
