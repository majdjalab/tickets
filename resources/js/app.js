import './bootstrap';
import { createApp } from 'vue';
import VueCalender from "./components/VueCalender.vue";
import CategoryForm from "./components/CategoryForm.vue"
import CategoryDisplay from "./components/CategoryDisplay.vue";
import Alpine from 'alpinejs';
import VCalendar from 'v-calendar';
import 'v-calendar/style.css';


window.Alpine = Alpine;

Alpine.start();

const app = createApp({});
app.component('vue-calender', VueCalender)
app.component('category-form', CategoryForm)
app.component('category-display', CategoryDisplay)

app.use(VCalendar, {})

app.mount('#app');
