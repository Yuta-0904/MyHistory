<template>
    <router-link :to="{ name: 'taskDetail', params: { id: taskCard.id } }">
        <v-card class="mx-auto my-3" width="250px" style="min-width: 250px">
            <v-card-title class="justify-space-between">
                {{ taskCard.name }}
                <v-hover v-slot="{ hover }"
                    ><span
                        @click.prevent="removeCard"
                        :class="{ 'on-hover': hover }"
                        >X</span
                    ></v-hover
                >
            </v-card-title>
            <v-card-text>
                {{ taskCard.content }}
            </v-card-text>
            <v-card-subtitle> タスク状況：{{ statusName }} </v-card-subtitle>
            <v-card-subtitle>
                タスクリミット：{{ taskCard.limit }}
            </v-card-subtitle>
        </v-card>
    </router-link>
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
        return {
            status: "",
        };
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
    computed: {
        statusName: function () {
            if (this.taskCard.status == 0) {
                return "未着手";
            } else if (this.taskCard.status == 1) {
                return "対応中";
            } else if (this.taskCard.status == 2) {
                return "保留";
            } else if (this.taskCard.status == 3) {
                return "完了";
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
