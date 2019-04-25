<?php
namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;

class ManagementController extends APIController
{
    public function __construct()
    {
    }
    
    public function index(Request $request)
    {
        $this->setStatusCode(200);
        return $this->respond([]);
    }    
}