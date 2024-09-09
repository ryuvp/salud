import request from '@/app/utils/request';
import Resource from '@/app/api/resource';

class DiagnosticResource extends Resource {
    constructor() {
        super('diagnostic');
    }

    cie10(){
        return request({
            url: '/get-cie10',
            method: 'get',
        })
    }

    ipress(){
        return request({
            url: '/get-ipress',
            method: 'get',
        })
    }
}

export { DiagnosticResource as default }