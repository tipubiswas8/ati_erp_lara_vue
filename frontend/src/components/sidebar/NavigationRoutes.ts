export interface INavigationRoute {
  name: string
  displayName: string
  meta: { icon: string }
  children?: INavigationRoute[]
}

export default {
  root: {
    name: '/',
    displayName: 'navigationRoutes.home',
  },
  routes: [
    {
      name: 'dashboard',
      displayName: 'menu.dashboard',
      meta: {
        icon: 'vuestic-iconset-dashboard',
      },
    },
    {
      name: 'security-access',
      displayName: 'menu.security-access',
      meta: {
        icon: 'security',
      },
      children: [
        {
          name: 'users',
          displayName: 'menu.users',
          meta: {
            icon: 'group',
          },
        },
        {
          name: 'role',
          displayName: 'menu.role',
          meta: {
            icon: 'android',
          },
        },
        {
          name: 'permission',
          displayName: 'menu.permission',
          meta: {
            icon: 'android',
          },
        },
      ],
    },
    {
      name: 'hr',
      displayName: 'menu.hr',
      meta: {
        icon: 'group',
      },
      children: [
        {
          name: 'employees',
          displayName: 'menu.employees',
          meta: {
            icon: 'laptop',
          },
        }
      ],
    },
    {
      name: 'test',
      displayName: 'menu.test',
      meta: {
        icon: 'android',
      },
    },
    {
      name: 'projects',
      displayName: 'menu.projects',
      meta: {
        icon: 'credit_card',
      },
      children: [
        {
          name: 'payment-methods',
          displayName: 'menu.payment-methods',
          meta: {
            icon: 'android',
          },
        },
        {
          name: 'pricing-plans',
          displayName: 'menu.pricing-plans',
          meta: {
            icon: 'android',
          },
        },
        {
          name: 'billing',
          displayName: 'menu.billing',
          meta: {
            icon: 'android',
          },
        },
      ],
    },
    {
      name: 'auth',
      displayName: 'menu.auth',
      meta: {
        icon: 'login',
      },
      children: [
        {
          name: 'login',
          displayName: 'menu.login',
          meta: {
            icon: 'android',
          },
        },
        {
          name: 'signup',
          displayName: 'menu.signup',
          meta: {
            icon: 'android',
          },
        },
      ],
    },
    {
      name: 'prescription',
      displayName: 'menu.prescription',
      meta: {
        icon: 'android',
      },
    },
  ] as INavigationRoute[],
}
