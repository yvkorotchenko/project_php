import Layout from '@/layout';

const withdrawManagement = {
  path: '/withdraw-management',
  component: Layout,
  name: 'WithdrawManagement',
  meta: {
    title: 'withdrawManagement',
    icon: 'el-icon-user-solid',
  },
  children: [
    {
      path: 'automatic-withdrawal-information',
      component: () => import('@/views/withdraw-management/index'), // Parent router-view
      name: 'AutomaticWithdrawalInformation',
      meta: {
        title: 'automaticWithdrawalInformation',
        permissions: ['view menu automatic withdrawal information'],
      },
      children: [
        {
          path: 'bank-card-automatic-withdrawal-configuration',
          component: () => import('@/views/withdraw-management/BankCardAutomaticWithdrawalConfiguration'), // Parent router-view
          name: 'BankCardAutomaticWithdrawalConfiguration',
          meta: {
            title: 'bankCardAutomaticWithdrawalConfiguration',
            permissions: ['view menu bank card automatic withdrawal configuration'],
          },
        },
      ],
    },
    {
      path: 'coin-payments-withdrawal',
      component: () => import('@/views/withdraw-management/index'), // Parent router-view
      name: 'CoinPaymentsWithdrawal',
      meta: {
        title: 'coinPaymentsWithdrawal',
        permissions: ['view menu coin payments withdrawal'],
      },
      children: [
        {
          path: 'coin-payments-application-list',
          component: () => import('@/views/withdraw-management/CoinPaymentsApplicationList'), // Parent router-view
          name: 'CoinPaymentsApplicationList',
          meta: {
            title: 'coinPaymentsApplicationList',
            permissions: ['view menu coin payments application list'],
          },
        },
        {
          path: 'coin-payments-audit-list',
          component: () => import('@/views/withdraw-management/CoinPaymentsAuditList'), // Parent router-view
          name: 'CoinPaymentsAuditList',
          meta: {
            title: 'coinPaymentsAuditList',
            permissions: ['view menu coin payments audit list'],
          },
        },
      ],
    },
    {
      path: 'bank-withd-raw',
      component: () => import('@/views/withdraw-management/index'), // Parent router-view
      name: 'BankWithdRaw',
      meta: {
        title: 'bankWithdRaw',
        permissions: ['view menu bank withd raw'],
      },
      children: [
        {
          path: 'bank-card-application-list',
          component: () => import('@/views/withdraw-management/BankCardApplicationList'), // Parent router-view
          name: 'BankCardApplicationList',
          meta: {
            title: 'bankCardApplicationList',
            permissions: ['view menu bank card application list'],
          },
        },
        {
          path: 'bank-card-review-list',
          component: () => import('@/views/withdraw-management/BankCardReviewList'), // Parent router-view
          name: 'BankCardReviewList',
          meta: {
            title: 'bankCardReviewList',
            permissions: ['view menu bank card review list'],
          },
        },
      ],
    },
    {
      path: 'black-list-of-withdrawals',
      component: () => import('@/views/withdraw-management/BlackListOfWithdrawals'), // Parent router-view
      name: 'BlackListOfWithdrawals',
      meta: {
        title: 'blackListOfWithdrawals',
        permissions: ['view menu black list of withdrawals'],
      },
    },
    {
      path: 'alarm-configuration',
      component: () => import('@/views/withdraw-management/AlarmConfiguration'), // Parent router-view
      name: 'AlarmConfiguration',
      meta: {
        title: 'Alarm configuration',
        permissions: ['view menu alarm configuration'],
      },
    },
    {
      path: 'withdraw-configuration',
      component: () => import('@/views/withdraw-management/WithdrawСonfiguration'), // Parent router-view
      name: 'WithdrawConfiguration',
      meta: {
        title: 'withdrawConfiguration',
        permissions: ['view menu withdraw configuration'],
      },
    },
  ],
};

export default withdrawManagement;
