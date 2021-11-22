import Vue from 'vue'
// ルーティングの定義をインポートする
import router from './router'
// ルートコンポーネントをインポートする
import App from './App.vue'

import Vuetify from 'vuetify'//vuetify追加
import 'vuetify/dist/vuetify.min.css'//vuetify追加
import '@mdi/font/css/materialdesignicons.css'//vuetifyアイコン追加

Vue.use(Vuetify);


new Vue({
    el: '#app',
    router, // ルーティングの定義を読み込む
    vuetify: new Vuetify({ icons: {
        iconfont: 'mdi', // 追加
    }}),
    components: { App }, // ルートコンポーネントの使用を宣言する
    template: '<App />' // ルートコンポーネントを描画する
  })