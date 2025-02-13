import request from '@/utils/request';
import Resource from '@/api/resource';

class RoleResource extends Resource {
  constructor() {
    super('roles');
  }

  deliteLists(resource) {
    return request({
      url: '/' + this.uri + '/delete-list',
      method: 'post',
      data: resource,
    });
  }

  permissions(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'get',
    });
  }
  updatePermissions(id, resource, uri = this.uri) {
    return request({
      url: '/' + uri + '/' + id,
      method: 'put',
      data: resource,
    });
  }
}

export { RoleResource as default };
