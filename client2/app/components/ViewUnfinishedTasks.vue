<script setup lang="ts">
import type {AxiosResponse} from "axios";
import type {Task} from '~/utils/types/models/task';
import type {Project} from "~/utils/types/models/project";
const {$api} = useNuxtApp();
const unfinishedTasks = ref<Task[]>();
const toast = useToast();
const props = defineProps<{
  selectedProject?: Project
}>()
onMounted(() => {
  loadUnfinishedTasks();
});
function loadUnfinishedTasks() {
  if (props.selectedProject === undefined) return
  $api.get(`/unfinished-tasks`, {params: {project_id: props.selectedProject.id}})
      .then((response: AxiosResponse) => {
        unfinishedTasks.value = response.data;
      })
}

const statusOptions = ref([
  {label: '⏹️ Not Started', value: 'not started'},
  {label: '▶️ In Progress', value: 'in progress'},
  {label: '⏸️ On Hold', value: 'on hold'}
]);

function updateTaskStatus(task: Task) {
  $api.put(`/update-status/${task.id}`, {status: task.status}, {withCredentials: true})
  toast.add({title: 'Updated', description: 'Task Updated Successfully', color:'success'});
}

function markTaskAsDone(task: Task) {
  $api.patch(`/task-complete/${task.id}`)
      .then(() => {
        unfinishedTasks.value = unfinishedTasks.value?.filter(unfinishedTask => unfinishedTask.id !== task.id)
        toast.add({title: 'Marked as Done', description: 'Task Marked as Done Successfully', color:'success'});
      })
}

function removeTask(task: Task) {
  $api.delete(`/removeTask/${task.id}`)
      .then(() => {
        unfinishedTasks.value = unfinishedTasks.value?.filter(unfinishedTask => unfinishedTask.id !== task.id)
        toast.add({title: 'Removed', description: 'Task Removed Successfully', color:'success'});
      })
}
</script>

<template>
  <main class="bg-blue-400 border-[6px] border-b-blue-950 rounded-xl shadow-lg mx-5 my-2 w-full">
    <span class="font-bold flex justify-center text-4xl">Unfinished Tasks: </span>
    <div v-if="props.selectedProject === undefined" class="font-bold flex justify-center text-4xl">
      You must first select a project
    </div>
    <div v-else>
      <AddTask :selected-project="props.selectedProject" @task-added="unfinishedTasks?.push($event)"/>
      <div v-for="task in unfinishedTasks" :key="task.id" class="flex flex-col bg-blue-200 border-[2px] border-blue-950 p-2 text-blue-950">
        <div><span class="font-bold">Title:</span> {{ task.title }}</div>
        <div><span class="font-bold">Description:</span> {{ task.description }}</div>
        <div><span class="font-bold">Status:</span> {{ task.status }}</div>
        <USelect
          v-model="task.status"
          color="neutral"
          :items="statusOptions"
          @update:model-value="() => updateTaskStatus(task)"
        />
        <UButton
            color="neutral"
            class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300"
            @click="markTaskAsDone(task)"
        >
          ☑️ Mark as Done
        </UButton>
        <UButton
            color="neutral"
            class="border-[2px] border-blue-950 hover:bg-blue-400 bg-blue-300"
            @click="removeTask(task)"
        >
          🚮 Remove Task
        </UButton>
      </div>
    </div>
  </main>
</template>