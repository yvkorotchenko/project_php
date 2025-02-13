import defaultSettings from '@/settings';
import i18n from '@/lang';

const title = defaultSettings.title || 'BI Admin';

export default function getPageTitle(key) {
  if (typeof key === 'undefined') {
    const hasKey = i18n.te(`route.${key}`);
    if (hasKey) {
      const pageName = i18n.t(`route.${key}`);
      return `${pageName} - ${title}`;
    }
    return `${title}`;
  } else {
    return '';
  }
}
