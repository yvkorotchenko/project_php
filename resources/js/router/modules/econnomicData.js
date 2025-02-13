import Layout from '@/layout';

const econnomicData = {
  path: '/economic-data',
  component: Layout,
  // redirect: '/real-time-data/index',
  name: 'EconomicData',
  meta: {
    title: 'economicData',
    icon: 'nested',
  },
  children: [
    {
      path: 'daily-revenue',
      component: () => import('@/views/economic-data/DailyRevenue'),
      name: 'DailyRevenue',
      meta: { title: 'dailyRevenue' },
    },
    {
      path: '/daily-gold-coin-flow',
      component: () => import('@/views/economic-data/DailyGoldCoinFlow'),
      name: 'DailyGoldCoinFlow',
      meta: { title: 'dailyGoldCoinFlow' },
    },
  ],
};

export default econnomicData;
