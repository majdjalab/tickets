<template>
    <div class="relative inline-block w-full">
        <button
            type="button"
            class="border border-gray-700 text-left p-2 dark:bg-gray-900 w-full h-10 text-white rounded-md shadow-sm"
            @click="toggleDropdown"></button>
            <div
                v-if="isDropdownOpen"
                class="overflow-auto bg-gray-900 text-white w-full mt-1 rounded-md shadow-lg"
                style="max-height: 240px; overflow-y: auto;">

                <div
                    v-for="category in categories"
                    :key="category.id"
                    class="p-2 flex items-center"
                >
                    <input
                        type="checkbox"
                        :id="category.id"
                        :value="category.id"
                        v-model="selectedValues"
                        class="mr-2"
                    />
                    <label :for="category.id">{{ category.name }}</label>
                </div>
            </div>

            <input type="hidden" name="categories" :value="selectedValues.join(',')" />
    </div>
</template>


<script>

export default {
    props: ['categories', 'selected'],
    data() {
        return{
            selectedValues:this.selected,
            isDropdownOpen: false,
        }
    },
    computed:{
        selectefItems(){
            return this.categories.filter(category =>
                this.selectedValues.includes(category.id.toString()));
        }
    },
    methods: {
        toggleDropdown(){
            this.isDropdownOpen = !this.isDropdownOpen
        }
    }
}

</script>

<style>
</style>
