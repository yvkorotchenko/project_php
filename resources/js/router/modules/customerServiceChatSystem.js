import Layout from '@/layout';
import viewsPermissions from '@/viewsPermissions.js';
import { getPermissionRouteView } from '@/utils/view-permission';
const fn = getPermissionRouteView(viewsPermissions);

const customerServiceChatSystem = {
  path: '/customer-service-chat-system',
  component: Layout,
  redirect: '/customer-service-chat-system/Feedback',
  name: 'customerServiceChatSystem',
  meta: {
    title: 'customerServiceChatSystem',
    icon: 'nested',
    permissions: [fn.permissionRouteView('customerServiceChatSystem')],
  },
  children: [
    {
      path: 'feedback',
      component: () => import('@/views/customer-service-chat-system/'), // Parent router-view
      name: 'feedback',
      meta: {
        title: 'feedback',
        permissions: [fn.permissionRouteView('feedback')],
      },
      children: [
        // {
        //   path: 'customer-service-waiting-list',
        //   component: () => import('@/views/game-settings/customerServiceWaitingList'),
        //   name: 'customerServiceWaitingList',
        //   meta: {
        //     title: 'customerServiceWaitingList',
        //     permissions: ['view menu customer service waiting list'],
        //   },
        // },
        // {
        //   path: 'customer-service-log',
        //   component: () => import('@/views/game-settings/customerServiceLog'),
        //   name: 'customerServiceLog',
        //   meta: {
        //     title: 'customerServiceLog',
        //     permissions: ['view menu customer service log'],
        //   },
        // },
        {
          path: 'feedback',
          component: () => import('@/views/customer-service-chat-system/Feedback'),
          name: 'feedback',
          meta: {
            title: 'feedback',
            permissions: ['view menu feedback'],
          },
        },
      ],
    },
  ],
};

export default customerServiceChatSystem;
