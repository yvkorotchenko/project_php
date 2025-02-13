import Layout from '@/layout';

const gameData = {
  path: '/promotion-data',
  component: Layout,
  // redirect: '/game-data/index',
  name: 'PromotionData',
  meta: {
    title: 'promotionData',
    icon: 'nested',
  },
  children: [
    {
      path: 'new-promotion',
      component: () => import('@/views/promotion-data/index'),
      name: 'NewPromotion',
      meta: {
        title: 'newPromotion',
      },
    },
    {
      path: 'New promotion details',
      component: () => import('@/views/promotion-data/NewPromotionDetails'),
      name: 'NewPromotionDetails',
      meta: { title: 'newPromotionDetails' },
    },
    {
      path: 'promotional-retention',
      component: () => import('@/views/promotion-data/PromotionalRetention'),
      name: 'PromotionalRetention',
      meta: {
        title: 'promotionalRetention', lang: 'promotionalRetention',
      },
    },
    {
      path: 'promotional-payment',
      component: () => import('@/views/promotion-data/PromotionalPayment'),
      name: 'PromotionalPayment',
      meta: { title: 'promotionalPayment' },
    },
    {
      path: 'new-paid-player-details',
      component: () => import('@/views/promotion-data/NewPaidPlayerDetails'),
      name: 'NewPaidPlayerDetails',
      meta: { title: 'newPaidPlayerDetails' },
    },
    {
      path: 'new-user-statistics',
      component: () => import('@/views/promotion-data/NewUserStatistics'),
      name: 'NewUserStatistics',
      meta: { title: 'newUserStatistics' },
    },
    {
      path: 'promotion-paid-statistics',
      component: () => import('@/views/promotion-data/PromotionPaidStatistics'),
      name: 'PromotionPaidStatistics',
      meta: { title: 'promotionPaidStatistics' },
    },
    {
      path: 'short-chain-generation',
      component: () => import('@/views/promotion-data/ShortChainGeneration'),
      name: 'ShortChainGeneration',
      meta: { title: 'shortChainGeneration' },
    },
    {
      path: 'special-channel-configuration',
      component: () => import('@/views/promotion-data/SpecialChannelConfiguration'),
      name: 'SpecialChannelConfiguration',
      meta: { title: 'specialChannelConfiguration' },
    },
  ],
};

export default gameData;
