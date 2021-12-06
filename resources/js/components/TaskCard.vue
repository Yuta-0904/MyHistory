<template>
    <router-link
        class="routerLink"
        :to="{ name: 'taskDetail', params: { id: taskCard.id } }"
    >
        <v-card
            class="mx-3 p-2 pb-4 my-2"
            width="250px"
            style="min-width: 250px"
            :color="cardColor"
            hover
        >
            <div class="d-flex justify-end mt-3 me-3">
                <v-hover v-slot="{ hover }"
                    ><span
                        @click.prevent="removeCard"
                        :class="{ 'on-hover': hover }"
                        >X</span
                    ></v-hover
                >
            </div>
            <v-card-title class="justify-center card-name">
                <span class="card-name font-weight-bold">{{
                    taskCard.name
                }}</span>
            </v-card-title>

            <div>
                <div class="d-flex justify-space-between mt-2 mx-4">
                    {{ taskCard.date }}
                    <span>{{ statusName }}</span>
                </div>
                <div class="d-flex justify-end" v-if="!complete">
                    期限：<span :class="limitStatus">{{ taskCard.limit }}</span>
                </div>
                <div v-else><br /></div>
            </div>
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
            complete: false,
        };
    },
    methods: {
        async removeCard() {
            if (confirm("タスクを削除してもよろしいでしょうか?")) {
                await axios
                    .delete("/api/task-card/" + this.taskCard.id)
                    .then((response) => {
                        console.log(response);
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
                this.complete = true;
                return "完了";
            }
        },
        cardColor() {
            if (this.taskCard.status == 0) {
                return "orange darken-1 grey--text text--darken-3";
            } else if (this.taskCard.status == 1) {
                return "blue grey--text text--darken-3";
            } else if (this.taskCard.status == 2) {
                return "yellow darken-1 grey--text text--darken-3";
            } else if (this.taskCard.status == 3) {
                return "grey white--text";
            }
        },
        limitStatus() {
            const today = new Date();
            const limitDay = new Date(this.taskCard.limit);

            if (today.getTime() > limitDay.getTime()) {
                return "red--text text--darken-2 font-weight-bold";
            } else {
                return;
            }

            return limitDay;
        },
    },
};
</script>

<style scoped lang="scss">
.v-card__title span.on-hover {
    cursor: pointer;
}

.card-name {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.routerLink {
    text-decoration: none;
}
</style>
