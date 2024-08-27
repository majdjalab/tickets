<template>
    <div class="displayer p-6 mt-4 self-center items-center text-white rounded-lg flex flex-col">
        <h1>All Categories</h1>
        <table class="border-separate border border-slate-400 mt-4">
            <thead>
            <tr>
                <th class="border border-slate-300 px-4 py-2">Category Name</th>
                <th class="border border-slate-300 px-4 py-2">Category Description</th>
                <th class="border border-slate-300 px-4 py-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="category in categories" :key="category.id">
                <td class="border border-slate-300 px-4 py-2">
                    <template v-if="editingCategory && editingCategory.id === category.id">
                        <input v-model="editedCategory.name" class="edit-input"/>
                    </template>
                    <template v-else>
                        {{ category.name }}
                    </template>
                </td>
                <td class="border border-slate-300 px-4 py-2">
                    <template v-if="editingCategory && editingCategory.id === category.id">
                        <input v-model="editedCategory.description" class="edit-input"/>
                    </template>
                    <template v-else>
                        {{ category.description }}
                    </template>
                </td>
                <td class="border border-slate-300 px-4 py-2 flex justify-around">
                    <button @click="deleteCategory(category.id)" class="delete-button">
                        <img src="https://cdn-icons-png.flaticon.com/128/9790/9790368.png" class="h-6"/>
                    </button>
                    <template v-if="editingCategory && editingCategory.id === category.id">
                        <button @click="updateCategory(category.id)" class="save-button">
                            <img src="https://cdn-icons-png.flaticon.com/128/2716/2716054.png" class="h-6"/>
                        </button>
                        <button @click="cancelEdit" class="cancel-button">
                            <img src="https://cdn-icons-png.flaticon.com/128/1828/1828665.png" class="h-6"/>
                        </button>
                    </template>

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
    data() {
        return {
            editingCategory: null,
            editedCategory: {
                name: '',
                description: '',
            },
        };
    },
    methods: {
        deleteCategory(id) {
            axios.delete(`/categories/${id}`)
                .then(response => {
                    this.$emit('category-deleted', id);
                })
                .catch(error => {
                    console.error('Error deleting category:', error);
                });
            location.reload();
        },
    }
};
</script>

<style>
.displayer {
    background-color: #1F2937;
    width: fit-content;
}

.delete-button, .edit-button, .save-button, .cancel-button {
    background-color: transparent;
    border: none;
    cursor: pointer;
    color: white;
    padding: 4px;
    margin: 0 4px;
}

.edit-input {
    background-color: #2D3748;
    color: white;
    border: 1px solid #4A5568;
    padding: 4px;
    border-radius: 4px;
}

.save-button:hover img {
    filter: brightness(0.8) sepia(1) hue-rotate(120deg);
}

.cancel-button:hover img {
    filter: brightness(0.8) sepia(1) hue-rotate(60deg);
}

.delete-button:hover img {
    filter: brightness(0.8) sepia(1) hue-rotate(0deg);
}

.edit-button:hover img {
    filter: brightness(0.8) sepia(1) hue-rotate(180deg);
}

table {
    width: 100%;
    margin-top: 1rem;
}

th, td {
    text-align: left;
}
</style>
