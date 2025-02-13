import request from '@/utils/request';
import Resource from '@/api/resource';

export default class taskManagement extends Resource {
  constructor() {
    super('task-management');
  }
  list(query) {
    return request({
      url: '/' + this.uri,
      method: 'get',
      params: query,
    });
  }
}
