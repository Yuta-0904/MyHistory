<template>
    <form>
        <v-text-field
            v-model="cardForm.name"
            label="TaskTitle"
            required
            clearable
            @focusin="startEdit"
            @focusout="finishEdit"
            class="mx-auto pt-0"
            width="100%"
        ></v-text-field>

        <v-select
            v-model="cardForm.list_name"
            :items="listNames"
            label="TaskListName"
            required
            clearable
            @focusin="startEdit"
            @focusout="finishEdit"
            class="mx-auto"
        ></v-select>

        <v-text-field
            v-model="cardForm.content"
            label="TaskContent"
            required
            clearable
            @focusin="startEdit"
            @focusout="finishEdit"
            class="mx-auto"
        ></v-text-field>
        <v-select
            v-model="cardForm.status"
            :items="items"
            label="TaskStatus"
            required
            clearable
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

        <v-btn
            class="d-flex mx-auto mb-3 px-10"
            @click="addCardToList"
            :class="[
                isEditing || contentExists
                    ? 'cyan red--text text--lighten-5'
                    : 'indigo darken-4 blue--text text--lighten-5',
            ]"
        >
            TaskAdd
        </v-btn>
    </form>
</template>

<script>
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
        };
    },
    computed: {
        contentExists() {
            return (
                this.cardForm.name.length > 0 &&
                this.cardForm.content.length > 0 &&
                this.cardForm.limit.length > 0 &&
                this.cardForm.status.length > 0
            );
        },
    },
    methods: {
        async addCardToList() {
            await this.$store.dispatch("task/taskCardCreate", this.cardForm);
            this.cardForm.name = "";
            this.cardForm.content = "";
            this.cardForm.status = "";
            this.cardForm.limit = "";
            this.cardForm.list_name = "";
            this.menu = false;
            this.text = "";
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
