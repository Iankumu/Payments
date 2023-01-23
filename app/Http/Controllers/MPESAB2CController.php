<?php

namespace App\Http\Controllers;

use App\Mpesa\B2C;
use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class MPESAB2CController extends Controller
{

    public function simulate(Request $request)
    {
        $phoneno = $request->input('phonenumber');
        $amount = $request->input('amount');
        $remarks = $request->input('remarks');
        $command = $request->input('command');


        $response = Mpesa::b2c($phoneno, $command, $amount, $remarks);

        $result = json_decode((string)$response);

        return Inertia::render('Payments/Partials/B2C', [
            'response' => $result,
        ]);
    }

    public function result(Request $request)
    {
        $b2c_confirm = (new B2C())->results($request);
        if ($b2c_confirm) {
            return response()->json([
                'message' => 'Withdrawal Successful'
            ], Response::HTTP_OK); //200
        }
    }
    public function timeout(Request $request)
    {
        Log::info("Timeout URL has been hit");
        Log::info($request->all());
    }
}
