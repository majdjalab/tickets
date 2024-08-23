<template>
    <div class="styler p-6 self-center items-center text-white rounded-lg flex flex-col">
        <label>Add Category</label>
        <form action="/categories" method="POST">
            <input type="hidden" name="_token" :value="csrfToken">
            <input type="text"
                   v-model="categoryName"
                   name="categoryName"
                   placeholder="Category Name"
                   class="border-gray-300 mt-4 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full" />
            <textarea
                v-model="categoryDescription"
                name="categoryDescription"
                placeholder="Category Description"
                class="border-gray-300 mt-4 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"></textarea>
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
            categoryName: "",
            categoryDescription: "",
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

        };
    },
};
</script>

<style>
.styler {
    background-color: #1F2937;
    width: fit-content;
}
</style>
