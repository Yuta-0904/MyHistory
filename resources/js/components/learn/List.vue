<template>
    <v-card
        class="mx-2 d-flex flex-column mt-3"
        width="300px"
        style="min-width: 300px"
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

        <LearnCard
            v-for="learnCard in learnCards"
            :key="learnCard.id"
            :learnCard="learnCard"
            :listIndex="listIndex"
        />
    </v-card>
</template>

<script>
import LearnCard from "./LearnCard.vue";

export default {
    name: "List",
    components: {
        // TaskCardAdd,
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
        learnCards: {
            type: Array,
        },
    },
    data() {
        return {};
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
    },
};
</script>

<style lang="scss" scoped>
.v-card__title span.on-hover {
    cursor: pointer;
}
</style>
