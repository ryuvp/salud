//import request from '@/app/utils/request';
import Resource from '@/app/api/resource';

class MedicamentResource extends Resource {
    constructor() {
        super('medicament');
    }
}

export { MedicamentResource as default }