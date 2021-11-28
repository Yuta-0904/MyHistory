<template>
    <header>
        <v-navigation-drawer
            v-model="drawer"
            app
            clipped
            bottom
            absolute
            temporary
            class="d-md-none d-block"
        >
            <v-list nav dense>
                <v-list-item-group>
                    <v-list-item to="/" v-if="isLogin">
                        <v-list-item-title>TASK</v-list-item-title>
                    </v-list-item>
                    <v-list-item to="/login">
                        <v-list-item-title>Login / Register</v-list-item-title>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar
            app
            color="cyan darken-4 indigo--text text--lighten-5"
            dark
            clipped-left
        >
            <v-app-bar-nav-icon
                @click="drawer = !drawer"
                class="d-md-none d-block"
            ></v-app-bar-nav-icon>
            <v-toolbar-title>MyHistory</v-toolbar-title>
            <v-tabs class="d-md-block d-none">
                <v-tab to="/" v-if="isLogin"> TASK </v-tab>
                <v-tab to="/learn" v-if="isLogin"> LEARN </v-tab>
                <v-tab>
                    <div v-if="isLogin" class="navbar__item">ログイン中</div>
                    <div v-else class="navbar__item">
                        <RouterLink class="button button--link" to="/login">
                            Login / Register
                        </RouterLink>
                    </div>
                </v-tab>
            </v-tabs>
        </v-app-bar>
    </header>
</template>
<script>
export default {
    data() {
        return {
            drawer: false,
        };
    },
    methods: {},
    computed: {
        isLogin() {
            return this.$store.getters["auth/check"];
        },
        username() {
            return this.$store.getters["auth/username"];
        },
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
