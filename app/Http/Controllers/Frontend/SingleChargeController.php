<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\HasPayment;
use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\SingleCharge;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class SingleChargeController extends Controller
{
    use HasPayment;

    public function index(SingleCharge $singleCharge)
    {
        abort_if(!$singleCharge->status, 404);
        $this->clearSessions();
        $gateways = Gateway::where('is_auto',1)->whereStatus(1)
            ->when(!Auth::check(), function ($builder){
                $builder->whereNotIn("namespace", ["App\\Lib\\Credit"]);
            })
            ->orderBy('name')
            ->get();

        $singleCharge->load('user.currency', 'currency');

        return view('frontend.singleCharge.index', compact('singleCharge', 'gateways'));
    }

    public function gateway(Request $request, SingleCharge $singleCharge)
    {
        $request->validate([
            'gateway' => ['required', 'exists:gateways,id'],
            'amount' => [Rule::requiredIf(fn() => !$singleCharge->amount), 'numeric']
        ]);
        $this->clearSessions();
        $amount = $singleCharge->amount > 0 ? $singleCharge->amount : $request->input('amount');
        Session::put('amount', $amount);

        $gateway = Gateway::findOrFail($request->input('gateway'));

        return view('frontend.singleCharge.gateway', compact('singleCharge', 'gateway', 'amount'));
    }

    public function payment(Request $request, SingleCharge $singleCharge, Gateway $gateway)
    {
        $this->validateRequest($request, $gateway);

        //Store Data For Save to DB
        Session::put('singlePaymentData', [
            'singleCharge' => $singleCharge,
            'gateway' => $gateway
        ]);

        if (Auth::check()){
            $info = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email
            ];
        }else{
            $info = [
                'name' => $request->input('name'),
                'email' => $request->input('email')
            ];
        }

        $amount = Session::get('amount');

        $convertedAmount = convert_money_direct($amount, $singleCharge->currency, $gateway->currency);
        $data = [
            'currency' => $gateway->currency->code,
            'name' => $info['name'],
            'email' => $info['email'],
            'phone' => $request->input('phone'),
            'billName' => 'Single Charge',
            'amount' => $convertedAmount,
            'test_mode' => $gateway->test_mode,
            'charge' => $gateway->charge,
            'pay_amount' => round($convertedAmount + $gateway->charge, 2),
            'gateway_id' => $gateway->id,
            'payment_type' => 'single_charge',
            'request_from' => 'merchant'
        ];

        Session::put('userInfo', $info);
        Session::put('without_tax', true);
        Session::put('fund_callback.success_url', '/payment/success');
        Session::put('fund_callback.cancel_url', '/payment/failed');
        if (!Auth::check()){
            Session::put('without_auth', true);
        }else{
            Session::put('without_auth', false);
        }
        return $this->proceedToPayment($request, $gateway, $data);
    }
}
