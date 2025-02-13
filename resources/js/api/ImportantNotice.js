import Resource from '@/api/resource';
import request from '@/utils/request';

export default class GameCategory extends Resource {
  constructor() {
    super('important-notice');
  }
  destroy(ids) {
    return request({
      url: '/' + this.uri,
      method: 'delete',
      params: { 'ids': ids },
    });
  }
}
