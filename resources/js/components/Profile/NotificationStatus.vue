<template>
    <div class="toggle-container">
        <button
            class="toggle-button"
            :class="{ 'bg-green-500': isActive, 'bg-red-300': !isActive }"
            @click="toggle"
        >
            {{ isActive ? 'ON' : 'OFF' }}
        </button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            isActive: false,
        };
    },
    methods: {
        async toggle() {
            this.isActive = !this.isActive;
            await this.saveStatus();
        },
        async saveStatus() {
            try {
                await axios.patch('/profile/status', {
                    isActive: this.isActive,
                });
            } catch (error) {
                console.error('Error Saving Status', error);
            }
        },
    },
    async mounted() {
        try {
            const response = await axios.get('/profile/status');
            this.isActive = response.data.is_active;
        } catch (error) {
            console.error('Error Fetching Profile', error);
        }
    },
};

</script>

<style scoped>
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

.bg-green-500 {
    background-color: #4aeb76;
}

.bg-red-300 {
    background-color: #eb4a4a;
}

.toggle-button:hover {
    transform: scale(1);
}
</style>
