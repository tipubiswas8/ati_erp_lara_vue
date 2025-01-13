export interface INavigationRoute {
  name: string
  displayName: string
  meta: { icon: string } // Use string to store icon names
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
      meta: { icon: 'DashboardOutlined' }, // Icon name as string
    },
    {
      name: 'security-access',
      displayName: 'menu.security-access',
      meta: { icon: 'SafetyOutlined' },
      children: [
        {
          name: 'users',
          displayName: 'menu.users',
          meta: { icon: 'TeamOutlined' },
        },
        {
          name: 'role',
          displayName: 'menu.role',
          meta: { icon: 'SolutionOutlined' },
        },
        {
          name: 'permission',
          displayName: 'menu.permission',
          meta: { icon: 'KeyOutlined' },
        },
      ],
    },
    {
      name: 'hr',
      displayName: 'menu.hr',
      meta: { icon: 'UsergroupAddOutlined' },
      children: [
        {
          name: 'employees',
          displayName: 'menu.employees',
          meta: { icon: 'IdcardOutlined' },
        },
        {
          name: 'organizations',
          displayName: 'menu.organizations',
          meta: { icon: 'ClusterOutlined' },
        },
      ],
    },
    {
      name: 'auth',
      displayName: 'menu.auth',
      meta: { icon: 'LockOutlined' },
      children: [
        {
          name: 'login',
          displayName: 'menu.login',
          meta: { icon: 'LoginOutlined' },
        },
        {
          name: 'signup',
          displayName: 'menu.signup',
          meta: { icon: 'UserAddOutlined' },
        },
      ],
    },
    {
      name: 'settings',
      displayName: 'menu.settings',
      meta: {
        icon: 'SettingOutlined',
      },
    },
  ] as INavigationRoute[],
}
