import Resource from '@/app/api/resource';
import validate from '@/app/utils/validate-dni';
class ReniecResource extends Resource {
    get(dni){
        return validate({
            url: '/dni/' + dni,
            method: 'get',
        })
    }
}

export {ReniecResource as default}