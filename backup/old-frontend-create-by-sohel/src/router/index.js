import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../components/admin/home/LoginView.vue'
import ProjectOne from '../pages/project/ProjectOne.vue'
import ProjectTwo from '../pages/project/ProjectTwo.vue'
import ProjectThree from '../pages/project/ProjectThree.vue'
import Support from '../pages/support/SupportIndex.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../components/admin/template/AdminTemplate.vue'),
      children: [
        {
          path: 'hr',
          name: 'hr',
          component: () => import('../pages/hr/HrIndex.vue')
        },
        {
          path: 'project-1',
          name: 'project_1',
          component: ProjectOne
        },
        {
          path: 'project-2',
          name: 'project_2',
          component: ProjectTwo
        },
        {
          path: 'project-3',
          name: 'project_3',
          component: ProjectThree
        },
        {
          path: 'work-report-one',
          name: 'work_report_one',
          component: () => import('../pages/work_report/WorkReportOne.vue')
        },
        {
          path: 'work-report-two',
          name: 'work_report_two',
          component: () => import('../pages/work_report/WorkReportTwo.vue')
        },
        {
          path: 'work-report-three',
          name: 'work_report_three',
          component: () => import('../pages/work_report/WorkReportThree.vue')
        },
        {
          path: 'support',
          name: 'support',
          component: Support
        },
        {
          path: 'create-modal',
          name: 'create_modal',
          component: () => import('../modals/CreateModal.vue')
        }
      ]
    }
  ]
})

export default router
