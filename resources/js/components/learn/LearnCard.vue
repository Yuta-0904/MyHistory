<template>
    <router-link :to="{ name: 'learnDetail', params: { id: learnCard.id } }">
        <v-card class="mx-auto my-3" width="250px" style="min-width: 250px">
            <v-card-title class="justify-space-between">
                {{ learnCard.name }}
                <v-hover v-slot="{ hover }"
                    ><span
                        @click.prevent="removeCard"
                        :class="{ 'on-hover': hover }"
                        >X</span
                    ></v-hover
                >
            </v-card-title>
            <v-card-text>
                {{ learnCard.content }}
            </v-card-text>
            <v-card-subtitle> 学習状況：{{ statusName }} </v-card-subtitle>
        </v-card>
    </router-link>
</template>

<script>
export default {
    name: "Card",
    props: {
        learnCard: {
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
            if (confirm("学習記録を削除してもよろしいでしょうか?")) {
                await axios
                    .delete("/api/learn-card/" + this.learnCard.id)
                    .then((response) => {
                        // alert(response.data.message);
                        this.$store.dispatch("learn/learnListsGet");
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
            if (this.learnCard.status == 0) {
                return "未着手";
            } else if (this.learnCard.status == 1) {
                return "学習中";
            } else if (this.learnCard.status == 2) {
                return "保留";
            } else if (this.learnCard.status == 3) {
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
