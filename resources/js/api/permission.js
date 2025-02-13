import request from '@/utils/request';
import Resource from '@/api/resource';

class PermissionsResource extends Resource {
  constructor() {
    super('permissions');
  }

  deliteLists(resource) {
    return request({
      url: '/' + this.uri + '/delete-list',
      method: 'post',
      data: resource,
    });
  }

  haveSubPermission(id) {
    return request({
      url: `/${this.uri}/${id}/children`,
      method: 'get',
    });
  }

  all() {
    return request({
      url: `/${this.uri}/all`,
      method: 'get',
    });
  }
}

export { PermissionsResource as default };
