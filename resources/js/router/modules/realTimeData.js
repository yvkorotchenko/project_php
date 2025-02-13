import Layout from '@/layout';
import viewsPermissions from '@/viewsPermissions.js';
import { getPermissionRouteView } from '@/utils/view-permission';
const fn = getPermissionRouteView(viewsPermissions);

const realTimeData = {
  path: '/real-time-data',
  component: Layout,
  redirect: '/real-time-data/index',
  name: 'RealTimeData',
  lang: 'RealTimeData',
  meta: {
    title: 'RealTimeData',
    lang: 'RealTimeData',
    icon: 'nested',
    permissions: [fn.permissionRouteView('RealTimeData')],
  },
  children: [
    // {
    //   path: 'playing-online',
    //   component: () => import('@/views/real-time-data/PlayingOnline'),
    //   name: 'PlayingOnline',
    //   meta: {
    //     title: 'playingOnline',
    //     permissions: ['view menu playing online'],
    //   },
    // },
    // {
    //   path: '/real-time-registration',
    //   component: () => import('@/views/real-time-data/RealTimeRegistration'),
    //   name: 'RealTimeRegistration',
    //   meta: {
    //     title: 'realTimeRegistration',
    //     permissions: ['view menu real time registration'],
    //   },
    // },
    // {
    //   path: 'payment-number-curve',
    //   component: () => import('@/views/real-time-data/PaymentNumberCurve'),
    //   name: 'PaymentNumberCurve',
    //   meta: {
    //     title: 'paymentNumberCurve',
    //     permissions: ['view menu payment number curve'],
    //   },
    // },
    // {
    //   path: 'new-paid-number-curve',
    //   component: () => import('@/views/real-time-data/NewPaidNumberCurve'),
    //   name: 'NewPaidNumberCurve',
    //   meta: {
    //     title: 'newPaidNumberCurve',
    //     permissions: ['view menu payment number curve'],
    //   },
    // },
    // {
    //   path: 'new-payment-limit-curve',
    //   component: () => import('@/views/real-time-data/NewPaymentLimitCurve'),
    //   name: 'NewPaymentLimitCurve',
    //   meta: {
    //     title: 'newPaymentLimitCurve',
    //     permissions: ['view menu new payment limit curve'],
    //   },
    // },
    // {
    //   path: 'real-time-curve-of-total',
    //   component: () => import('@/views/real-time-data/RealTimeCurveOfTotal'),
    //   name: 'RealTimeCurveOfTotal',
    //   meta: {
    //     title: 'realTimeCurveOfTotal',
    //     permissions: ['view menu real time curve of total'],
    //   },
    // },
    // {
    //   path: 'payment-to-remind-reality-curve',
    //   component: () => import('@/views/real-time-data/PaymentToRemindRealityCurve'),
    //   name: 'PaymentToRemindRealityCurve',
    //   meta: {
    //     title: 'paymentToRemindRealityCurve',
    //     permissions: ['view menu payment to remind reality curve'],
    //   },
    // },
    {
      path: 'playing-online',
      component: () => import('@/views/real-time-data/PlayingOnlineStatistics'),
      name: 'Playing Online',
      meta: {
        title: 'playingOnlineStatistics',
        permissions: [fn.permissionRouteView('playingOnlineStatistics')],
      },
    },
    {
      path: 'real-time-rergistration',
      component: () => import('@/views/real-time-data/RegistrationOnlineStatistics'),
      name: 'RealTimeRegistration',
      meta: {
        title: 'registrationStatistics',
        permissions: [fn.permissionRouteView('registrationStatistics')],
      },
    },
  ],
};

export default realTimeData;
