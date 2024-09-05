//import request from '@/app/utils/request';
import Resource from '@/app/api/resource';

class PrescritionResource extends Resource {
    constructor() {
        super('prescription');
    }
}

export { PrescritionResource as default }
