import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);
import Layout from '@/layout';
import viewsPermissions from '@/viewsPermissions.js';
import { getPermissionRouteView } from '@/utils/view-permission';
const fn = getPermissionRouteView(viewsPermissions);

import gameSetting from '@/router/modules/gameSetting';
import customerServiceChatSystem from '@/router/modules/customerServiceChatSystem';
import systemManagement from '@/router/modules/system-management';
// import sportsNewsCrawlerManagement from '@/router/modules/sportsNewsCrawlerManagement';
// import gameSetting from '@/router/modules/gameSetting';
// import withdrawManagement from '@/router/modules/withdrawManagement';
import paymentManagement from '@/router/modules/paymentManagement';
// import realTimeData from '@/router/modules/realTimeData';
// import activeData from '@/router/modules/activeData';
// import paymentData from '@/router/modules/paymentData';
// import econnomicData from '@/router/modules/econnomicData';
// import gameData from '@/router/modules/gameData';
// import promotionData from '@/router/modules/promotionData';
import realTimeData from './modules/realTimeData';
// import UIElements from './modules/elementUi';

export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path*',
        component: () => import('@/views/redirect/index'),
      },
    ],
  },
  {
    name: 'loginPage',
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true,
  },
  {
    path: '/auth-redirect',
    component: () => import('@/views/login/AuthRedirect'),
    hidden: true,
  },
  {
    path: '/404',
    redirect: { name: 'Page404' },
    component: () => import('@/views/error-page/404'),
    hidden: true,
  },
  {
    path: '/401',
    component: () => import('@/views/error-page/401'),
    hidden: true,
  },
  // withdrawManagement,
  // realTimeData,
  // activeData,
  // paymentData,
  // econnomicData,
  // gameData,
  // promotionData,
];

export const asyncRoutes = [
  {
    // home redirect all path
    path: '',
    component: Layout,
    redirect: 'dashboard',
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/index'),
        name: 'HomePage',
        meta: {
          title: 'homePage',
          icon: 'dashboard',
          noCache: true,
        },
      },
    ],
  },
  {
    path: '/home-page',
    component: Layout,
    redirect: '/dashboard',
  },
  systemManagement,
  gameSetting,
  paymentManagement,
  realTimeData,
  {
    path: '/action-log',
    component: Layout,
    redirect: '/action-log/log',
    name: 'ActionLog',
    meta: {
      title: 'actionLog',
      icon: 'el-icon-office-building',
      permissions: [fn.permissionRouteView('actionLog')],
    },
    children: [{
      path: 'log',
      component: () => import('@/views/action/ActionLog.vue'),
      name: 'ActionLogList',
      meta: {
        title: 'actionLogList',
        permissions: [fn.permissionRouteView('actionLogList')],
      },
    }],
  },
  customerServiceChatSystem,
  {
    component: Layout,
    path: '/qa',
    name: 'Qa',
    meta: {
      title: 'gameTesting',
      icon: 'el-icon-s-ticket',
      permissions: [fn.permissionRouteView('gameTesting')],
    },
    children: [
      {
        path: '/qa/notification',
        component: () => import('@/views/qa/QaNotification.vue'),
        meta: {
          title: 'gameTestingNotification',
          icon: 'el-icon-s-ticket',
          permissions: [fn.permissionRouteView('gameTestingNotification')],
        },
        name: 'qaNotification',
      },
    ],
  },
];

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  base: process.env.MIX_MT_SPORTS_PATH,
  routes: constantRoutes,
});

const router = createRouter();

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher; // reset router
}

export default router;
