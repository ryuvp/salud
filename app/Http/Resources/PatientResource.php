<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\District;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'document' => $this->document,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'status_format' => $this->status_format,
            'sex' => $this->sex,
            'sex_format' => $this->sex_format,
            'age' => $this->age,
            'birthdate' => $this->birthdate,
            'ubigeo' => $this->ubigeo,
            'address' => $this->address,
            'clinic_history' => $this->clinic_history,
            'district' => $this->getDistrictByUbigeo($this->ubigeo),
            'province' => $this->getProvinceByUbigeo($this->ubigeo),
            'department' => $this->getDepartmentByUbigeo($this->ubigeo),
            'ipress' => $this->ipress ? [
                'id' => $this->ipress->id,
                'code' => $this->ipress->code,
                'name' => $this->ipress->name,
                'clasification' => $this->ipress->clasification,
                'type' => $this->ipress->type,
                'disa_code' => $this->ipress->disa_code,
                'disa_name' => $this->ipress->disa_name,
                'red_code' => $this->ipress->red_code,
                'red_name' => $this->ipress->red_name,
                'microred_code' => $this->ipress->microred_code,
                'microred_name' => $this->ipress->microred_name,
            ]: null,
        ];
    }

    private function getDistrictByUbigeo($ubigeo)
    {
        if (!$ubigeo) {
            return null;
        }

        $district = District::where('ubigeo_inei', $ubigeo)->first(['id', 'name']);
        return $district ? ['id' => $district->id, 'name' => $district->name] : null;
    }

    private function getProvinceByUbigeo($ubigeo)
    {
        if (!$ubigeo) {
            return null;
        }

        $district = District::where('ubigeo_inei', $ubigeo)->first();
        if (!$district || !$district->province) {
            return null;
        }

        $province = $district->province()->select('id', 'name')->first();
        return $province ? ['id' => $province->id, 'name' => $province->name] : null;
    }

    private function getDepartmentByUbigeo($ubigeo)
    {
        if (!$ubigeo) {
            return null;
        }

        $district = District::where('ubigeo_inei', $ubigeo)->first();
        if (!$district || !$district->province || !$district->province->department) {
            return null;
        }

        $department = $district->province->department()->select('id', 'name')->first();
        return $department ? ['id' => $department->id, 'name' => $department->name] : null;
    }
}
