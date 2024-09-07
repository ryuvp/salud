import request from '@/app/utils/request';
import Resource from '@/app/api/resource';


class IpressResource extends Resource {

    constructor() {
        super('ipress');
    }
    ipress(ubigeo_inei) {
        return request({
            url: '/get-ipress-by-ubigeo/' + ubigeo_inei,
            method: 'get',
        })
    }
}

export { IpressResource as default }
