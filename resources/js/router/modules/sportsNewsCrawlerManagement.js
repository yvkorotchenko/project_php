import Layout from '@/layout';

const sportsNewsCrawlerManagement = {
  path: '/sports-news-crawler-management',
  component: Layout,
  redirect: '/sports-news-crawler-management/sports-news',
  name: 'SportsNewsCrawlerManagement',
  meta: {
    title: 'sportsNewsCrawlerManagement',
    icon: 'el-icon-tickets',
  },
  children: [
    {
      path: 'sports-news',
      component: () => import('@/views/sports-news-crawler-management/SportsNews'),
      name: 'SportsNews',
      meta: {
        title: 'sportsNews',
        icon: 'el-icon-notebook-2',
        breadcrumb: false,
        permissions: [],
      },
    },
    {
      path: 'match-schedule-management',
      component: () => import('@/views/sports-news-crawler-management/MatchScheduleManagement'),
      name: 'MatchScheduleManagement',
      meta: {
        title: 'matchScheduleManagement',
        icon: 'el-icon-s-management',
        breadcrumb: false,
        permissions: [],
      },
    },
  ],
};

export default sportsNewsCrawlerManagement;
