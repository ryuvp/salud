<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
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
            'medicament' => $this->medicament->name, // Muestra el nombre del medicamento relacionado
            'quantity' => $this->quantity,
            'frequency' => $this->frequency,
            'created_at' => $this->created_at,
        ];
    }
}
