<template>
    <div class="relative inline-block w-full">
        <!-- Button to toggle the dropdown -->
        <button
            type="button"
            class="border border-gray-700 text-left p-2 dark:bg-gray-900 w-full h-10 text-white rounded-md shadow-sm"
            @click="toggleDropdown"></button>

        <!-- Dropdown menu that appears when isDropdownOpen is true -->
        <div
            v-if="isDropdownOpen"
            class="overflow-auto bg-gray-900 text-white w-full mt-1 rounded-md shadow-lg"
            style="max-height: 240px; overflow-y: auto;">

            <!-- Loop through categories and display each as a checkbox option -->
            <div
                v-for="category in categories"
                :key="category.id"
                class="p-2 flex items-center">

                <!-- Checkbox for selecting a category -->
                <input
                    type="checkbox"
                    :id="category.id"
                    :value="category.id"
                    v-model="selectedValues"
                    class="mr-2"
                />

                <!-- Label for the checkbox displaying the category name -->
                <label :for="category.id">{{ category.name }}</label>
            </div>
        </div>

        <!-- Hidden input field to store selected category IDs as a comma-separated string -->
        <input type="hidden" name="categories" :value="selectedValues.join(',')" />
    </div>
</template>

<script>
export default {
    // Props to receive categories and initially selected categories
    props: ['categories', 'selected'],

    data() {
        return {
            // Data property to hold the selected category IDs
            selectedValues: this.selected,
            // Boolean to control the dropdown visibility
            isDropdownOpen: false,
        };
    },

    computed: {
        // Computed property to filter and return selected categories based on selectedValues
        selectedItems() {
            return this.categories.filter(category =>
                this.selectedValues.includes(category.id.toString()));
        }
    },

    methods: {
        // Method to toggle the dropdown open/close state
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        }
    }
};
</script>

<style>
</style>
