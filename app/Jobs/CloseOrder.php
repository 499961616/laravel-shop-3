<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CloseOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order,$delay)
    {
        $this->order = $order;

        $this->delay($delay);
    }



    // 判断对应的订单是否已经被支付
    // 如果已经支付则不需要关闭订单，直接退出
    public function handle()
    {
        if ($this->order->paid_at){
            return;
        }

        DB::transaction(function (){

            $this->order->update(['closed'=>true]);

            foreach ($this->order->items as $item){
                $item->productSku->addStock($item->amount);
            }
            if ($this->order->couponCode){
                $this->order->couponCode->changeUsed(false);
            }
        });
    }
}
