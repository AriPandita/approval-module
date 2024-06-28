<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Approval;

class ApprovalService
{


    public function getByApprover($status, $keyword, $limit=null) // atur limit untuk pagination
    {
        $query = Approval::query();

        if (isset($status)) {
            $query->where('status', $status);
        }

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('module', 'like', "%$keyword%")
                ->orWhere('sub_modul', 'like', "%$keyword%")
                ->orWhere('action', 'like', "%$keyword%");
            });
        }

        if ($limit) {
            return $query->simplePaginate($limit);
        } else {
            return $query->get();
        }
    }

    public function getByRequester($user_id_start, $status, $keyword, $limit=null) // atur limit untuk pagination
    {
        $userExists = Approval::where('user_id_start', $user_id_start)->exists();
        if (!$userExists) {
            throw new \Exception('User ID does not exist.');
        }
        
        $query = Approval::query()->where('user_id_start', $user_id_start);
        
        if (isset($status)) {
            $query->where('status', $status);
        }

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('module', 'like', "%$keyword%")
                ->orWhere('sub_modul', 'like', "%$keyword%")
                ->orWhere('action', 'like', "%$keyword%");
            });
        }

        if ($limit && $limit != null) {
            $data = $query->simplePaginate($limit);
        } else {
            $data = $query->get();
        }

        return $data;
    }

    

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
