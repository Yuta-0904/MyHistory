<template>
    <v-card
        class="mx-2 d-flex flex-column mt-3"
        style="min-width: 300px"
        width="1000"
    >
        <v-card-text>
            <v-card-title class="justify-space-between">
                <h2>{{ name }}</h2>
                <v-hover v-slot="{ hover }"
                    ><span @click="removeList" :class="{ 'on-hover': hover }"
                        >X</span
                    ></v-hover
                >
            </v-card-title>
        </v-card-text>
        <v-card-title style="padding-top: 0">
            <v-btn
                outlined
                class="me-5"
                color="light-blue darken-4"
                elevation="6"
                dark
                @click="cardSort('status')"
            >
                status
            </v-btn>
            <v-btn outlined color="light-blue darken-4" elevation="6" dark @click="cardSort('limit')">
                limit
            </v-btn>
        </v-card-title>
        <div class="d-flex flex-nowrap cardlist">
            <TaskCard
                v-for="taskCard in taskCards"
                :key="taskCard.id"
                :taskCard="taskCard"
                :listIndex="listIndex"
            />
        </div>
        
    </v-card>
</template>

<script>
import { mapState } from "vuex";
import TaskCardAdd from "./TaskCardAdd.vue";
import TaskCard from "./TaskCard.vue";

export default {
    name: "List",
    components: {
        TaskCardAdd,
        TaskCard,
    },
    props: {
        name: {
            type: String,
            required: true,
        },
        listIndex: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            taskCards: [],
            sortSwitch: false
        };
    },
    methods: {
        async removeList() {
            if (
                confirm(
                    "リストを削除するとリスト内のタスクも削除されますがよろしいでしょうか?"
                )
            ) {
                await axios
                    .delete("/api/task-list/" + this.listIndex)
                    .then((response) => {
                        this.$store.dispatch("task/taskListsGet");
                    })
                    .catch((error) => {
                        console.log(error);
                        alert("削除に失敗しました");
                    });
            }
        },
        async taskCardGet(sort,order) {
            sort = sort ? sort : "created_at";
            order = order ? order : 'desc'
            const response = await axios.get(
                "/api/task-card?list_id=" + this.listIndex + "&sort=" + sort + "&order=" + order
            );
            this.taskCards = response.data.taskCards;
            
        },
        async cardSort(sort) {
            if(this.sortSwitch){
await this.taskCardGet(sort,'desc');
this.sortSwitch = false;
            }else {
               await this.taskCardGet(sort,'asc'); 
               this.sortSwitch = true;
            }
            
        },
    },
    watch: {
        $route: {
            async handler() {
                this.taskCardGet();
            },
            immediate: true,
        },
           stateTaskCards: {
            handler() {
                this.taskCardGet();
                
            },
            deep: true,
        },
    },
    computed: {
        ...mapState({
            stateTaskCards: (state) => state.task.taskCards,
        }),
    },
};
</script>

<style lang="scss" scoped>
.v-card__title span.on-hover {
    cursor: pointer;
}

.v-card__title span.on-hover {
    cursor: pointer;
}
.cardlist {
    overflow-x: scroll;
    overflow-y: hidden;
    height: 100%;
}
</style>
