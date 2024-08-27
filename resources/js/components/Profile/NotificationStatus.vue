<template>
    <!-- Container for the toggle button -->
    <div class="toggle-container">
        <!-- Toggle button that changes appearance based on the 'isActive' state -->
        <button
            class="toggle-button"
            :class="{ 'bg-green-500': isActive, 'bg-red-300': !isActive }"
            @click="toggle"
        >
            <!-- Display 'ON' or 'OFF' based on the 'isActive' state -->
            {{ isActive ? 'ON' : 'OFF' }}
        </button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            isActive: false, // State to track if the toggle is active or not
        };
    },
    methods: {
        // Method to toggle the isActive state and save the status to the server
        async toggle() {
            this.isActive = !this.isActive;
            await this.saveStatus();
        },
        // Method to send the updated status to the server
        async saveStatus() {
            try {
                await axios.patch('/profile/status', {
                    isActive: this.isActive, // Send the current state
                });
            } catch (error) {
                console.error('Error Saving Status', error);
            }
        },
    },
    async mounted() {
        // Fetch the initial status when the component is mounted
        try {
            const response = await axios.get('/profile/status');
            this.isActive = response.data.is_active; // Set the initial state based on server response
        } catch (error) {
            console.error('Error Fetching Profile', error);
        }
    },
};

</script>

<style scoped>
/* Style for the toggle button */
.toggle-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 4rem;
    height: 2rem;
    border-radius: 9999px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    color: #fff;
    transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
}

/* Background color when isActive is true */
.bg-green-500 {
    background-color: #4aeb76;
}

/* Background color when isActive is false */
.bg-red-300 {
    background-color: #eb4a4a;
}

/* Scale effect on hover */
.toggle-button:hover {
    transform: scale(1);
}
</style>
