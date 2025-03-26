<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'title',
        'image_url',
        'link_url',
        'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'image_url' => 'required|valid_url',
        'link_url' => 'required|valid_url',
        'is_active' => 'permit_empty|in_list[0,1,true,false]',
    ];
    
    protected $validationMessages = [
        'title' => [
            'required' => 'Title is required',
            'min_length' => 'Title must be at least 3 characters long',
            'max_length' => 'Title cannot exceed 255 characters',
        ],
        'image_url' => [
            'required' => 'Image URL is required',
            'valid_url' => 'Please provide a valid URL for the image',
        ],
        'link_url' => [
            'required' => 'Link URL is required',
            'valid_url' => 'Please provide a valid URL for the link',
        ],
        'is_active' => [
            'in_list' => 'Is active must be either true or false',
        ],
    ];

    protected $skipValidation = false;
}