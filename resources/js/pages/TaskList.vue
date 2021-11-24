<template>

 <div class="task-lists">
      <h1>タスクリスト一覧</h1>
      
        <v-card-title class="text-h3 teal--text text--darken-1 text-decoration-underline">
            <p>ListTotal:</p>
        </v-card-title>
        <v-row>
          <v-col cols="4" class="ml-6 mb-3 mt-2">
                <task-list-add
                @taskListsGet = "taskListsGet"
                />
          </v-col>
        </v-row>
        <!-- <div class="d-flex justify-center flex-wrap"> -->
          <v-card
          class="d-flex justify-center flex-wrap"
          color="grey lighten-2 my-5 py-5"
          >
              <List 
              v-for= "taskList in taskLists"
              :key = "taskList.id"
              :name = "taskList.name"
              :listIndex = "taskList.id"
              :taskCards = 'taskList.cards' 
              />
          </v-card>
        <!-- </div> -->
    </div>
</template>

<script>
import TaskListAdd from '../components/TaskListAdd.vue'
import List from '../components/List.vue'
	export default {
    components: {
      TaskListAdd,
      List
    },
		data(){
			return{
        taskLists:[],
				taskList: {
            name:'',
        },
			}
		},
    methods: {
        async taskListsGet () {
          // authストアのloginアクションを呼び出す
          const response = await axios.get('/api/task-list')
          this.taskLists = response.data.taskList 
        },
        async addTask() {
          await this.$store.dispatch('task/taskListsCreate', this.taskList)
          this.name = ""
          this.taskListsGet ()
        },
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