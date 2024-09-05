//import request from '@/app/utils/request';
import Resource from '@/app/api/resource';

class PrescriptionResource extends Resource {
    constructor() {
        super('prescription');
    }
}

export { PrescriptionResource as default }
