<template>
    <div>
        <button
            type="button"
            @click="togglePopover"
            class="flex px-3 items-center py-2 text-sm font-semibold border border-orange-800 rounded-full"
        >
            <img class="w-6 h-6 mr-2" src="/calender.png" alt="Calendar icon" />
            <span
                :class="{
                    'text-yellow-600': isToday,
                    'text-red-600': isYesterday,
                    'text-green-600': isTomorrow,
                    'text-white': !isToday && !isYesterday && !isTomorrow,
                }"
            >
                {{
                    isToday
                        ? 'Today'
                        : isYesterday
                            ? 'Yesterday'
                            : isTomorrow
                                ? 'Tomorrow'
                                : formattedDate || 'No Due date'
                }}
            </span>
        </button>
        <VCalendar
            ref="calendar"
            is-dark="system"
            v-if="isPopoverVisible"
            class="popover-content"
            v-model="selectedDate"
            @dayclick="onDateSelect"
        >
            <template #footer>
                <div class="w-full flex px-4 pb-3">
                    <button
                        class="bg-green hover:bg-indigo-700 text-white font-bold w-full px-3 py-1 rounded-md"
                        @click.prevent="submitDate"
                    >
                        Submit
                    </button>
                    <button
                        type="button"
                        @click="togglePopover"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold w-full px-3 py-1 rounded-md"
                    >
                        Close
                    </button>
                </div>
            </template>
        </VCalendar>
    </div>
</template>

<script setup lang="ts">
import { defineProps, ref, computed } from 'vue';
import axios from 'axios';

// Define props
const props = defineProps<{
    ticketId: number;
}>();

const ticketId = ref(props.ticketId);
const selectedDate = ref<Date | null>(null);

// Date formatting logic for display
const today = new Date();
const currentDate = today.toDateString();
const yesterday = new Date(today.getTime() - 24 * 60 * 60 * 1000).toDateString();
const tomorrow = new Date(today.getTime() + 24 * 60 * 60 * 1000).toDateString();

const formattedDate = computed(() =>
    selectedDate.value ? new Intl.DateTimeFormat('en-US').format(selectedDate.value) : ''
);
const isYesterday = computed(() =>
    selectedDate.value ? selectedDate.value.toDateString() === yesterday : false
);
const isTomorrow = computed(() =>
    selectedDate.value ? selectedDate.value.toDateString() === tomorrow : false
);
const isToday = computed(() =>
    selectedDate.value ? selectedDate.value.toDateString() === currentDate : false
);

const isPopoverVisible = ref(false);

const togglePopover = () => {
    isPopoverVisible.value = !isPopoverVisible.value;
};

const onDateSelect = (day: { date: Date }) => {
    selectedDate.value = day.date;
    console.log('Date selected:', selectedDate.value);
};

// Format date to 'YYYY/MM/DD' for backend
const formatDateForBackend = (date: Date): string => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}/${month}/${day}`; // Convert to 'YYYY/MM/DD'
};

const submitDate = async () => {
    if (!ticketId.value) {
        console.error('ticketId is not defined');
        return;
    }

    if (!selectedDate.value) {
        console.error('No date selected');
        return;
    }

    const formattedDateForBackend = formatDateForBackend(selectedDate.value);

    try {
        const response = await axios.post(`/ticket/${ticketId.value}/due-date`, {
            date: formattedDateForBackend, // Send the 'YYYY/MM/DD' format
        });
        console.log('Submitted Date:', formattedDateForBackend);
    } catch (error) {
        console.error('Error:', error);
    }

    togglePopover();
};


</script>

<style scoped>
</style>
