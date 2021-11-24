const state = {
  taskLists: [],
}

const getters = {
  
}

const mutations = {
  setTaskLists (state, taskLists) {
    state.taskLists = taskLists
  },

  addTaskList(state,taskList){
    state.taskLists.push({
        id: taskList.id,
        user_id: taskList.user_id,
        name: taskList.name,
        created_at: taskList.created_at,
        updated_at: taskList.updated_at,
        deleted_at:null
    })
  },
}

const actions = {
  //タスクリスト取得
  async taskListsGet (context) {
    const response = await axios.get('/api/task-list')
    const taskList = response.data.taskList || null
    context.commit('setTaskLists', taskList)
  },

  //タスクリスト新規作成
  async taskListsCreate (context, data) {
    const response = await axios.post('/api/task-list',data)
    context.commit('addTaskList', response.data.taskList)
  },
}

export default {
  //namespacedをtrueにすることでアクション呼び出す際に"モジュール/actions名"の形を第一に引数にできる。
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}