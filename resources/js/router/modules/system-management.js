import Layout from '@/layout';
import viewsPermissions from '@/viewsPermissions.js';
import { getPermissionRouteView } from '@/utils/view-permission';

const fn = getPermissionRouteView(viewsPermissions);

const systemManagement = {
  path: '/system-management',
  component: Layout,
  redirect: '/system-management/users',
  name: 'SystemManagement',
  meta: {
    title: 'systemManagement',
    icon: 'peoples',
    // breadcrumb: false,
    // permissions: [viewsPermissions.systemManagement.permissions.route.view],
    permissions: [fn.permissionRouteView('systemManagement')],
  },
  children: [
    // show list user lists
    {
      path: 'users',
      component: () => import('@/views/system-management/Users'),
      name: 'UserList',
      meta: {
        title: 'userManagement',
        icon: 'user',
        breadcrumb: true,
        permissions: [fn.permissionRouteView('userManagement')],
      },
    },
    {
      path: 'roles',
      component: () => import('@/views/system-management/Roles'),
      name: 'RoleList',
      meta: {
        title: 'roleManagement',
        icon: 'role',
        breadcrumb: true,
        permissions: [fn.permissionRouteView('roleManagement')],
      },
    },
    // set default value for :pareten > 0
    // {
    //   path: 'authority/0',
    //   component: () => import('@/views/system-management/Authority'),
    //   children: [
    //     {
    //       path: '/system-management/authority/0',
    //       meta: {
    //         title: 'authorityManagement',
    //         icon: 'list',
    //         permissions: ['view menu system management authority'],
    //       },
    //     },
    //   ],
    // },
    {
      path: 'authority/:parent',
      component: () => import('@/views/system-management/Authority'),
      name: 'Authority',
      hidden: true,
      meta: {
        title: 'authorityManagement',
        icon: 'list',
        breadcrumb: true,
        // permissions: ['view menu system management authority'],
      },
    },
    {
      path: 'operation-log',
      name: 'OperationLog',
      component: () => import('@/views/system-management/OperationLog'),
      // meta: { title: 'Operation Log', icon: 'skill', permission: ['administrator permission'], breadcrumb: false },
      meta: {
        title: 'operationLog',
        icon: 'el-icon-tickets',
        breadcrumb: true,
        permissions: [fn.permissionRouteView('operationLog')],
      },
    },
    // {
    //   path: 'customerserviceoperator',
    //   name: 'CustomerServiceOperator',
    //   component: () => import('@/views/system-management/Customerserviceoperator'),
    //   // meta: { title: 'Operation Log', lang: 'operationLog', icon: 'skill', permission: ['administrator permission'], breadcrumb: false },
    //   meta: {
    //     title: 'customerServiceOperator',
    //     icon: 'el-icon-tickets',
    //     breadcrumb: true,
    //     permissions: ['view menu system management operation log'],
    //   },
    // },
  ],
};

export default systemManagement;
