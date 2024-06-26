<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Approval;

class ApprovalService
{
    public function createApproval(array $data)
    {
        // Set default status to 0
        $data['status'] = 0;

        $data['date_process'] = Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s');
        $data['created_at'] = Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s');
        $data['updated_at'] = Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s');

        // Create the approval entry
        return Approval::create($data);
    }

    public function processApproval(array $data)
    {
        $approval = Approval::find($data['id']);
        if ($approval) {
            $approval->status = $data['status'];
            $approval->information = $data['information'];
            $approval->date_process = Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s');
            $approval->updated_at = Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s');
            $approval->save();
            return $approval;
        }
        throw new \Exception('Approval not found.');
    }

    public function getApprovalDetail($id)
    {
        $approval = Approval::find($id);
        if ($approval) {
            return $approval;
        }
        throw new \Exception('Approval not found.');
    }
}
?>
