<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Approval;
use Illuminate\Http\Request;
use App\Services\ApprovalService;

class ApprovalController extends Controller
{
    protected $approvalService;

    public function __construct(ApprovalService $approvalService)
    {
        $this->approvalService = $approvalService;
    }

    public function getByApprover(Request $request)
    {
        $status = $request->input('status');
        $keyword = $request->input('keyword');
    
        $data = $this->approvalService->getByApprover($status, $keyword);
        $items = $data->items();
        $responseData = collect($items)->map(function ($item) {
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
                'date_process' => date('M d, Y', strtotime($item->date_process)),
                'created_date' => date('M d, Y', strtotime($item->created_at)),
            ];
        });
    
        return response()->json([
            'responseCode' => 200,
            'responseMessage' => 'Successfully get data',
            'responseData' => $responseData,
        ], 200);
    }

    public function getByRequester(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id_start' => 'required|integer',
            ]);

            $user_id_start = $validated['user_id_start'];
            $status = $request->input('status');
            $keyword = $request->input('keyword');

            $data = $this->approvalService->getByRequester($user_id_start, $status, $keyword);

            // Proses data paginasi
            $items = $data->items();
            $responseData = collect($items)->map(function ($item) {
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
                    'date_process' => date('M d, Y', strtotime($item->date_process)),
                    'created_date' => date('M d, Y', strtotime($item->created_at)),
                ];
            });

            // Return JSON response
            return response()->json([
                'responseCode' => 200,
                'responseMessage' => 'Successfully get data',
                'responseData' => $responseData,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'responseCode' => 500,
                'responseMessage' => 'Failed get data',
                'responseData' => [$th->getMessage()]
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'data_id' => 'required|integer',
                'user_id_start' => 'required|integer',
                'user_id_approver' => 'required|integer',
                'module' => 'required|string',
                'sub_modul' => 'required|string',
                'action' => 'required|string',
                'information' => 'required|string',
                'data' => 'required|array',
            ]);

            // Use the service to create approval
            $approval = $this->approvalService->createApproval($validated);
                
            return response()->json([
                'responseCode' => 200,
                'responseMessage' => 'Successfully create data',
                'responseData' => [$approval]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'responseCode' => 500,
                'responseMessage' => 'Failed create data',
                'responseData' => [$th->getMessage()]
            ], 500);
        }
    }
    
    public function process(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer',
                'status' => 'required|integer',
                'information' => 'required|string',
            ]);

            $approval = $this->approvalService->processApproval($validated);

            return response()->json([
                'responseCode' => 200,
                'responseMessage' => 'Successfully update data',
                'responseData' => [$approval]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'responseCode' => 500,
                'responseMessage' => 'Failed update data',
                'responseData' => [$th->getMessage()]
            ], 500);
        }
    }

    public function detail(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer',
            ]);

            $approval = $this->approvalService->getApprovalDetail($validated['id']);

            return response()->json([
                'responseCode' => 200,
                'responseMessage' => 'Successfully get data',
                'responseData' => [[
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
                ]]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'responseCode' => 500,
                'responseMessage' => 'Failed get data',
                'responseData' => [$th->getMessage()]
            ], 500);
        }
    }
}
