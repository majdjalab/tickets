import './bootstrap';
import { createApp } from 'vue';
import VueButton from './components/VueButton.vue';
import VueCalender from "./components/VueCalender.vue";
import Modal from './components/Modal.vue';
import Alpine from 'alpinejs';
import VCalendar from 'v-calendar';
import 'v-calendar/style.css';

window.Alpine = Alpine;

Alpine.start();

const app = createApp({});
app.component('vue-calender', VueCalender)
app.use(VCalendar, {})

app.mount('#app');
