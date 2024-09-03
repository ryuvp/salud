<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PrescriptionResource;

class DiagnosticResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cie10' => $this->cie10->name, // Muestra el nombre de CIE10 relacionado
            'created_at' => $this->created_at,
            'ipress' => $this->ipress ? [
                'id' => $this->ipress->id,
                'name' => $this->ipress->name,
            ]: null,
            'prescriptions' => PrescriptionResource::collection($this->prescriptions), // Muestra las prescripciones usando otro recurso
        ];
    }
}
