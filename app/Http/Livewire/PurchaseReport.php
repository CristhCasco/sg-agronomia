<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Purchase;

class PurchaseReport extends Component
{
    public $data =[];
    public   $reportType, $userId, $dateFrom, $dateTo, $saleId;
    

    public function render()
    {
        $this->PurchasesByDate();

        return view('livewire.purchase-report',[
            'users' => User::orderBy('name','asc')->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }


    public function PurchasesByDate()
    {
        if($this->reportType == 0) // ventas del dia
        {
            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d')   . ' 23:59:59';

        } else {
             $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';
             $to = Carbon::parse($this->dateTo)->format('Y-m-d')     . ' 23:59:59';
        }

        if($this->reportType == 1 && ($this->dateFrom == '' || $this->dateTo =='')) { 
            $this->data =[];
            return;
        }

        if($this->userId == 0) 
        {
            $this->data = Purchase::join('users as u','u.id','purchases.user_id')
            ->select('purchases.*','u.name as usuario')
            ->whereBetween('purchases.created_at', [$from, $to])
            ->get();
        } else {
            $this->data = Sale::join('users as u','u.id','purchases.user_id')
            ->select('purchases.id','purchases.total','purchases.items','purchases.status','u.name as usuario','purchases.created_at')
            ->whereBetween('purchases.created_at', [$from, $to])
            ->where('user_id', $this->userId)
            ->get();
        }

    }
}
