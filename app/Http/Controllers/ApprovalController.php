<?php

namespace App\Http\Controllers;

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
}
