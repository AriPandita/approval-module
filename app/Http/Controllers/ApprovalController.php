<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use \Exception;
use App\Helpers\ResponseHelper;
use App\Services\ApprovalService;
use App\Http\Requests\ApproverRequest;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\DetailRequest;
use App\Http\Requests\ProcessRequest;
use App\Http\Requests\RequesterRequest;

class ApprovalController extends Controller
{
    protected $approvalService;
    protected $responseHelper;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    public function Approver(ApproverRequest $request)
    {
        try {
            $validatedData = $request->validated();
    
            $status = $validatedData['status'] ?? null;
            $keyword = $validatedData['keyword'] ?? null;
            $limit = $request->input('limit'); // Tambahkan penanganan untuk input limit jika diperlukan
    
            if ($limit) {
                $data = $this->approvalService->getByApprover($status, $keyword, $limit);
            } else {
                $data = $this->approvalService->getByApprover($status, $keyword);
            }

            $responseData = $data->map(function($item) {
            return [
                'id' => $item->id,
                'data_id' => $item->data_id,
                'user_id_start' => $item->user_id_start,
                'user_id_approver' => $item->user_id_approver,
                'module' => $item->module,
                'sub_modul' => $item->sub_modul,
                'action' => $item->action,
                'data' => $item->data,
                'status' => $item->status,
                'information' => $item->information,
                'date_process' => \Carbon\Carbon::parse($item->date_process)->format('M d, Y'),
                'created_date' => \Carbon\Carbon::parse($item->created_at)->format('M d, Y'),
                ];
            });
        
            return ResponseHelper::successResponse('Successfully get data', $responseData, 200);

        } catch (Exception $th) {
            return ResponseHelper::errorResponse('Failed get data', [$th->getMessage()], 400);
        }
    }


    public function Requester(RequesterRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $status = $validatedData['status'] ?? null;
            $keyword = $validatedData['keyword'] ?? null;
            $user_id_start = $validatedData['user_id_start']?? null;
            $limit = $request->input('limit'); // Tambahkan penanganan untuk input limit jika diperlukan
            
            if ($limit) {
                $data = $this->approvalService->getByRequester($user_id_start, $status, $keyword, $limit);
            } else {
                $data = $this->approvalService->getByRequester($user_id_start, $status, $keyword);
            }

            $responseData = $data->map(function($item) {
                return [
                    'id' => $item->id,
                    'data_id' => $item->data_id,
                    'user_id_start' => $item->user_id_start,
                    'user_id_approver' => $item->user_id_approver,
                    'module' => $item->module,
                    'sub_modul' => $item->sub_modul,
                    'action' => $item->action,
                    'data' => $item->data,
                    'status' => $item->status,
                    'information' => $item->information,
                    'date_process' => \Carbon\Carbon::parse($item->date_process)->format('M d, Y'),
                    'created_date' => \Carbon\Carbon::parse($item->created_at)->format('M d, Y'),
                    ];
                });

            return ResponseHelper::successResponse('Successfully get data', $responseData, 200);

        } catch (\Exception $th) {
            return ResponseHelper::errorResponse('Failed get data', [$th->getMessage()], 400);
        }
    }


    public function create(CreateRequest $request)
    {
        try {
            $validated = $request->validated();
            $approval = $this->approvalService->createApproval($validated);
            $responseData = [
                    'data_id' => $approval->data_id,
                    'user_id_start' => $approval->user_id_start,
                    'user_id_approver' => $approval->user_id_approver,
                    'module' => $approval->module,
                    'sub_modul' => $approval->sub_modul,
                    'action' => $approval->action,
                    'data' => $approval->data,
                    'status' => $approval->status,
                    'information' => $approval->information,
                    ];
            return ResponseHelper::successResponse('Successfully create data', $responseData);
            
        } catch (\Throwable $th) {
            return ResponseHelper::errorResponse('Failed create data', [$th->getMessage()]);
        }
    }
        
    public function process(ProcessRequest $request)
    {
        try {
            $validated = $request->validated();
    
            $approval = $this->approvalService->processApproval($validated);
            $responseData = [
                'id' => $approval->id,
                'status' => $approval->status,
                'information' => $approval->information,
            ];

    
            return ResponseHelper::successResponse('Successfully update data', $responseData);
    
        } catch (\Throwable $th) {
            return ResponseHelper::errorResponse('Failed update data', [$th->getMessage()]);
        }
    }
    
    public function detail(DetailRequest $request)
    {
        try {
            $validated = $request->validated();
    
            $approval = $this->approvalService->getApprovalDetail($validated['id']);
    
            $responseData = [
                'id' => $approval->id,
                'data_id' => $approval->data_id,
                'user_id_start' => $approval->user_id_start,
                'user_id_approver' => $approval->user_id_approver,
                'module' => $approval->module,
                'sub_modul' => $approval->sub_modul,
                'action' => $approval->action,
                'data' => $approval->data,
                'status' => $approval->status,
                'information' => $approval->information,
                'date_process' => Carbon::parse($approval->date_process)->format('M d, Y'),
                'created_date' => Carbon::parse($approval->created_at)->format('M d, Y'),
            ];
    
            return ResponseHelper::successResponse('Successfully get data', [$responseData]);
    
        } catch (\Throwable $th) {
            return ResponseHelper::errorResponse('Failed get data', [$th->getMessage()]);
        }
    }
}
