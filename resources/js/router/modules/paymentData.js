import Layout from '@/layout';

const paymentData = {
  path: '/payment-data',
  component: Layout,
  // redirect: '/payment-data/index',
  name: 'PaymentData',
  meta: {
    title: 'paymentData',
    icon: 'nested',
  },
  children: [
    {
      path: 'payment-overview',
      component: () => import('@/views/payment-data/PaymentOverview'),
      name: 'PaymentOverview',
      meta: { title: 'paymentOverview' },
    },
    {
      path: '/payment-channel-quota',
      component: () => import('@/views/payment-data/PaymentChannelQuota'),
      name: 'PaymentChannelQuota',
      meta: { title: 'paymentChannelQuota' },
    },
    {
      path: 'distribution-of-people-in-payment-channels',
      component: () => import('@/views/payment-data/DistributionOfPeopleInPaymentChannels'),
      name: 'DistributionPeoplePaymentChannels',
      meta: { title: 'distributionOfPeopleInPaymentChannels' },
    },
    {
      path: 'payment-amount-distribution',
      component: () => import('@/views/payment-data/PaymentAmountDistribution'),
      name: 'PaymentAmountDistribution',
      meta: { title: 'paymentAmountDistribution' },
    },
    {
      path: 'continuous-payment-behavior',
      component: () => import('@/views/payment-data/ContinuousPaymentBehavior'),
      name: 'ContinuousPaymentBehavior',
      meta: { title: 'continuousPaymentBehavior' },
    },
    {
      path: 'first-charge-continuous-pay',
      component: () => import('@/views/payment-data/FirstChargeContinuousPay'),
      name: 'FirstChargeContinuousPay',
      meta: { title: 'firstChargeContinuousPay' },
    },
    {
      path: 'payment-ranking',
      component: () => import('@/views/payment-data/PaymentRanking'),
      name: 'PaymentRanking',
      meta: { title: 'paymentRanking' },
    },
    {
      path: 'withdrawal-ranking',
      component: () => import('@/views/payment-data/WithdrawalRanking'),
      name: 'WithdrawalRanking',
      meta: { title: 'withdrawalRanking' },
    },
    {
      path: 'lost-losers',
      component: () => import('@/views/payment-data/LostLosers'),
      name: 'LostLosers',
      meta: { title: 'lostLosers' },
    },
    {
      path: 'profit-ranking',
      component: () => import('@/views/payment-data/ProfitRanking'),
      name: 'ProfitRanking',
      meta: { title: 'profitRanking' },
    },
  ],
};

export default paymentData;
