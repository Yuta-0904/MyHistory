<template>
  <form>
    <v-text-field
      v-model="taskList.name"
      label="TaskList"
      required
      @focusin="startEdit"
      @focusout="finishEdit"
    ></v-text-field>
     <v-btn
      class="mr-4"
      @click="addList"
      :class="[isEditing || contentExists ? 'cyan red--text text--lighten-5' :'indigo darken-4 blue--text text--lighten-5']"
      >
      submit
    </v-btn>
    <v-btn @click="clear">
      clear
    </v-btn>
  </form>
</template>

<script>
export default {
    data(){
        return{
            taskList: {
              name:'',
            },
            isEditing: false,
        }
    },
    methods: {
      async addList(){
          await this.$store.dispatch('task/taskListsCreate', this.taskList)
          this.taskList.name = ""
          this.$emit('taskListsGet')
        },
        clear(){
        this.taskList.name = ''
        },
        startEdit(){
            this.isEditing = true
        },
        finishEdit(){
            this.isEditing = false
        }
    },
    computed: {
        contentExists() {
        return this.taskList.name.length > 0
        },
    },
    }
</script>