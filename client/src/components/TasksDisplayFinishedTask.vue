<script setup lang="ts">
import { inject, ref } from "vue";
import {AxiosInstance} from "axios";
import {Task} from "@/types/models/task";

const injectedApi = inject<AxiosInstance>("api");
if (!injectedApi) {
  throw new Error("API instance not provided");
}
const api = injectedApi;
const finishedTasks = inject<Task[]>("finishedTasks");
const hideFinishedTasks = ref<boolean>(true);
const emit = defineEmits(["taskRemoved"]);

function removeTask(id: number | undefined) {
    api.delete(`/removeTask/${id}`)
        .then(() => {
            emit("taskRemoved", id);
        });
}

function hideTheFinishedTasks() {
    hideFinishedTasks.value = !hideFinishedTasks.value;
}
</script>

<template>
    <div class="w-full border-4 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100/90 text-yellow-900">
        <label class="block mb-2 text-yellow-800 font-bold">Finished Tasks:</label>
        <button @click="hideTheFinishedTasks"
            class="w-full border-4 border-yellow-700 rounded-md px-3 py-2 bg-yellow-200 hover:bg-yellow-500 text-pink-800 text-2xl">
            ➖ Hide/Unhide Finished Tasks
        </button>
        <div v-if="hideFinishedTasks">
            <div v-for="finishedTask in finishedTasks" :key="finishedTask.id"
                class="flex flex-col w-full border-3 border-yellow-700 rounded-md px-3 py-2 bg-yellow-50 text-yellow-900 m-2 wrap-anywhere">
                <b>Title:</b> {{ finishedTask.title }}
                <b>Description:</b> {{ finishedTask.description }}
                <b>Finished On:</b> {{ finishedTask.completed_at }}
                <div>
                <button @click="removeTask(finishedTask.id)"
                    class="border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-500 text-pink-800">
                    🗑️ Remove
                </button>
            </div>
            </div>
            
        </div>
    </div>
</template>