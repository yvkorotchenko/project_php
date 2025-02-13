import Layout from '@/layout';

const activeData = {
  path: '/active-data',
  component: Layout,
  name: 'ActiveData',
  meta: {
    title: 'activeData',
    icon: 'nested',
  },
  children: [
    {
      path: 'daily-active',
      component: () => import('@/views/active-data/index'),
      name: 'DailyActive',
      meta: { title: 'dailyActive' },
    },
    {
      path: '/registered-user-retention',
      component: () => import('@/views/active-data/index'),
      name: 'RegisteredUserRetention',
      meta: { title: 'registeredUserRetention' },
    },
    {
      path: 'active-user-retention',
      component: () => import('@/views/active-data/ActiveUserRetention'),
      name: 'ActiveUserRetention',
      meta: { title: 'activeUserRetention' },
    },
    {
      path: 'pay-user-retention',
      component: () => import('@/views/active-data/PayUserRetention'),
      name: 'PayUserRetention',
      meta: { title: 'payUserRetention' },
    },
    {
      path: 'add-paid-retention',
      component: () => import('@/views/active-data/AddPaidRetention'),
      name: 'AddPaidRetention',
      meta: { title: 'addPaidRetention' },
    },
  ],
};

export default activeData;
