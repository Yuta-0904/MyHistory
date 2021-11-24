const state = {
  taskLists: [],
  taskCards: [],
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

  addTaskCard(state,taskCard){
    state.taskCards.push({
        id: taskCard.id,
        user_id: taskCard.user_id,
        list_id: taskCard.list_id,
        name: taskCard.name,
        content: taskCard.content,
        status: taskCard.status,
        limit: taskCard.limit,
        created_at: taskCard.created_at,
        updated_at: taskCard.updated_at,
        deleted_at:null
    })
  }

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

  //タスクカード新規作成
  async taskCardCreate (context, data) {
    const response = await axios.post('/api/task-card',data)
    context.commit('addTaskCard', response.data.taskCard)
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