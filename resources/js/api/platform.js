import Resource from '@/api/resource';
import request from '@/utils/request';

export default class PlatformResource extends Resource {
  constructor() {
    super('platforms');
  }

  gameCategories(platformId, query) {
    return request({
      url: `platforms/${platformId}/game-categories`,
      method: 'get',
      params: query,
    });
  }

  updateGameCateogries(platformId, categories) {
    return request({
      url: `platforms/${platformId}/game-categories`,
      method: 'put',
      data: { categories: categories },
    });
  }
}
