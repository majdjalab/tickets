<template>
    <div class="styler p-6 self-center items-center text-white rounded-lg flex flex-col">
        <label>Add Category</label>
        <!-- Form to submit new category data -->
        <form action="/categories" method="POST">
            <!-- Hidden input to include the CSRF token for security -->
            <input type="hidden" name="_token" :value="csrfToken">

            <!-- Input for entering the category name, bound to 'categoryName' data -->
            <input type="text"
                   v-model="categoryName"
                   name="categoryName"
                   placeholder="Category Name"
                   class="border-gray-300 mt-4 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full" />

            <!-- Textarea for entering the category description, bound to 'categoryDescription' data -->
            <textarea
                v-model="categoryDescription"
                name="categoryDescription"
                placeholder="Category Description"
                class="border-gray-300 mt-4 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"></textarea>

            <!-- Submit button for the form -->
            <button type="submit"
                    class="bg-gray-50 text-black w-full font-bold mt-4 px-3 py-1 rounded-md">
                Submit
            </button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            categoryName: "", // Model for the category name input
            categoryDescription: "", // Model for the category description textarea
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Retrieve CSRF token from meta tag
        };
    },
};
</script>

<style>
/* Custom styling for the container div */
.styler {
    background-color: #1F2937; /* Dark background color */
    width: fit-content; /* Width adjusts to content size */
}
</style>
