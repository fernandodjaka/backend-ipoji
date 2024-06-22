<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\NotificationModel;

class NotificationController extends ResourceController
{
    protected $format = 'json';

    public function getNotifications($userId)
    {
        $notificationModel = new NotificationModel();
        $notifications = $notificationModel->where('user_id', $userId)->findAll();

        return $this->respond(['notifications' => $notifications]);
    }
}
