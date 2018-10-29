<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CPLog;
use App\Invoice;
use App\Subscribe;
use App\Plan;

use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $invoice = Invoice::findOrFail((int)$request->input('InvoiceId'));
//
//        $code = 11;
//
//        if ($invoice->amount == (int)$request->input('Amount') && !$invoice->paid)
//        {
//            $invoice->payment->pay();
//
//            CPLog::create([
//                'invoice_id' => $request->input('InvoiceId'),
//                'transaction_id' => $request->input('TransactionId'),
//                'currency' => $request->input('Currency'),
//                'cardFirstSix' => $request->input('CardFirstSix'),
//                'cardLastFour' => $request->input('CardLastFour'),
//                'cardType' => $request->input('CardType'),
//                'name' => $request->input('Name'),
//                'email' => $request->input('Email'),
//                'issuer' => $request->input('Issuer'),
//                'token' => $request->input('Token'),
//            ]);
////            $invoice->cplog()->save($cplog);
//
//            $invoice->paid_on = $request->input('DateTime');
//            $invoice->paid = TRUE;
//            $invoice->save();
//
//            $code = 0;
//
//        }
//
//
//        return $code;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function pays(Request $request)
    {
        $invoice = Invoice::where('id', $request->InvoiceId)->first();

        if($invoice->amount == $request->Amount) {
            CPLog::updateOrCreate([
                'invoice_id' => $request->InvoiceId,
                'transaction_id' => $request->TransactionId,
                'currency' => $request->Currency,
                'cardFirstSix' => $request->CardFirstSix,
                'cardLastFour' => $request->CardLastFour,
                'cardType' => $request->CardType,
                'name' => $request->Name,
                'email' => $request->Email,
                'issuer' => $request->Issuer,
                'token' => $request->Token,
            ]);
            $invoice->paid = true;
            $invoice->paid_at = $request->DateTime;
            $invoice->save();
            if($invoice->type_id == 1) {
                $subscribe = Subscribe::where('user_id', $invoice->user_id)->first();
                $interval = $subscribe->interval;
                $end = Carbon::parse($subscribe->end_at);
                if($interval == 'month') {
                    $dt = Carbon::parse($subscribe->end_at)->addMonth();
                }
                if($interval == 'year') {
                    $dt = Carbon::parse($subscribe->end_at)->addYear();
                }
                $subscribe->start_at = $end;
                $subscribe->end_at = $dt;
                $subscribe->active = true;
                $subscribe->save();
            }
            if($invoice->type_id == 2) {
                $subscribe = Subscribe::where('user_id', $invoice->user_id)->first();
                $plan = Plan::find($invoice->plan_id);
                $now = Carbon::now();
                if($plan->interval == 'month') {
                    $dt = Carbon::now()->addMonth();
                }
                if($plan->interval == 'year') {
                    $dt = Carbon::now()->addYear();
                }
                $subscribe->plan_id = $plan->id;
                $subscribe->interval = $plan->interval;
                $subscribe->start_at = $now;
                $subscribe->end_at = $dt;
                $subscribe->active = true;
                $subscribe->save();
            }
        }

        return ['error' => 0];
    }
}
