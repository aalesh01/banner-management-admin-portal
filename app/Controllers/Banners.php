<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BannerModel;

class Banners extends ResourceController
{
    use ResponseTrait;

    protected $model;

    public function __construct()
    {
        $this->model = new BannerModel();
    }


    public function index()
    {
        $banners = $this->model->findAll();
        return $this->respond($banners);
    }


    public function create()
    {
        $data = $this->request->getJSON(true);
    
        if (empty($data)) {
            return $this->failValidationError('No data received');
        }
    
        if (isset($data['is_active'])) {
            $data['is_active'] = isset($data['is_active']) && filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
        }
    
        if (!$this->model->insert($data)) {
            return $this->failValidationErrors($this->model->errors());
        }
    
        $response = [
            'status' => 201,
            'message' => 'Banner created successfully',
            'id' => $this->model->getInsertID()
        ];
    
        return $this->respondCreated($response);
    }
    

    public function update($id = null)
    {
        if ($id === null) {
            return $this->failValidationError('ID is required');
        }

        $banner = $this->model->find($id);
        if (!$banner) {
            return $this->failNotFound('Banner not found with ID: ' . $id);
        }

        $data = $this->request->getJSON(true);

        if (empty($data)) {
            return $this->failValidationError('No data received');
        }

        if (isset($data['is_active'])) {
            $data['is_active'] = isset($data['is_active']) && filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';

        }

        if (!$this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $response = [
            'status' => 200,
            'message' => 'Banner updated successfully',
            'id' => $id
        ];

        return $this->respond($response);
    }


    public function delete($id = null)
    {
        if ($id === null) {
            return $this->failValidationError('ID is required');
        }

        $banner = $this->model->find($id);
        if (!$banner) {
            return $this->failNotFound('Banner not found with ID: ' . $id);
        }

        if (!$this->model->delete($id)) {
            return $this->fail('Failed to delete banner');
        }

        $response = [
            'status' => 200,
            'message' => 'Banner deleted successfully',
            'id' => $id
        ];

        return $this->respondDeleted($response);
    }
}