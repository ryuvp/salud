import request from '@/app/utils/request';
import Resource from '@/app/api/resource';

class Cie10Resource extends Resource {
    constructor() {
        super('cie10');
    }
}

export { Cie10Resource as default }