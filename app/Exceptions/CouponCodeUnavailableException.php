<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Exception;
use Throwable;

class CouponCodeUnavailableException extends Exception
{
    public function __construct($message = "", $code = 403)
    {
        parent::__construct($message, $code);
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()){
            return response()->json(['msg' => $this->message], $this->code);
        }
        // 否则返回上一页并带上错误信息
        return redirect()->back()->withErrors(['coupon_code' => $this->message]);
    }
}
