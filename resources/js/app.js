import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from '@/router'
import '../css/app.css'

import PrimeVue from 'primevue/config'
import 'primevue/resources/themes/lara-light-purple/theme.css'
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'
import 'chart.js/auto'
import Chart from 'primevue/chart'
import DataTable from 'primevue/datatable'
import Card from 'primevue/card'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Calendar from 'primevue/calendar'

const app = createApp(App)

app.use(PrimeVue)
app.component('Chart', Chart)
app.component('Card', Card)
app.component('DataTable', DataTable)
app.component('Column', Column)
app.component('InputText', InputText)
app.component('Calendar', Calendar)

app.use(createPinia())
app.use(router)
app.mount('#app')
