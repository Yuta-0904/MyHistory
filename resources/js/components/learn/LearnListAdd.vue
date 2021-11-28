<template>
    <form>
        <v-text-field
            v-model="learnList.name"
            label="LearnList"
            required
            clearable
            @focusin="startEdit"
            @focusout="finishEdit"
        ></v-text-field>
        <v-btn
            class="mr-4"
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
export default {
    data() {
        return {
            learnList: {
                name: "",
            },
            isEditing: false,
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
    },
};
</script>
