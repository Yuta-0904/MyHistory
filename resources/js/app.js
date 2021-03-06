import Vue from 'vue'
// ルーティングの定義をインポートする
import router from './router'
// ルートコンポーネントをインポートする
import App from './App.vue'

import Vuetify from 'vuetify'//vuetify追加
import 'vuetify/dist/vuetify.min.css'//vuetify追加
import '@mdi/font/css/materialdesignicons.css'//vuetifyアイコン追加
import store from './store' // ★　vuexstore追加
import './bootstrap'

Vue.use(Vuetify);


const createApp = async () => {
    await store.dispatch('auth/currentUser')
    new Vue({
        el: '#app',
        router, // ルーティングの定義を読み込む
        store,
        vuetify: new Vuetify({ icons: {
            iconfont: 'mdi', // 追加
        }}),
        components: { App }, // ルートコンポーネントの使用を宣言する
        template: '<App />' // ルートコンポーネントを描画する
    })
}

createApp()
