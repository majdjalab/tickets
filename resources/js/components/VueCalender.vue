<template>
    <div>
        <!-- Button to toggle the popover calendar -->
        <button
            type="button"
            @click="togglePopover"
            class="flex px-3 items-center py-2 text-sm font-semibold border rounded-full"
        >
            <!-- Calendar icon -->
            <img class="w-6 h-6 mr-2" src="/calender.png" alt="Calendar icon" />

            <!-- Display the appropriate date label (Today, Yesterday, Tomorrow, or formatted date) -->
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

        <!-- VCalendar component for selecting a date -->
        <VCalendar
            ref="calendar"
            is-dark="system"
            v-if="isPopoverVisible"
            class="popover-content"
            v-model="selectedDate"
            @dayclick="onDateSelect"
        >
            <!-- Footer buttons for submitting or closing the date picker -->
            <template #footer>
                <div class="w-full flex px-4 pb-3">
                    <button
                        class="bg-green-600 text-white mr-2 font-bold w-full px-3 py-1 rounded-md"
                        @click.prevent="submitDate"
                    >
                        Submit
                    </button>
                    <button
                        type="button"
                        @click="togglePopover"
                        class="bg-red-600 hover:bg-red-700 ml-2 text-white font-bold w-full px-3 py-1 rounded-md"
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

// Define props to accept a ticketId from the parent component
const props = defineProps<{
    ticketId: number;
}>();

const ticketId = ref(props.ticketId);  // Reactive reference to the ticket ID
const selectedDate = ref<Date | null>(null);  // Reactive reference to the selected date

// Define dates for today, yesterday, and tomorrow
const today = new Date();
const currentDate = today.toDateString();
const yesterday = new Date(today.getTime() - 24 * 60 * 60 * 1000).toDateString();
const tomorrow = new Date(today.getTime() + 24 * 60 * 60 * 1000).toDateString();

// Computed property to format the selected date for display
const formattedDate = computed(() =>
    selectedDate.value ? new Intl.DateTimeFormat('en-US').format(selectedDate.value) : ''
);

// Computed properties to determine if the selected date is today, yesterday, or tomorrow
const isYesterday = computed(() =>
    selectedDate.value ? selectedDate.value.toDateString() === yesterday : false
);
const isTomorrow = computed(() =>
    selectedDate.value ? selectedDate.value.toDateString() === tomorrow : false
);
const isToday = computed(() =>
    selectedDate.value ? selectedDate.value.toDateString() === currentDate : false
);

const isPopoverVisible = ref(false);  // Reactive reference to control the visibility of the popover

// Function to toggle the visibility of the popover calendar
const togglePopover = () => {
    isPopoverVisible.value = !isPopoverVisible.value;
};

// Function to handle date selection from the calendar
const onDateSelect = (day: { date: Date }) => {
    selectedDate.value = day.date;
    console.log('Date selected:', selectedDate.value);
};

// Function to format the selected date for backend submission
const formatDateForBackend = (date: Date): string => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}/${month}/${day}`; // Convert to 'YYYY/MM/DD'
};

// Function to submit the selected date to the backend
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
        // Send the formatted date to the backend via a POST request
        const response = await axios.post(`/ticket/${ticketId.value}/due-date`, {
            date: formattedDateForBackend, // Send the 'YYYY/MM/DD' format
        });
        console.log('Submitted Date:', formattedDateForBackend);
    } catch (error) {
        console.error('Error:', error);
    }

    // Close the popover after submission
    togglePopover();
};

</script>

<style scoped>
/* Add your scoped styles here if needed */
</style>
