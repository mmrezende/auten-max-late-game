<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return([
            'id' => $this->id,

            'email' => $this->email,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'phone' => $this->phone,
            'plan_name' => is_null($this->payment_plan) ? 'Gratuito' : $this->payment_plan->name,
            'plan_period' => is_null($this->payment_plan) ? 'free' : $this->payment_plan->period,
            'payment_method' => $this->payment_method,
            'isVerified' => !is_null($this->email_verified_at),
            'isBlocked' => !!$this->is_blocked,
            'block_reason' => $this->block_reason,
            'isInactive' => $this->isInactive(),
            'isRegular' => $this->isRegular(),
            'isPastTestPeriod' => $this->isPastTestPeriod(),
            'is_admin' => $this->is_admin,
            'has_full_access' => $this->has_full_access
        ]);
    }
}
