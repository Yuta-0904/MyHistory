<template>
    <v-card class="mx-auto my-3" width="250px" style="min-width: 250px">
        <v-card-title class="justify-space-between">
            {{ taskCard }}
            <v-hover v-slot="{ hover }"
                ><span @click="removeCard" :class="{ 'on-hover': hover }"
                    >X</span
                ></v-hover
            >
        </v-card-title>

        <v-card-subtitle>
            {{ listIndex }}
        </v-card-subtitle>
    </v-card>
</template>

<script>
export default {
    name: "Card",
    props: {
        taskCard: {
            type: Object,
            required: true,
        },
        listIndex: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {};
    },
    methods: {
        async removeCard() {
            if (confirm("タスクを削除してもよろしいでしょうか?")) {
                await axios
                    .delete("/api/task-card/" + this.taskCard.id)
                    .then((response) => {
                        alert(response.data.message);
                        this.$store.dispatch("task/taskListsGet");
                    })
                    .catch((error) => {
                        console.log(error);
                        alert("削除に失敗しました");
                    });
            }
        },
    },
};
</script>

<style scoped lang="scss">
.v-card__title span.on-hover {
    cursor: pointer;
}
</style>
