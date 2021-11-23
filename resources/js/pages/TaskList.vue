<template>
<div>
	<h1>タスクリスト一覧</h1>
	<ul>
		<li v-for="taskList in testList" :key="taskList.id" class="mb-1">
			{{ taskList }} 
		</li>
	</ul>
  <div class="panel">
    <form class="form" @submit.prevent="addTask">
      <label for="taskListName">Name</label>
      <input type="text" class="form__item" id="taskListName" v-model="taskList.name">
      <div class="form__button">
          <button type="submit" class="btn btn-secondary">addTaskList</button>
      </div>
    </form>
  </div>
</div>
</template>

<script>
	export default {
		data(){
			return{
        testList:[],
				taskList: {
            name:'',
        },
			}
		},
    methods: {
        async taskListsGet () {
          // authストアのloginアクションを呼び出す
          const response = await axios.get('/api/task-list')
          this.testList = response.data.taskList 
        },
        async addTask() {
          await this.$store.dispatch('task/taskListsCreate', this.taskList)
          this.name = ""
          this.taskListsGet ()
        }
    },
    watch: {
    $route: {
      handler () {
        this.taskListsGet()
      },
      immediate: true
    },
  }
	}
</script>