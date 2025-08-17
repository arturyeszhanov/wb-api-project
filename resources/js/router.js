import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '@/pages/Dashboard.vue'
import MetricDetail from '@/pages/MetricDetail.vue'
import ProductDetail from '@/pages/ProductDetail.vue'

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard,
  },
  {
    path: '/total_price',
    name: 'TotalPrice',
    component: MetricDetail,
    props: { metricKey: 'total_price' },
  },
  {
    path: '/discount_percent',
    name: 'DiscountPercent',
    component: MetricDetail,
    props: { metricKey: 'discount_percent' },
  },
  {
    path: '/is_cancel',
    name: 'IsCancel',
    component: MetricDetail,
    props: { metricKey: 'is_cancel' },
  },
  {
    path: '/income_id',
    name: 'IncomeId',
    component: MetricDetail,
    props: { metricKey: 'income_id' },
  },
  {
    path: '/product/:nmId',
    name: 'ProductDetail',
    component: ProductDetail,
    props: true, // пробросит nmId как пропс
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
