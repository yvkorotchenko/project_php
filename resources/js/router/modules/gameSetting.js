import Layout from '@/layout';
import viewsPermissions from '@/viewsPermissions.js';
import { getPermissionRouteView } from '@/utils/view-permission';
const fn = getPermissionRouteView(viewsPermissions);

const gameSetting = {
  path: '/game-setting',
  component: Layout,
  redirect: '/game-setting/account-management',
  name: 'GameSettings',
  meta: {
    title: 'gameSettings',
    icon: 'nested',
    permissions: [fn.permissionRouteView('gameSettings')],
  },
  children: [
    {
      path: 'account-management',
      component: () => import('@/views/game-settings/index'), // Parent router-view
      name: 'AccountManagement',
      meta: {
        title: 'accountManagement',
        permissions: [fn.permissionRouteView('accountManagement')],
      },
      children: [
        {
          path: 'member-information-modification',
          component: () => import('@/views/game-settings/MemberInformationModification'),
          name: 'MemberInformationModification',
          meta: {
            title: 'memberInformationModification',
            permissions: [fn.permissionRouteView('memberInformationModification')],
          },
        },
        {
          path: 'device-number-change',
          component: () => import('@/views/game-settings/DeviceNumberChange'),
          name: 'DeviceNumberChange',
          meta: {
            title: 'deviceNumberChange',
            permissions: [fn.permissionRouteView('deviceNumberChange')],
          },
        },
        {
          path: 'verification-code-query',
          component: () => import('@/views/game-settings/VerificationCodeQuery'),
          name: 'VerificationCodeQuery',
          meta: {
            title: 'verificationCodeQuery',
            permissions: [fn.permissionRouteView('verificationCodeQuery')],
          },
        },
        // {
        //   path: 'subordinate-binding-log',
        //   component: () => import('@/views/game-settings/SubordinateBindingLog'),
        //   name: 'SubordinateBindingLog',
        //   meta: {
        //   title: 'subordinateBindingLog',
        //   permissions: ['view menu subordinate binding log'],
        //   },
        // },
        {
          path: 'player-information',
          component: () => import('@/views/game-settings/PlayerInformation'),
          name: 'PlayerInformation',
          meta: {
            title: 'playerInformation',
            permissions: [fn.permissionRouteView('playerInformation')],
          },
        },
        {
          path: 'account-suspension',
          component: () => import('@/views/game-settings/AccountSuspension'),
          name: 'AccountSuspension',
          meta: {
            title: 'accountSuspension',
            permissions: [fn.permissionRouteView('accountSuspension')],
          },
        },
        {
          path: 'ip-query',
          component: () => import('@/views/game-settings/IpQuery'),
          name: 'IpQuery',
          meta: {
            title: 'ipQuery',
            permissions: [fn.permissionRouteView('ipQuery')],
          },
        },
        {
          path: 'all-players-query',
          component: () => import('@/views/game-settings/AllPlayersQuery'),
          name: 'AllPlayersQuery',
          meta: {
            title: 'allPlayersQuery',
            permissions: [fn.permissionRouteView('allPlayersQuery')],
          },
        },
        {
          path: 'user-login-log',
          component: () => import('@/views/game-settings/UserLoginLog'),
          name: 'UserLoginLog',
          meta: {
            title: 'userLoginLog',
            permissions: [fn.permissionRouteView('userLoginLog')],
          },
        },
        {
          path: 'sensitive-operation-log',
          component: () => import('@/views/game-settings/SensitiveOperationLog'),
          name: 'SensitiveOperationLog',
          meta: {
            title: 'sensitiveOperationLog',
            permissions: [fn.permissionRouteView('sensitiveOperationLog')],
          },
        },
        // {
        //   path: 'sportbook-management',
        //   component: () => import('@/views/game-settings/SportBookManagement'),
        //   name: 'SportBookManagement',
        //   meta: {
        //     title: 'sportbookManagement',
        //     permissions: ['view menu sportbook management'],
        //   },
        // },
      ],
    },
    {
      path: 'marquee-management',
      component: () => import('@/views/game-settings/index'), // Parent router-view
      name: 'MarqueeManagement',
      meta: {
        title: 'marqueeManagement',
        permissions: [fn.permissionRouteView('marqueeManagement')],
      },
      children: [
        {
          path: 'system-timing',
          component: () => import('@/views/game-settings/SystemTiming'),
          name: 'SystemTiming',
          meta: {
            title: 'systemTiming',
            permissions: [fn.permissionRouteView('systemTiming')],
          },
        },
        {
          path: 'winning-withdrawal',
          component: () => import('@/views/game-settings/WinningWithdrawal'),
          name: 'WinningWithdrawal',
          meta: {
            title: 'winningWithdrawal',
            permissions: [fn.permissionRouteView('winningWithdrawal')],
          },
        },
      ],
    },
    // {
    //   path: 'customer-service',
    //   component: () => import('@/views/game-settings/index'), // Parent router-view
    //   name: 'customerService',
    //   meta: {
    //     title: 'customerService',
    //     permissions: ['view menu customer service'],
    //   },
    //   children: [
    //     {
    //       path: 'feedback',
    //       component: () => import('@/views/game-settings/Feedback'),
    //       name: 'Feedback',
    //       meta: {
    //         title: 'feedback',
    //         permissions: ['view menu feedback'],
    //       },
    //       children: [
    //         {
    //           path: 'feedback',
    //           component: () => import('@/views/game-settings/Feedback'),
    //           name: 'Feedback',
    //           meta: {
    //             title: 'feedback',
    //             permissions: ['view menu feedback'],
    //           },
    //         },
    //       ],
    //     },
    //   ],
    // },
    {
      path: 'ip-management',
      component: () => import('@/views/game-settings/index'), // Parent router-view
      name: 'IPManagement',
      meta: {
        title: 'iPManagement',
        permissions: [fn.permissionRouteView('iPManagement')],
      },
      children: [
        {
          path: 'client-ip-management',
          component: () => import('@/views/game-settings/ClientIPManagement'),
          name: 'ClientIPManagement',
          meta: {
            title: 'clientIPManagement',
            permissions: [fn.permissionRouteView('clientIPManagement')],
          },
        },
        {
          path: 'ip-whitelist',
          component: () => import('@/views/game-settings/IPWhitelist'),
          name: 'IPWhitelist',
          meta: {
            title: 'iPWhitelist',
            permissions: [fn.permissionRouteView('iPWhitelist')],
          },
        },
        {
          path: 'forbidden-ip-login',
          component: () => import('@/views/game-settings/ForbiddenIPLogin'),
          name: 'ForbiddenIPLogin',
          meta: {
            title: 'forbiddenIPLogin',
            permissions: [fn.permissionRouteView('forbiddenIPLogin')],
          },
        },
        {
          path: 'test-ip-white-list',
          component: () => import('@/views/game-settings/TestIPWhiteList'),
          name: 'TestIPWhiteList',
          meta: {
            title: 'testIPWhiteList',
            permissions: [fn.permissionRouteView('testIPWhiteList')],
          },
        },
      ],
    },
    {
      path: 'service-suspension-management',
      component: () => import('@/views/game-settings/index'), // Parent router-view
      name: 'ServiceSuspensionManagement',
      meta: {
        title: 'serviceSuspensionManagement',
        permissions: [fn.permissionRouteView('serviceSuspensionManagement')],
      },
      children: [
        // {
        //   path: 'stop-service-operation',
        //   component: () => import('@/views/game-settings/StopServiceOperation'),
        //   name: 'StopServiceOperation',
        //   meta: {
        //     title: 'stopServiceOperation',
        //     permissions: ['view menu stop service operation'],
        //   },
        // },
        {
          path: 'stop-service-announcement',
          component: () => import('@/views/game-settings/StopServiceAnnouncement'),
          name: 'StopServiceAnnouncement',
          meta: {
            title: 'stopServiceAnnouncement',
            permissions: [fn.permissionRouteView('stopServiceAnnouncement')],
          },
          children: [
            {
              path: 'stop-service-announcement',
              component: () => import('@/views/game-settings/StopServiceAnnouncement'),
              name: 'StopServiceAnnouncement',
              meta: {
                title: 'stopServiceAnnouncement',
                permissions: [fn.permissionRouteView('stopServiceAnnouncement')],
              },
            },
          ],
        },
      ],
    },
    {
      path: 'game-management',
      component: () => import('@/views/game-settings/index'), // Parent router-view
      name: 'GameManagement',
      meta: {
        title: 'gameManagement',
        permissions: [fn.permissionRouteView('gameManagement')],
      },
      children: [
        // {
        //   path: 'bet-amount-setting',
        //   component: () => import('@/views/game-settings/BetAmountSetting'),
        //   name: 'BetAmountSetting',
        //   meta: {
        //     title: 'betAmountSetting',
        //     permissions: ['view menu bet amount setting'],
        //   },
        // },
        {
          path: 'share-address',
          component: () => import('@/views/game-settings/ShareAddress'),
          name: 'ShareAddress',
          meta: {
            title: 'shareAddress',
            permissions: [fn.permissionRouteView('shareAddress')],
          },
        },
        // {
        //   path: 'sign-in-configuration',
        //   component: () => import('@/views/game-settings/SignInConfiguration'),
        //   name: 'SignInConfiguration',
        //   meta: {
        //     title: 'signInConfiguration',
        //     permissions: ['view menu sign in configuration'],
        //   },
        // },
        {
          path: 'activity-configuration',
          component: () => import('@/views/game-settings/index'), // Parent router-view
          name: 'ActivityConfiguration',
          meta: {
            title: 'activityConfiguration',
            permissions: [fn.permissionRouteView('activityConfiguration')],
          },
          children: [
            {
              path: 'rewards-management',
              component: () => import('@/views/game-settings/RewardsManagement'),
              name: 'RewardsManagement',
              meta: {
                title: 'rewardsManagement',
                permissions: [fn.permissionRouteView('rewardsManagement')],
              },
            },
            {
              path: 'welfare',
              component: () => import('@/views/game-settings/SevenDaysWelfare'),
              name: 'Welfare',
              meta: {
                title: 'sevenDaysWelfare',
                permissions: [fn.permissionRouteView('sevenDaysWelfare')],
              },
            },
            {
              path: '/task-management',
              component: () => import('@/views/game-settings/index'),
              name: 'TaskManagement',
              meta: {
                title: 'taskManagement',
                permissions: [fn.permissionRouteView('taskManagement')],
              },
              children: [
                {
                  path: 'task-list',
                  component: () => import('@/views/game-settings/TaskList'),
                  name: 'TaskList',
                  meta: {
                    title: 'taskList',
                    permissions: [fn.propertyIsEnumerable('taskList')],
                  },
                },
                {
                  path: 'task-record',
                  component: () => import('@/views/game-settings/TaskRecord'),
                  name: 'taskRecord',
                  meta: {
                    title: 'taskRecord',
                    permissions: [fn.permissionRouteView('taskRecord')],
                  },
                },
                {
                  path: 'task-details',
                  component: () => import('@/views/game-settings/TaskDetails'),
                  name: 'TaskDetails',
                  meta: {
                    title: 'taskDetails',
                    permissions: [fn.permissionRouteView('taskDetails')],
                  },
                },
              ],
            },
          ],
        },
        // {
        //   path: 'announcement-configuration',
        //   component: () => import('@/views/game-settings/AnnouncementConfiguration'),
        //   name: 'AnnouncementConfiguration',
        //   meta: {
        //     title: 'announcementConfiguration',
        //     permissions: ['view menu announcement configuration'],
        //   },
        // },
        {
          path: 'important-notice',
          component: () => import('@/views/game-settings/ImportantNotice'),
          name: 'ImportantNotice',
          meta: {
            title: 'importantNotice',
            permissions: [fn.permissionRouteView('importantNotice')],
          },
        },
        {
          path: 'banner-management',
          component: () => import('@/views/game-settings/BannerManagement'),
          name: 'BannerManagement',
          meta: {
            title: 'bannerManagement',
            permissions: [fn.permissionRouteView('bannerManagement')],
          },
        },
        {
          path: 'hot-management',
          component: () => import('@/views/game-settings/HotManagement'),
          name: 'HotManagement',
          meta: {
            title: 'hotManagement',
            permissions: [fn.permissionRouteView('hotManagement')],
          },
        },
        // {
        //   path: 'game-configuration',
        //   component: () => import('@/views/game-settings/GameConfiguration'),
        //   name: 'GameConfiguration',
        //   meta: {
        //     title: 'gameConfiguration',
        //     permissions: ['view menu game configuration'],
        //   },
        // },
        // {
        //   path: 'customer-service-setting',
        //   component: () => import('@/views/game-settings/CustomerServiceSetting'),
        //   name: 'CustomerServiceSetting',
        //   meta: {
        //     title: 'customerServiceSetting',
        //     permissions: ['view menu customer service setting'],
        //   },
        // },
        // {
        //   path: 'gateway-configuration',
        //   component: () => import('@/views/game-settings/GatewayConfiguration'),
        //   name: 'GatewayConfiguration',
        //   meta: {
        //     title: 'gatewayConfiguration',
        //     permissions: ['view menu gateway configuration'],
        //   },
        // },
        // {
        //   path: 'picture-configuration',
        //   component: () => import('@/views/game-settings/PictureConfiguration'),
        //   name: 'PictureConfiguration',
        //   meta: {
        //     title: 'pictureConfiguration',
        //     permissions: ['view menu picture configuration'],
        //   },
        // },
        // {
        //   path: 'test-gateway-configuration',
        //   component: () => import('@/views/game-settings/TestGatewayConfiguration'),
        //   name: 'TestGatewayConfiguration',
        //   meta: {
        //     title: 'testGatewayConfiguration',
        //     permissions: ['view menu test gateway configuration'],
        //   },
        // },
        // {
        //   path: 'push-activity',
        //   component: () => import('@/views/game-settings/PushActivity'),
        //   name: 'PushActivity',
        //   meta: {
        //     title: 'pushActivity',
        //     permissions: ['view menu push activity'],
        //   },
        // },
        // {
        //   path: 'sms-platform-switch',
        //   component: () => import('@/views/game-settings/SMSPlatformSwitch'),
        //   name: 'SMSPlatformSwitch',
        //   meta: {
        //     title: 'sMSPlatformSwitch',
        //     permissions: ['view menu sms platform switch'],
        //   },
        // },
      ],
    },
    {
      path: 'mail-management',
      component: () => import('@/views/game-settings/index'), // Parent router-view
      name: 'MailManagement',
      meta: {
        title: 'mailManagement',
        permissions: [fn.permissionRouteView('mailManagement')],
      },
      children: [
        // {
        //   path: 'newbie-mail',
        //   component: () => import('@/views/game-settings/NewbieMail'),
        //   name: 'NewbieMail',
        //   meta: {
        //     title: 'newbieMail',
        //     permissions: ['view menu newbie mail'],
        //   },
        // },
        {
          path: 'mailing-list',
          component: () => import('@/views/game-settings/MailingList'),
          name: 'MailingList',
          meta: {
            title: 'mailingList',
            permissions: [fn.permissionRouteView('mailingList')],
          },
        },
        // {
        //   path: 'mass-mailing-list',
        //   component: () => import('@/views/game-settings/MassMailingList'),
        //   name: 'MassMailingList',
        //   meta: {
        //     title: 'massMailingList',
        //     permissions: ['view menu mass mailing list'],
        //   },
        // },
        {
          path: 'bulk-mail-generation-form',
          component: () => import('@/views/game-settings/BulkMailGenerationForm'),
          name: 'BulkMailGenerationForm',
          meta: {
            title: 'bulkMailGenerationForm',
            permissions: [fn.permissionRouteView('bulkMailGenerationForm')],
          },
        },
      ],
    },
    //
    // {
    //   path: 'function-management',
    //   component: () => import('@/views/game-settings/index'), // Parent router-view
    //   name: 'FunctionManagement',
    //   meta: {
    //     title: 'functionManagement',
    //     permissions: ['view menu function management'],
    //   },
    //   // children: [
    //   //   {
    //   //     path: 'newbie-mail',
    //   //     component: () => import('@/views/game-settings/NewbieMail'),
    //   //     name: 'NewbieMail',
    //   //     meta: {
    //            title: 'newbieMail',
    //            permissions: ['view menu newbie mail'],
    //          },
    //   //   },
    //   // ],
    // },
    // {
    //   path: 'rebate-management',
    //   component: () => import('@/views/game-settings/index'), // Parent router-view
    //   name: 'RebateManagement',
    //   meta: {
    //     title: 'rebateManagement',
    //     permissions: ['view menu rebate management'],
    //   },
    //   children: [
    //     {
    //       path: 'rebate-configuration',
    //       component: () => import('@/views/game-settings/RebateConfiguration'),
    //       name: 'RebateConfiguration',
    //       meta: {
    //         title: 'rebateConfiguration',
    //         permissions: ['view menu rebate management'],
    //       },
    //       children: [],
    //     },
    //   ],
    // },
    {
      path: 'third-party-management',
      component: () => import('@/views/game-settings/index'),
      name: 'ThirdPartyManagement',
      meta: {
        title: 'thirdPartyManagement',
        permissions: [fn.permissionRouteView('thirdPartyManagement')],
      },
      children: [
        {
          path: 'platform-settings',
          component: () => import('@/views/game-settings/PlatformSettings'),
          name: 'PlatformSettings',
          meta: {
            title: 'platformSettings',
            permissions: [fn.permissionRouteView('platformSettings')],
          },
        },
        {
          path: 'category-settings',
          component: () => import('@/views/game-settings/CategorySettings'),
          name: 'CategorySettings',
          meta: {
            title: 'categorySettings',
            permissions: [fn.permissionRouteView('categorySettings')],
          },
        },
        {
          path: 'game-list',
          component: () => import('@/views/game-settings/GameList'),
          name: 'GameList',
          meta: {
            title: 'gameList',
            permissions: [fn.permissionRouteView('gameList')],
          },
        },
      ],
    },
  ],
};

export default gameSetting;
