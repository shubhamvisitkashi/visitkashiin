<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        $data = [
            'id'                => $this->id,
            'slug'              => $this->slug,
            'name'              => $this->name,
            'description'       => $this->description,
            'category'          => [
                                    'id'    => $this->category->id,
                                    'slug' => $this->category->slug,
                                    'name' => $this->category->name
                                ],
            'sub_category'      => [
                                    'id'    => $this->subCategory->id,
                                    'slug' => $this->subCategory->slug,
                                    'name' => $this->subCategory->name
                                ],
            'meta_title'        => $this->meta_title,
            'meta_keyword'      => $this->meta_keyword,
            'meta_description'  => $this->meta_description,

            'images'        => [],
        ];

        $images = [];
        foreach($this->images as $image){
            $imageUrl = asset('backend/admin/product_images/'.$image);
            $images[] = $imageUrl;
        }
        $data['images'] = $images;

        
        return $data;
    }
}
