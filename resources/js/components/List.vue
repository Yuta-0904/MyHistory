<template>
    
  <v-card
    class="mx-2 d-flex flex-column mt-3"
    width="300px"
    style="min-width: 300px"
  >
    <v-card-text>
        <v-card-title class="justify-space-between">
            <h2>{{ name }}</h2>
            <v-hover v-slot="{ hover }"><span @click="removeList" :class="{ 'on-hover': hover }">X</span></v-hover>
        </v-card-title>
    </v-card-text>
    
    <v-card-text>
        <TaskCardAdd :listIndex="listIndex" />
    </v-card-text>
    
    <TaskCard v-for="taskCard in taskCards"
        :key="taskCard.id"
        :taskCard ="taskCard"
        :listIndex="listIndex"
    />
  </v-card>
 
</template>

<script>
import TaskCardAdd from "./TaskCardAdd.vue"
import TaskCard from './TaskCard.vue'

export default {
    name:'List',
    components: {
        TaskCardAdd,
        TaskCard,
    },
    props:{
        name:{
            type:String,
            required: true
        },
        listIndex: {
            type:Number,
            required: true
        },
        taskCards: {
            type: Array
        }
    },
    data(){
        return{
            
        }
    },
    methods:{
       async removeList(){
            if(confirm('リストを削除するとリスト内のタスクも削除されますがよろしいでしょうか?')){
                await axios.delete('/api/task-list/' + this.listIndex) 
				     .then(response => {
				     	alert(response.data.message);
                        this.$store.dispatch('task/taskListsGet')
				     })
				     .catch(error => {
                         console.log(error);
                         alert('削除に失敗しました');
                     })
            }
        },
    },
}
</script>

<style lang="scss" scoped>
    .v-card__title span.on-hover{
        cursor: pointer;
    }
</style>