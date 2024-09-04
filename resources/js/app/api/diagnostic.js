//import request from '@/app/utils/request';
import Resource from '@/app/api/resource';

class DiagnosticResource extends Resource {
    constructor() {
        super('diagnostic');
    }
}

export { DiagnosticResource as default }