<?php

namespace App\Console\Commands;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Models\DonHang;
use Illuminate\Support\Facades\Log;


class ThanhToanTuDongCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:mb';

    /**
     * The console command description.
     * 
     *
     * @var string
     */
    protected $description = 'Thanh toán tự động';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while(true){
            printf("lấy danh sách mới nhất tại thời điểm : " . now() . "\n");
            $data = $this->getListMB();
           
            $list_don_hang = DonHang::where('is_thanh_toan', DonHang::CHUA_THANH_TOAN)
                                                    ->where("payment_method", DonHang::LOAI_THANH_TOAN_ONLINE)
                                                    ->select('id', 'order_code', 'total_amount')
                                                    ->get(); ;
            foreach ($data as $transaction) {
                $orderCode = $this->getOrderCode($transaction['description']);
                if($orderCode) {
                    foreach ($list_don_hang as $don_hang) {
                        if ($orderCode == $don_hang->order_code  && $don_hang->total_amount  == (int)$transaction['creditAmount']) {
                            $don_hang->is_thanh_toan = DonHang::DA_THANH_TOAN;
                            $don_hang->save();
                            $this->info("Thanh toán thành công: " . $orderCode . " - " . $don_hang->total_amount);
                        }
                    }
                }
            }
            sleep(5);
        }
    }
    private function getOrderCode($description)
    {
        preg_match('/DH[0-9]+/', $description, $matches);
        return $matches[0] ?? null;
    }
    private function getListMB()
    {
        $client = new Client();
        $response = $client->request('POST', 'https://api-mb.midstack.io.vn/api/transactions', [
             'json' => [
                "USERNAME"  => "0813559551",
                "PASSWORD"  => "Truong0812@@@@",
                "DAY_BEGIN" => Carbon::now()->format('d/m/Y'),
                "DAY_END"   => Carbon::now()->format('d/m/Y'),
                "NUMBER_MB" => "0813559551"
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        // dump($data);

        return $data["data"]["transactionHistoryList"];
    }

}
