<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $date = (new DateTime($this->date))->format('d/m/Y');

        $subscription_begin = (new DateTime($this->subscription_begin_at))->format('H:i');
        $subscription_end = (new DateTime($this->subscription_end_at))->format('H:i');
        $subscription = $subscription_begin . ' às ' . $subscription_end;

        $img_filename = $this->tournament_platform ? Storage::disk('public')->url($this->tournament_platform->img_filename) : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'date' => $date,
            'subscription' => $subscription,
            'platform_name' => $this->tournament_platform?->name,
            'platform_id' => $this->tournament_platform_id,
            'platform_img' => $img_filename,
            'type_name' => $this->tournament_type?->name,
            'type_id' => $this->tournament_type_id,
            'buy_in' => $this->buy_in,
            'prize' => $this->prize,
            'isRecurrent' => !is_null($this->tournament_recurrence_id),
            'isApproved' => $this->is_approved,
            'isNotifiable' => Auth::user()->is_admin ? null : $this->notifications()->whereBelongsTo(Auth::user())->exists(),
        ];
    }
}