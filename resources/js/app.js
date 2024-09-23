import './bootstrap.js';
import { createApp } from 'vue';


const app = createApp({});

import ImageList from './components/ImageList.vue';
app.component('image-list', ImageList);

app.mount('#app');
