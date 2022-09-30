import { createRouter, createWebHistory } from 'vue-router'
import PropertiesView from '../views/PropertiesView.vue'

const routes = [
  {
    path: '/',
    name: 'properties',
    component: PropertiesView
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
