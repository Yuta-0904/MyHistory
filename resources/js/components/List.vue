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
    <TaskCard v-for="taskCard in taskCards"
        :key="taskCard.id"
        :taskCard ="taskCard"
        :listIndex="listIndex"
    />
  </v-card>
 
</template>

<script>
// import CardAdd from "./CardAdd.vue"
import TaskCard from './TaskCard.vue'

export default {
    name:'List',
    components: {
        // CardAdd,
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
        removeList(){
            if(confirm('本当にこのリストを削除しますか?')){
                // this.$store.dispatch('removelist',{listIndex: this.listIndex})
                console.log('DELETE');
            }
        },
    },
    // computed:{
    //     taskCardProp() {
    //         console.log(this.taskCards);
    //     }
    // }
}
</script>

<style lang="scss" scoped>
    .v-card__title span.on-hover{
        cursor: pointer;
    }
</style>