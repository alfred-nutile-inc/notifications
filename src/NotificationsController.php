<?php

namespace AlfredNutileInc\Notifications;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends Controller
{
    protected $input;

    /**
     * @var NotificationService
     */
    private $service;

    public function __construct(NotificationInterface $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $input = Input::only(['read', 'limit']);
        try {
            $results['notifications'] = $this->service->initialize($input)->getAll($input);

            return Response::json(['results' => $results], 200);
        } catch (\Exception $e) {
            return Response::json(['message' => $e->getMessage(), 'results' => []], 422);
        }
    }

    public function getNotification($notice_id)
    {
        try {
            $results['notification'] = $this->service->getOne($notice_id);

            return Response::json($this->responseServices->respond($results, 'Loaded Notice'), 200);
        } catch (\Exception $e) {
            return Response::json($this->responseServices->respond($e->getMessage(), sprintf('Error Getting Notification %s', $e->getMessage())), 422);
        }
    }

    public function putNotificationRead($notice_ids)
    {
        try {
            $this->input = explode(',', $notice_ids);
        } catch (\Exception $e) {
            return Response::json($this->responseServices->respond($e->getMessage(), 'POST Failed'), 422);
        }

        $validator = Validator::make($this->input, $this->service->rulesUpdate);

        if (!$validator->passes()) {
            return Response::json($this->responseServices->respond($validator->messages(), 'Validation failed'), 422);
        }

        try {
            $results = $this->service->updateNoticesRead($this->input);
            $count = count($this->input);

            return Response::json($this->responseServices->respond([], "Marked $count notices read"), 200);
        } catch (\Exception $e) {
            return Response::json($this->responseServices->respond($e->getMessage(), sprintf('Error Updating Notices %s', $e->getMessage())), 422);
        }
    }

    public function putNotification($notice_id)
    {
        try {
            $this->input = $this->getInput();
        } catch (\Exception $e) {
            return Response::json($this->responseServices->respond($e->getMessage(), 'POST Failed'), 422);
        }

        $validator = Validator::make($this->input, $this->service->rulesUpdate);

        if (!$validator->passes()) {
            return Response::json($this->responseServices->respond($validator->messages(), 'Validation failed'), 422);
        }

        try {
            $results = $this->service->updateNotice($notice_id, $this->input);

            return Response::json($this->responseServices->respond($results, 'Updated Notice'), 200);
        } catch (\Exception $e) {
            return Response::json($this->responseServices->respond($e->getMessage(), sprintf('Error Updating Notice %s', $e->getMessage())), 422);
        }
    }
}
