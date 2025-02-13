import Layout from '@/layout';

const gameData = {
  path: '/game-data',
  component: Layout,
  // redirect: '/game-data/index',
  name: 'GameData',
  meta: {
    title: 'gameData',
    icon: 'nested',
  },
  children: [
    {
      path: 'game-statistics',
      component: () => import('@/views/game-data/index'),
      name: 'GameStatistics',
      meta: { title: 'gameStatistics' },
      children: [
        {
          path: '/player-turnover-query',
          component: () => import('@/views/game-data/index'),
          name: 'PlayerTurnoverQuery',
          meta: { title: 'playerTurnoverQuery' },
        },
        {
          path: '/platform-profit-and-loss-ranking',
          component: () => import('@/views/game-data/index'),
          name: 'PlatformProfitAndLossRanking',
          meta: { title: 'platformProfitAndLossRanking' },
        },
        {
          path: 'member-gold-coin-ranking',
          component: () => import('@/views/game-data/index'),
          name: 'MemberGoldCoinRanking',
          meta: { title: 'memberGoldCoinRanking' },
        },
      ],
    },
    {
      path: 'Activity data',
      component: () => import('@/views/game-data/ActivityData'),
      name: 'ActivityData',
      meta: { title: 'activityData' },
      children: [
        {
          path: 'sign-in-statistics',
          component: () => import('@/views/game-data/SignInStatistics'),
          name: 'SignInStatistics',
          meta: { title: 'signInStatistics' },
        },
        {
          path: 'daily-gift',
          component: () => import('@/views/game-data/DailyGift'),
          name: 'DailyGift',
          meta: { title: 'dailyGift' },
        },
        {
          path: 'effective-bet-amount-ranking-reward',
          component: () => import('@/views/game-data/EffectiveBetAmountRankingReward'),
          name: 'EffectiveBetAmountRankingReward',
          meta: { title: 'effectiveBetAmountRankingReward' },
        },
        {
          path: 'Sharing gift activities',
          component: () => import('@/views/game-data/SharingGiftActivities'),
          name: 'SharingGiftActivities',
          meta: { title: 'sharingGiftActivities' },
        },
        {
          path: 'lucky-wheel-statistics',
          component: () => import('@/views/game-data/LuckyWheelStatistics'),
          name: 'LuckyWheelStatistics',
          meta: { title: 'luckyWheelStatistics' },
        },
        {
          path: 'member-center-statistics',
          component: () => import('@/views/game-data/MemberCenterStatistics'),
          name: 'MemberCenterStatistics',
          meta: { title: 'memberCenterStatistics' },
        },
        {
          path: 'special-channel-activities',
          component: () => import('@/views/game-data/SpecialChannelActivities'),
          name: 'SpecialChannelActivities',
          meta: { title: 'specialChannelActivities' },
        },
      ],
    },
    {
      path: 'betting-record',
      component: () => import('@/views/game-data/BettingRecord'),
      name: 'BettingRecord',
      meta: { title: 'bettingRecord' },
      children: [
        {
          path: 'sports-betting-records',
          component: () => import('@/views/game-data/SportsBettingRecords'),
          name: 'SportsBettingRecords',
          meta: { title: 'sportsBettingRecords' },
        },
        {
          path: 'Esports betting records',
          component: () => import('@/views/game-data/EsportsBettingRecords'),
          name: 'EsportsBettingRecords',
          meta: { title: 'esportsBettingRecords' },
        },
      ],
    },
  ],
};

export default gameData;
