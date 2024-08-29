import request from '@/app/utils/request';

class UbigeoResource {

    constructor(uri) {
        this.uri = uri;
    }
    departments() {
        return request({
            url: '/get-departments',
            method: 'get',
        })
    }

    provinces(departamento_id) {
        return request({
            url: '/get-provinces/' + departamento_id,
            method: 'get',
        })
    }

    districts(provincia_id) {
        return request({
            url: '/get-districts/' + provincia_id,
            method: 'get',
        })
    }

}

export { UbigeoResource as default }
