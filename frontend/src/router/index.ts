import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import AuthLayout from '../layouts/AuthLayout.vue'
import AppLayout from '../layouts/AppLayout.vue'

import RouteViewComponent from '../layouts/RouterBypass.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/:pathMatch(.*)*',
    redirect: { name: 'dashboard' },
  },
  {
    name: '',
    path: '/',
    component: AppLayout,
    redirect: { name: 'dashboard' },
    children: [
      {
        name: 'dashboard',
        path: 'dashboard',
        component: () => import('../pages/admin/dashboard/Dashboard.vue'),
      },
      {
        name: 'security-access',
        path: '/security-access',
        component: RouteViewComponent,
        children: [
          {
            name: 'users',
            path: 'users',
            component: RouteViewComponent,
            children: [
              {
                name: 'temporary-user',
                path: 'temporary-user',
                component: () => import('../pages/hr/organization/OrganizationPage.vue'),
              },
              {
                name: 'permanent-user',
                path: 'permanent-user',
                component: () => import('../pages/sa/permission/PermissionPage.vue'),
              }
            ]
          },
          {
            name: 'role',
            path: 'role',
            component: () =>  import('../pages/sa/role/RolePage.vue'),
          },
          {
            name: 'permission',
            path: 'permission',
            component: RouteViewComponent,
            children: [
              {
                name: 'temporary-permission',
                path: 'temporary-permission',
                component: () => import('../pages/sa/permission/PermissionPage.vue'),
              },
              {
                name: 'permanent-permission',
                path: 'permanent-permission',
                component: () => import('../pages/sa/permission/PermissionPage.vue'),
              },
            ]
          },
        ],
      },
      {
        name: 'hr',
        path: 'hr',
        component: RouteViewComponent,
        children: [
          {
            name: 'employees',
            path: 'employees',
            component: () => import('../pages/hr/employee/EmployeesPage.vue'),
          },
          {
            name: 'organizations',
            path: 'organizations',
            component: () => import('../pages/hr/organization/OrganizationPage.vue'),
          }
        ]
      },
      {
        name: 'settings',
        path: 'settings',
        component: () => import('../pages/settings/Settings.vue'),
      },
    ],
  },
  {
    path: '/auth',
    component: AuthLayout,
    children: [
      {
        name: 'login',
        path: 'login',
        component: () => import('../pages/auth/Login.vue'),
      },
      {
        name: 'signup',
        path: 'signup',
        component: () => import('../pages/auth/Signup.vue'),
      },
      {
        name: 'recover-password',
        path: 'recover-password',
        component: () => import('../pages/auth/RecoverPassword.vue'),
      },
      {
        name: 'recover-password-email',
        path: 'recover-password-email',
        component: () => import('../pages/auth/CheckTheEmail.vue'),
      },
      {
        path: '',
        redirect: { name: 'login' },
      },
    ],
  },
  {
    name: 'logout',
    path: '/logout',
    redirect: 'login',
  },
  {
    name: 'preferences',
    path: '/preferences',
    component: () => import('../pages/preferences/Preferences.vue')
  },
  {
    name: '404',
    path: '/404',
    component: () => import('../pages/error/404.vue')
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }
    // For some reason using documentation example doesn't scroll on page navigation.
    if (to.hash) {
      return { el: to.hash, behavior: 'smooth' }
    } else {
      window.scrollTo(0, 0)
    }
  },
  routes,
})

export default router
