export default {
  route: {
    'Home page': 'Домашняя страница',
    'System Management': 'Управление системой',
    // start children route
    'User Management': 'Управление пользователями',
    'Role management': 'Управление ролями',
    'Authority management': 'Управление полномочиями',
    'Operation log': 'Журнал операцый',
    // end children route
    'Game setting': 'Настройки игры',
    // --> start children route
    'Announcement configuration': '公告配置',
    'Test gateway configuration': '测试网关配置',
    'Push activity': '推送活动',
    'Newbie mail': '新手邮件',
    'Mass mailing list': '群发邮件列表 ',
    'Rebate management': '返利管理',
    // end children route
    'Withdraw management': '提现管理',
    // start children route
    'Automatic withdrawal information': '自动出款信息',
    'Coin payments withdrawal': 'CoinPayments 提现',
    'Coin payments application list': 'CoinPayments 申请列表',
    'Coin payments audit list': 'CoinPayments 审核列 ',
    'Bank withd raw': 'Bank 提现 ',
    /* ВНЕСТИ ПРАВКИ */
    'Bank 申请列表': 'Bank 申请列表',
    'Bank 审核列表': 'Bank 审核列表',
    'Withdraw the black list': '提现拉黑列表',
    'Alarm configuration': '预警配置',
    'Withdraw configuration': '提现配置',
    'Recharge blacklist': '充值黑名单',
    'GM plus and minus mon': 'GM加减钱',
    'Payment client configuration': '支付客户端配置',
    'GM add and subtract money record': 'GM加减钱记录',
    'Online recharge query': '线上充值查询',
    'Replenishment order management': '补单管理',
    'Customer service replenishment': '客服补单',
    'Order replenishment record': '补单记录',
    'Level management': '层级管理',
    'User level configuration': '用户层级配置',
    'Layered player inform': '分层玩家信息',
    'Digital currency management': '数字货币管理',
    'Currency payment configuration': '货币支付配置',
    'Currency withdrawal configuration': '货币提现配置',
    // translate vIP充值管理:
    'VIP recharge management': 'VIP 充值管理 ',
    'VIP recharge': 'VIP 充值 ',
    'VIP recharge query': 'VIP 充值查询',
    /* end children route */
    RealTimeData: '实时数据',
    'Real time registration': '实时注册',
    'New paid number curve': '新增付费人数曲线',
    'Betting record': '投注记录',

    /* end children route */
    'Promotion data': '推广数据',
  },
  navbar: {
    logOut: '退出登录',
    changePassword: '修改密码',
  },
  login: {
    title: '系统登录',
    logIn: '登录',
    username: '账号',
    password: '密码',
    any: '随便填',
    thirdparty: '第三方登录',
    'Thirdparty tips': 'Thirdparty tips',
  },
  btn: {
    delete: 'Удалить',
    add: 'Добавить',
    edit: 'Редактировать',
    roles: 'Роли',
    authority: 'Дерево привилегий',
    verifyIdentidy: 'Подтвердить личность',
    confirm: 'Подтвердить',
    cancel: 'Отмена',
    ok: 'Да',
    // operation log
    query: 'Запрос',
    reset: 'Перезагрузить',
  },
  // translate for table
  table: {
    id: 'ID',
    userName: 'Имя пользователя',
    nickName: 'Ник',
    role: 'Роль',
    createTime: 'Время создания',
    updateTime: 'Время обновления',
    action: 'Действия',
  },
  dialog: {
    user: {
      addTitle: 'Add new user',
      editTitle: 'Edit user',
      userName: 'Имя пользователя',
      nickName: 'Ник',
      ip: 'IP',
      password: 'Пароль',
      confirmPassword: 'Подтвердить пароль',
      nameRule: 'Имя обязательно',
      nickRule: 'Ник обязательно',
    },
  },
};
