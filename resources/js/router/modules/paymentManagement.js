import Layout from '@/layout';
import viewsPermissions from '@/viewsPermissions.js';
import { getPermissionRouteView } from '@/utils/view-permission';
const fn = getPermissionRouteView(viewsPermissions);

const generateMenu = (path, component, name, meta) => ({
  path: path,
  component: component,
  name: name,
  meta: meta,
});

const paymentManagement = {
  path: '/payment-management',
  component: Layout,
  // redirect: '/payment-management/index',
  name: 'PaymentManagement',
  meta: {
    title: 'paymentManagement',
    icon: 'nested',
    permissions: [fn.permissionRouteView('paymentManagement')],
  },
  children: [
    {
      path: 'recharge-management',
      component: () => import('@/views/nested-menu'),
      name: 'RechargeManagement',
      meta: {
        title: 'rechargeManagement',
        permissions: [fn.permissionRouteView('rechargeManagement')],
      },
      children: [
        generateMenu('vip-recharge',
          () => import('@/views/payment-management/VIPRecharge'),
          'VIPRecharge',
          {
            title: 'vIPRecharge',
            permissions: [fn.permissionRouteView('vIPRecharge')],
          }),
        generateMenu('vip-recharge-query',
          () => import('@/views/payment-management/VIPRechargeQuery'),
          'VIPRechargeQuery',
          {
            title: 'vIPRechargeQuery',
            permissions: [fn.permissionRouteView('vIPRechargeQuery')],
          }),
      ],
    },
    {
      path: 'withdrawal-management',
      component: () => import('@/views/nested-menu'),
      name: 'WithdrawalManagement',
      meta: {
        title: 'withdrawalManagement',
        permissions: [fn.permissionRouteView('withdrawalManagement')],
      },
      children: [
        generateMenu('vip-withdrawal',
          () => import('@/views/payment-management/VipWithdraw'),
          'VipWithdrawal',
          {
            title: 'vIPWithdrawal',
            permissions: [fn.permissionRouteView('vIPWithdrawal')],
          }),
        generateMenu('vip-withdrawal-query',
          () => import('@/views/payment-management/WithdrawList'),
          'WithdrawalList',
          {
            title: 'vIPWithdrawalQuery',
            permissions: [fn.permissionRouteView('vIPWithdrawalQuery')],
          }),
      ],
    },
    // {
    //   path: 'recharge-management',
    //   component: () => import('@/views/payment-management/RechargeManagement'),
    //   name: 'RechargeManagement',
    //   meta: {
    //     title: 'rechargeManagement',
    //     permissions: ['view menu recharge management'],
    //   },
    //   children: [
    //     {
    //       path: 'recharge-blacklist',
    //       component: () => import('@/views/payment-management/RechargeBlacklist'),
    //       name: 'RechargeBlacklist',
    //       meta: {
    //         title: 'rechargeBlacklist',
    //         permissions: ['view menu recharge blacklist'],
    //       },
    //     },
    //     {
    //       path: 'gm-plus-and-minus-mon',
    //       component: () => import('@/views/payment-management/GmPlusAndMinusMon'),
    //       name: 'GmPlusAndMinusMon',
    //       meta: {
    //         title: 'gMPlusAndMinusMon',
    //         permissions: ['view menu gm plus and minus mon'],
    //       },
    //     },
    //     {
    //       path: 'payment-client-configuration',
    //       component: () => import('@/views/payment-management/PaymentClientConfiguration'),
    //       name: 'PaymentClientConfiguration',
    //       meta: {
    //         title: 'paymentClientConfiguration',
    //         permissions: ['view menu payment client configuration'],
    //       },
    //     },
    //     {
    //       path: 'bank-card-configuration',
    //       component: () => import('@/views/payment-management/BankCardConfiguration'),
    //       name: 'BankCardConfiguration',
    //       meta: {
    //         title: 'bankCardConfiguration',
    //         permissions: ['view menu bank card configuration'],
    //       },
    //     },
    //     {
    //       path: 'gm-add-and-subtract-money-record',
    //       component: () => import('@/views/payment-management/GmAddAndSubtractMoneyRecord'),
    //       name: 'GmAddAndSubtractMoneyRecord',
    //       meta: {
    //         title: 'gMAddAndSubtractMoneyRecord',
    //         permissions: ['view menu gm add and subtract money record'],
    //       },
    //     },
    //     {
    //       path: 'online-recharge-query',
    //       component: () => import('@/views/payment-management/OnlineRechargeQuery'),
    //       name: 'OnlineRechargeQuery',
    //       meta: {
    //         title: 'onlineRechargeQuery',
    //         permissions: ['view menu online recharge query'],
    //       },
    //     },
    //   ],
    // },
    // на перекладе
    // {
    //   path: 'member-information-modification',
    //   component: () => import('@/views/payment-management/'),
    //   name: '',
    //   meta: {
    //     title: '',
    //     permissions: ['view menu member information modification'],
    //   },
    //   children: [
    //     {
    //       path: 'recharge-blacklist',
    //       component: () => import('@/views/payment-management/RechargeBlacklist'),
    //       name: 'RechargeBlacklist',
    //       meta: {
    //         title: 'rechargeBlacklist',
    //         permissions: ['view menu recharge blacklist'],
    //       },
    //     },
    //     {
    //       path: 'gm-plus-and-minus-mon',
    //       component: () => import('@/views/payment-management/GmPlusAndMinusMon'),
    //       name: 'GmPlusAndMinusMon',
    //       meta: {
    //         title: 'gMPlusAndMinusMon',
    //         permissions: ['view menu gm plus and minus mon'],
    //       },
    //     },
    //     {
    //       path: 'payment-client-configuration',
    //       component: () => import('@/views/payment-management/PaymentClientConfiguration'),
    //       name: 'PaymentClientConfiguration',
    //       meta: {
    //         title: 'paymentClientConfiguration',
    //         permissions: ['view menu payment client configuration'],
    //       },
    //     },
    //   ],
    // },
    // {
    //   path: 'replenishment-order-management',
    //   component: () => import('@/views/payment-management/ReplenishmentOrderManagement'),
    //   name: 'ReplenishmentOrderManagement',
    //   meta: {
    //     title: 'replenishmentOrderManagement',
    //     permissions: ['view menu replenishment order management'],
    //   },
    //   children: [
    //     {
    //       path: 'customer-service-replenishment',
    //       component: () => import('@/views/payment-management/CustomerServiceReplenishment'),
    //       name: 'CustomerServiceReplenishment',
    //       meta: {
    //         title: 'customerServiceReplenishment',
    //         permissions: ['view menu customer service replenishment'],
    //       },
    //     },
    //     {
    //       path: 'order-replenishment-record',
    //       component: () => import('@/views/payment-management/'),
    //       name: 'OrderReplenishmentRecord',
    //       meta: {
    //         title: 'orderReplenishmentRecord',
    //         permissions: ['view menu order replenishment record'],
    //       },
    //     },
    //   ],
    // },
    // {
    //   path: 'level-management',
    //   component: () => import('@/views/payment-management/LevelManagement'),
    //   name: 'LevelManagement',
    //   meta: {
    //     title: 'levelManagement',
    //     permissions: ['view menu level management'],
    //   },
    //   children: [
    //     {
    //       path: 'user-level-configuration',
    //       component: () => import('@/views/payment-management/UserLevelConfiguration'),
    //       name: 'UserLevelConfiguration',
    //       meta: {
    //         title: 'userLevelConfiguration',
    //         permissions: ['view menu user level configuration'],
    //       },
    //     },
    //     {
    //       path: 'layered-player-inform',
    //       component: () => import('@/views/payment-management/LayeredPlayerInform'),
    //       name: 'LayeredPlayerInform',
    //       meta: {
    //         title: 'layeredPlayerInform',
    //         permissions: ['view menu layered player inform'],
    //       },
    //     },
    //   ],
    // },
    // {
    //   path: 'digital-currency-management',
    //   component: () => import('@/views/payment-management/index'),
    //   name: 'DigitalCurrencyManagement',
    //   meta: {
    //     title: 'digitalCurrencyManagement',
    //     permissions: ['view menu digital currency management'],
    //   },
    //   children: [
    //     {
    //       path: 'currency-payment-configuration',
    //       component: () => import('@/views/payment-management/CurrencyPaymentConfiguration'),
    //       name: 'CurrencyPaymentConfiguration',
    //       meta: {
    //         title: 'currencyPaymentConfiguration',
    //         permissions: ['view menu currency payment configuration'],
    //       },
    //     },
    //     {
    //       path: 'currency-withdrawal-configuration',
    //       component: () => import('@/views/payment-management/CurrencyWithdrawalConfiguration'),
    //       name: 'CurrencyWithdrawalConfiguration',
    //       meta: {
    //         title: 'currencyWithdrawalConfiguration',
    //         permissions: ['view menu currency withdrawal configuration'],
    //       },
    //     },
    //   ],
    // },
    //  {
    //   path: 'vip-recharge-management',
    //   component: () => import('@/views/payment-management/'),
    //   name: 'VIPRechargeManagement',
    //   meta: {
    //     title: 'vIPRechargeManagement',
    //     permissions: ['view menu vip recharge management'],
    //   },
    //   children: [
    //     {
    //       path: 'vip-recharge-query',
    //       component: () => import('@/views/payment-management/VIPRechargeQuery'),
    //       name: 'VIPRechargeQuery',
    //       meta: {
    //         title: 'vIPRechargeQuery',
    //         permissions: ['view menu vip recharge query'],
    //       },
    //     },
    //   ],
    // },
  ],
};

export default paymentManagement;
