import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error' // ★ 追加
import task from './task'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    task
  }
})

export default store