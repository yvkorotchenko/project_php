import Layout from '@/layout';

export default {
  meta: {
    permissions: ['view menu element ui'],
    title: 'Element UI',
    lang: 'elementUI',
    icon: 'el-icon-s-platform',
  },
  path: '/element-ui',
  component: Layout,
  redirect: '/element-ui/avatars',
  name: 'ElementUI',
  children: [
    {
      path: 'avatars',
      component: () => import('@/views/element-ui/Avatars'),
      name: 'Avatars',
      meta: {
        roles: ['debian'],
        permissions: ['view menu element ui avatars'],
        title: 'Avatars',
        lang: 'elementUIAvatars',
        icon: 'el-icon-picture-outline-round',
      },
    },
  ],
};
