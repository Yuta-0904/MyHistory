import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import TaskList from './pages/TaskList.vue'
import Login from './pages/Login.vue'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)


// VueRouterインスタンスを作成する
export default new VueRouter({
  mode: 'history',
  routes: [
    { 
        path: '/',
        name: 'taskList',
        component: TaskList
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    }
  ]
});
