import './bootstrap';
import { createApp } from 'vue';
import VueCalender from "./components/VueCalender.vue";
import CategoryForm from "./components/Categories/CategoryForm.vue"
import CategoryDisplay from "./components/Categories/CategoryDisplay.vue";
import NotificationStatus from "./components/Profile/NotificationStatus.vue";
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

app.use(VCalendar, {})

app.mount('#app');
