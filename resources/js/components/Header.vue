<template>
    <header>
        <v-app-bar
            app
            color="cyan darken-4 indigo--text text--lighten-5"
            dark
            clipped-left
        >
            <v-toolbar-title>
                <span class="pl-3">MyHistory</span>
            </v-toolbar-title>
            <v-tabs>
                <v-tab to="/" v-if="isLogin"
                    ><v-icon class="me-2">mdi-electron-framework</v-icon> TASK
                </v-tab>
                <v-tab to="/learn" v-if="isLogin"
                    ><v-icon class="me-2">mdi-book-open-page-variant</v-icon>
                    LEARN
                </v-tab>
                <v-tab v-if="isLogin" @click="logout"> Logout </v-tab>
            </v-tabs>
        </v-app-bar>
    </header>
</template>
<script>
import { mapState } from "vuex";
export default {
    data() {
        return {};
    },
    methods: {
        async logout() {
            await this.$store.dispatch("auth/logout");

            if (this.apiStatus) {
                this.$router.push("/login");
            }
        },
    },
    computed: {
        isLogin() {
            return this.$store.getters["auth/check"];
        },
        username() {
            return this.$store.getters["auth/username"];
        },
        ...mapState({
            apiStatus: (state) => state.auth.apiStatus,
        }),
    },
};
</script>
<style scoped>
.v-toolbar__title {
    overflow: visible !important;
    margin-right: 50px !important;
}

.v-application a {
    color: hsla(0, 0%, 100%, 0.6);
}
</style>
