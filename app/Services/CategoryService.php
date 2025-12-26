<?php

namespace App\Services;

use App\Traits\FileManagerTrait;
use Illuminate\Support\Str;

class CategoryService
{
    use FileManagerTrait;

    public function getAddData(object $request): array
    {
        return [
            'name' => $request['name'][array_search('en', $request['lang'])],
            'category_code' => $request['category_code'][array_search('en', $request['lang'])] ?? null,

            'slug' => Str::slug($request['name'][array_search('en', $request['lang'])]),
            'icon' => $this->upload('category/', 'webp', $request->file('image')),
            'delivery_class_id' => $request->get('delivery_class_id', 1),
            'number_of_days' => $request->get('number_of_days', 1),
            'parent_id' => $request->get('parent_id', 0),
            // 'sub_category_code' => $request['sub_category_code'][array_search('en', $request['lang'])] ?? null,
            'position' => $request['position'],
            'priority' => $request['priority'],
        ];
    }


    public function deliveryGetAddData(object $request): array
    {
        return [
            'name' => $request['name'][array_search('en', $request['lang'])],
            'code' => $request['code'][array_search('en', $request['lang'])] ?? null,
            'description' => $request['description'][array_search('en', $request['lang'])] ?? null,

        ];
    }




    public function getUpdateData(object $request, object $data): array
    {
        $image = $request->file('image') ? $this->update('category/', $data['image'], 'webp', $request->file('image')) : $data['icon'];
        return [
            'name' => $request['name'][array_search('en', $request['lang'])],
            'category_code' => $request['category_code'][array_search('en', $request['lang'])] ?? null,
            'delivery_class_id' => $request->get('delivery_class_id', 1),
            // 'sub_category_code' => $request['sub_category_code'][array_search('en', $request['lang'])] ?? null,
            'number_of_days' => $request->get('number_of_days', 1),
            'slug' => Str::slug($request['name'][array_search('en', $request['lang'])]),
            'icon' => $image,
            'priority' => $request['priority'],
        ];
    }

    public function getSelectOptionHtml(object $data): string
    {
        $output = '<option value="" disabled selected>' . (translate('select_sub_category')) . '</option>';
        foreach ($data as $row) {
            $output .= '<option value="' . $row->id . '">' . $row->defaultName . '</option>';
        }
        return $output;
    }

    public function deleteImages(object $data): bool
    {
        if ($data->childes) {
            foreach ($data->childes as $child) {
                if ($child->childes) {
                    foreach ($child->childes as $item) {
                        if ($item['icon']) {
                            $this->delete('category/' . $item['icon']);
                        }
                    }
                }
                if ($child['icon']) {
                    $this->delete('category/' . $child['icon']);
                }
            }
        }
        if ($data['icon']) {
            $this->delete('category/' . $data['icon']);
        }
        return true;
    }
}
