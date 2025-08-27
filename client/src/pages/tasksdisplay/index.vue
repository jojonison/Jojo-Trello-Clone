<script setup lang="ts">

import { provide, inject, ref, onMounted } from "vue";
import Logout from "@/layouts/Logout.vue";
import TasksDisplayFinishedTask from "@/components/TasksDisplayFinishedTask.vue";
import TasksDisplayUnfinishedTask from "@/components/TasksDisplayUnfinishedTask.vue";
import { useAuthStore } from '@/store/auth';
import { AxiosInstance, AxiosResponse } from "axios";
import type { Task } from '@/types/models/task';
import ProjectBoard from "@/components/ProjectBoard.vue";
import { Project } from "@/types/models/project";
import * as z from 'zod';
import {useToast} from "@nuxt/ui/composables/useToast.js";

const TaskInput = z.object({
  title: z.string().min(1),
  description: z.string().max(255)
})

const selectedProject = ref<Project>();
const tasks = ref<Task[]>([]);
const finishedTasks = ref<Task[]>([]);
const title = ref<string>("");
const description = ref<string>("");
const loading = ref<boolean>(false);
const injectedApi = inject<AxiosInstance>("api");
if (!injectedApi) {
  throw new Error("API instance not provided");
}
const api = injectedApi;
const auth = useAuthStore();
const toast = useToast();

provide("finishedTasks", finishedTasks);

onMounted(() => {
    if (!selectedProject.value) { return }
    loadTasks();
    loadFinishedTasks();
});
function loadTasks() {
    if (!auth.isLoggedIn) {return}
    loading.value = true;
    api
        .get(`/unfinished-tasks`, {
            params: {
                project_id: selectedProject.value?.id,
            },
        })
        .then((response) => {
            tasks.value = response.data;
        })
        .catch()
        .finally(() => {
            loading.value = false;
        });
}

function loadFinishedTasks() {
    if (!auth.isLoggedIn) {return}
    api
        .get(`/finished-tasks`, {
          params: {project_id: selectedProject.value?.id}
        })
        .then((response) => {
            finishedTasks.value = response.data;
        })
        .catch((error) => console.error(error));
}

function addTask(task: Task) {
    const result = TaskInput.safeParse({title: task.title, description: task.description})
    if (!result.success) {
      toast.add({title: 'Invalid', description: 'Title cannot be Empty', color:'error'});
    } else {
      api
          .post("/create-task", {
            title: title.value,
            description: description.value,
            project_id: selectedProject.value?.id
          })
          .then(function (response: AxiosResponse) {
            toast.add({title: 'Success', description: 'Task added successfully'})
            title.value = "";
            description.value = "";
            tasks.value.push(response.data[0]); // TODO watch if the tasks array logic is broken
            // loadTasks();
          })
    }

}

function removeTaskFromList(id: number, isComplete: boolean, response: AxiosResponse) {
    const index = tasks.value.findIndex(task => task.id === id);
    if (isComplete) {
        finishedTasks.value.push(response.data[0])
    }
    if (index !== -1) {
        tasks.value.splice(index, 1);
    }
}

function removeFinishedTaskFromList(id: number) {
    const index = finishedTasks.value.findIndex(task => task.id === id);
    if (index !== -1) {
        finishedTasks.value.splice(index, 1);
    }
}

function updateSelectedProject(givenProject: Project) {
  selectedProject.value = givenProject;
  loadTasks();
  loadFinishedTasks();
}
</script>

<template>
    <ProjectBoard @selectedProject="updateSelectedProject"/>
    <div class="
    flex flex-col items-start justify-center gap-3.5
    border-[8px] border-yellow-800 bg-amber-100
    p-3
    ">
        <div v-if="!auth.isLoggedIn">
            <h1 class="block mb-2 text-yellow-800 font-bold font-mono text-6xl w-full">You might not be logged in</h1>
        </div>
        <div v-else>
            <div v-if="loading" class="block mb-2 text-yellow-800 font-bold font-mono text-6xl w-full">
                <h1>Loading...</h1>
            </div>
        </div>

        <div v-if="auth.isLoggedIn" class="ml-auto">
            <Logout />
        </div>

        <div v-if="auth.isLoggedIn"
            class="flex flex-col w-full border-4 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100/90 text-yellow-900">
            <label class="text-yellow-800">Title</label>
            <input
                @keyup.enter="addTask({ title, description })"
                type="text"
                placeholder="Enter title:"
                class="w-full h-auto border-4 border-yellow-700 rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-yellow-400 bg-yellow-50 text-yellow-900 placeholder-yellow-700"
                v-model="title" />
            <label class="block mb-2 text-yellow-800">Description</label>
            <input
                @keyup.enter="addTask({ title, description })"
                type="text"
                placeholder="Enter description:"
                class="w-full border-4 border-yellow-700 rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-yellow-400 bg-yellow-50 text-yellow-900 placeholder-yellow-700"
                v-model="description" />
            <div class="flex justify-center-safe">
                <button
                    @click="addTask({ title, description })"
                    class="flex justify-center-safe max-w-6/12 border-4 border-yellow-700 my-2 rounded-md px-3 py-2 bg-yellow-200 hover:bg-yellow-500 text-pink-800 text-3xl">
                    ➕ Add Task
                </button>
            </div>
        </div>
        <TasksDisplayUnfinishedTask
            :tasks="tasks"
            @taskCompleted="removeTaskFromList"
            @taskRemoved="removeTaskFromList" />
        <TasksDisplayFinishedTask @taskRemoved="removeFinishedTaskFromList" />
    </div>
</template>