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
            <v-btn
                outlined
                color="light-blue darken-4"
                elevation="6"
                dark
                @click="cardSort('created_at')"
            >
                create
            </v-btn>
        </v-card-title>
        <div class="d-flex flex-nowrap cardlist">
            <LearnCard
                v-for="learnCard in learnCards"
                :key="learnCard.id"
                :learnCard="learnCard"
                :listIndex="listIndex"
            />
        </div>
    </v-card>
</template>

<script>
import LearnCard from "./LearnCard.vue";
import { mapState } from "vuex";

export default {
    name: "List",
    components: {
        LearnCard,
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
            learnCards: [],
            sortSwitch: false,
        };
    },
    methods: {
        async removeList() {
            if (
                confirm(
                    "リストを削除するとリスト内の学習記録も削除されますがよろしいでしょうか?"
                )
            ) {
                await axios
                    .delete("/api/learn-list/" + this.listIndex)
                    .then((response) => {
                        this.$store.dispatch("learn/learnListsGet");
                    })
                    .catch((error) => {
                        console.log(error);
                        alert("削除に失敗しました");
                    });
            }
        },
        async learnCardGet(sort, order) {
            sort = sort ? sort : "created_at";
            order = order ? order : "desc";
            const response = await axios.get(
                "/api/learn-card?list_id=" +
                    this.listIndex +
                    "&sort=" +
                    sort +
                    "&order=" +
                    order
            );
            this.learnCards = response.data.learnCards;
        },
        async cardSort(sort) {
            if (this.sortSwitch) {
                await this.learnCardGet(sort, "desc");
                this.sortSwitch = false;
            } else {
                await this.learnCardGet(sort, "asc");
                this.sortSwitch = true;
            }
        },
    },
      watch: {
        $route: {
            async handler() {
                this.learnCardGet();
            },
            immediate: true,
        },
        stateLearnCards: {
            handler() {
                this.learnCardGet();
            },
            deep: true,
        },
    },
    computed: {
        ...mapState({
            stateLearnCards: (state) => state.learn.learnCards,
        }),
    },
};
</script>

<style lang="scss" scoped>
.v-card__title span.on-hover {
    cursor: pointer;
}
.cardlist {
    overflow-x: scroll;
    overflow-y: hidden;
    height: 100%;
}
</style>
