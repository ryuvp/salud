/* /resources/js/app/api/user.js */
import request from '@/app/utils/request';
import Resource from '@/app/api/resource';

class UserResource extends Resource {
  constructor() {
    super('users');
  }

  permissions(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'get',
    });
  }

  updatePermission(id, permissions) {
    return request({
      url: '/' + this.uri + '/' + id + '/permissions',
      method: 'put',
      data: permissions,
    });
  }

  logs(id, params) {
    return request({
      url: '/' + this.uri + '/' + id + '/logs',
      method: 'get',
      params: params
    });
  }

  storePatient(resource) {
    return request({
      url: '/patients',
      method: 'post',
      data: resource
    })
  }

  patients() {
    return request({
      url: '/patients',
      method: 'get',
    })
  }

  follow() {
    return request({
      url: '/patients/follow',
      method: 'get',
    })
  }
}

export { UserResource as default };
