<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PlanFeaturesCollection;
use App\Http\Resources\FeatureCollection;

class Plan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'discount' => $this->discount,
            'description' => $this->description,
            'price' => $this->price,
            'interval' => $this->interval,
            'trial_period_days' => $this->trial_period_days,
            'sort_order' => $this->sort_order,
            'on_show' => $this->on_show,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'features' => $this->features,
        ];
    }
}
