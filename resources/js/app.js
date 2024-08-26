import './bootstrap';
import '../css/app.css';
import { createApp } from 'vue';
import VueCalender from "./components/VueCalender.vue";
import CategoryForm from "./components/Categories/CategoryForm.vue"
import CategoryDisplay from "./components/Categories/CategoryDisplay.vue";
import NotificationStatus from "./components/Profile/NotificationStatus.vue";
import CategoriesList from "./components/Categories/CategoriesList.vue";
import Alpine from 'alpinejs';
import VCalendar from 'v-calendar';
import 'v-calendar/style.css';


window.Alpine = Alpine;

Alpine.start();

const app = createApp({});
app.component('vue-calender', VueCalender)
app.component('category-form', CategoryForm)
app.component('category-display', CategoryDisplay)
app.component('notification-button', NotificationStatus)
app.component('category-list', CategoriesList);

app.use(VCalendar, {})

app.mount('#app');
