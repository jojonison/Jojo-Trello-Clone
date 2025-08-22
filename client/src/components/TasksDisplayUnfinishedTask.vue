<script setup lang="ts">
import {inject, PropType, ref} from "vue";
import EditTasks from "@/components/EditTasks.vue";
import TaskStatusDropdown from "@/components/TaskStatusDropdown.vue";
import { AxiosInstance } from "axios";
import type { Task } from "@/types/models/task";

const props = defineProps({
    tasks: {
        type: Array as PropType<Array<Task>>,
        required: true
    }
});

const emit = defineEmits(["taskCompleted", "taskRemoved"]);
const editingTask = ref<Task | null>(null);
const injectedApi = inject<AxiosInstance>("api");
if (!injectedApi) {
  throw new Error("API instance not provided");
}
const api = injectedApi;

function markAsDone(id: number | unknown) {
    api.patch(`/task-complete/${id}`)
        .then((response) => {
            emit("taskCompleted", id, 1, response);
        });
}

function removeTask(id: number | unknown) {
    api.delete(`/removeTask/${id}`)
        .then(() => {
            emit("taskRemoved", id, 0, null);
        });
}

function startEditing(task: Task) {
    editingTask.value = task;
}

function cancelEditing() {
    editingTask.value = null;
}

function finishEditing(updated: Task) {
    api.put(`/task-update/${updated.id}`, {
        title: updated.title,
        description: updated.description,
    }).then(() => {
        editingTask.value = null;
        const idx = props.tasks.findIndex(t => t.id === updated.id);
        if (idx !== -1) {
            props.tasks[idx] = { ...props.tasks[idx], ...updated };
        }
    });
}
</script>

<template>
    <div class="w-full border-4 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100/90 text-yellow-900">
        <label class="block mb-2 text-yellow-800 font-bold">Unfinished Tasks:</label>
        <div v-if="props.tasks.length === 0" class="text-yellow-800 italic">Got nothing to do :^]</div>
        <div v-for="task in props.tasks" :key="task.id"
            class="w-full border-4 border-yellow-700 rounded-md px-3 py-2 bg-yellow-50 text-yellow-900 mt-2">
            <EditTasks v-if="editingTask && editingTask.id === task.id" :task="task" @submit="finishEditing"
                @cancel="cancelEditing" />
            <div v-else class="flex flex-col wrap-anywhere">
                <span class="font-bold">Title:</span> {{ task.title }}
                <span class="font-bold">Description:</span> {{ task.description }}
                <span class="font-bold">Status: </span>
                <TaskStatusDropdown :task="task"/>
                <div class="flex flex-wrap gap-2 mt-2">
                    <button @click="markAsDone(task.id)"
                        class="border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-pink-800">
                        ✔️ Mark as Done
                    </button>
                    <button @click="startEditing(task)"
                        class="border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-pink-800">
                        📝 Edit
                    </button>
                    <button @click="removeTask(task.id)"
                        class="border-2 border-yellow-700 rounded-md px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-pink-800">
                        🗑️ Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
