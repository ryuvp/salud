import request from '@/app/utils/request';

class IpressResource {

    constructor(uri) {
        this.uri = uri;
    }
    ipress(ubigeo_inei) {
        return request({
            url: '/get-ipress-by-ubigeo/' + ubigeo_inei,
            method: 'get',
        })
    }
    get_ipress(id) {
        return request({
            url: '/ipress/' + id,
            method: 'get',
        })
    }
}

export { IpressResource as default }
