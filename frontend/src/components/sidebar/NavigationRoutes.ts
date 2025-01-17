
// NavigationRoutes.ts
export interface INavigationRoute {
  name: string;
  displayName: string;
  meta?: {
    icon?: string;
  };
  children?: INavigationRoute[];
  grandChildren?: INavigationRoute[];
}

// Export your routes as a constant
const navigationRoutes: INavigationRoute[] = [
  {
    name: 'dashboard',
    displayName: 'menu.dashboard',
    meta: { icon: 'DashboardOutlined' },
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
        grandChildren: [
          { name: 'temporary-user', displayName: 'menu.sub-menu.grand-submenu-one', meta: { icon: 'UserOutlined' } },
          { name: 'permanent-user', displayName: 'menu.sub-menu.grand-submenu-two', meta: { icon: 'PlayCircleOutlined' } },
        ],
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
        grandChildren: [
          { name: 'temporary-permission', displayName: 'menu.sub-menu.grand-submenu-three', meta: { icon: 'PlusSquareOutlined' } },
          { name: 'permanent-permission', displayName: 'menu.sub-menu.grand-submenu-four', meta: { icon: 'PlusCircleOutlined' } },
        ],
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
    meta: { icon: 'SettingOutlined' },
  },
];

export default navigationRoutes;

