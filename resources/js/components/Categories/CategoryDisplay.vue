<template>
    <div class="displayer p-6 mt-4 self-center items-center text-white rounded-lg flex flex-col">
        <h1>All Categories</h1>
        <!-- Table to display the list of categories -->
        <table class="border-separate border border-slate-400 mt-4">
            <thead>
            <tr>
                <th class="border border-slate-300 px-4 py-2">Category Name</th>
                <th class="border border-slate-300 px-4 py-2">Category Description</th>
                <th class="border border-slate-300 px-4 py-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            <!-- Loop through the categories array passed as a prop -->
            <tr v-for="category in categories" :key="category.id">
                <td class="border border-slate-300 px-4 py-2">
                    {{ category.name }}
                </td>
                <td class="border border-slate-300 px-4 py-2">
                    {{ category.description }}
                </td>
                <td class="border border-slate-300 px-4 py-2 flex justify-around">
                    <!-- Button to delete the category -->
                    <button @click="deleteCategory(category.id)" class="delete-button">
                        <img src="https://cdn-icons-png.flaticon.com/128/9790/9790368.png" class="h-6"/>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        categories: {
            type: Array,
            required: true,
        },
    },
    methods: {
        // Method to delete a category by its ID
        deleteCategory(id) {
            axios.delete(`/categories/${id}`)
                .then(response => {
                    // Emit an event to the parent component that the category has been deleted
                    this.$emit('category-deleted', id);
                })
                .catch(error => {
                    console.error('Error deleting category:', error);
                });
            location.reload(); // Refresh the page to update the list
        },
    }
};
</script>

<style>
.displayer {
    background-color: #1F2937;
    width: fit-content;
}

.delete-button {
    background-color: transparent;
    border: none;
    cursor: pointer;
    color: white;
    padding: 4px;
    margin: 0 4px;
}

.delete-button:hover img {
    filter: brightness(0.8) sepia(1) hue-rotate(0deg);
}

table {
    width: 100%;
    margin-top: 1rem;
}

th, td {
    text-align: left;
}
</style>
