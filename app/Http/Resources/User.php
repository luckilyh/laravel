<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * 将资源转换成数组
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'account' => $this->account,
            'phone' => $this->phone,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'account' => $this->account,
            'introduction' => $this->introduction,
        ];
    }
}
