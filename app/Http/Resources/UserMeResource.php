<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PersonalResource;
use App\Http\Resources\UnidadesResource;

class UserMeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            "id"                => $this->id,
            "cedula"            => $this->cedula,
            "is_admin"          => $this->is_admin,
            "personal_id"       => $this->personal_id,
            "status"            => $this->status,
            "personal"          => new PersonalResource($this->personal),
        ];
    }
}
