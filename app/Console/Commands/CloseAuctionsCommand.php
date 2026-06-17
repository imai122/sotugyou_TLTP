<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\signature;
use Illuminate\Console\Command;
use App\Models\Bid;
use App\Models\Product;
use App\Models\YIC_user;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

// #[Signature('app:close-auctions-command')]
// #[Description('Command description')]
class CloseAuctionsCommand extends Command
{

    protected $signature = 'auction:close';//手動の時に入力

    // コマンドの説明
    protected $description = '終了時間を過ぎたオークションの落札者を自動で決定する';//命令

    public function handle()
    {
        \Log::info("auction:close コマンドが実行されました。現在時刻: " . now());
        // REPLACEを使用してTを除去し、正しく現在時刻と比較する
        $expiredProducts = Product::whereRaw("REPLACE(end_date, 'T', ' ') <= ?", [now()->format('Y-m-d H:i:s')])
            ->whereDoesntHave('transactions')
            ->get();

        // 2. 抽出結果のデバッグ表示
        $this->info("処理対象の商品数: " . $expiredProducts->count());

        if ($expiredProducts->isEmpty()) {
            $this->info('処理対象の商品はありません。');
            return Command::SUCCESS;
        }

        // 3. ループ処理
        foreach ($expiredProducts as $product) {
            $this->info("処理中の商品ID: " . $product->product_id);

            // 入札の最大値を取得
            $max_product = Bid::where('product_id', (int)$product->product_id)
                ->orderBy('bid_amount', 'desc')
                ->first();

            if (!$max_product || empty($max_product->bid_amount)) {
                $this->error("商品ID: {$product->product_id} は落札金額が不明なためスキップしました。");
                continue;
            }//金額が入力されていない場合

            
            //落札者の情報を取得
            $buyer = YIC_user::where('user_id', (string)$max_product->bidder_id)->first();
            //出品者の情報を取得
            $seller = YIC_user::where('user_id', (string)$product->seller_id)->first(); 
            // 取引データの作成
            try {
                $transaction = Transaction::create([
                    'transaction_id' => $this->generateTransactionId(),
                    'product_id'     => $product->product_id,
                    'buyer_id'       => $max_product->bidder_id,
                    'winnig_price'   => (int)$max_product->bid_amount,
                    'status'         => 1,
                    'won_at'         => now(),
                ]);
                $this->info("取引を作成しました: " . $transaction->transaction_id);
             
                // CloseAuctionsCommand.php のメール送信部分を修正
if ($seller && $seller->email) {
    try {
        $this->info("出品者へメール送信開始: " . $seller->email);
        Mail::to($seller->email)->send(new ContactMail($transaction, $product, 'seller'));
        $this->info("出品者へのメール送信完了");
    } catch (\Exception $e) {
        //メール送信失敗時のエラーを必ず表示させる
        $this->error("出品者へのメール送信でエラー発生: " . $e->getMessage());
    }

} else {
    $this->error("出品者のメールアドレスが取得できないため送信スキップしました。");
}
                if ($buyer && $buyer->email) {
                    Mail::to($buyer->email)->send(new ContactMail($transaction, $product));
                }

                $this->info("商品ID: {$product->product_id} の落札者を決定しました。(買い手ID: {$max_product->bidder_id})");
            
            } catch (\Exception $e) {
                $this->error("商品ID: {$product->product_id} の保存中にエラーが発生しました: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }



      private function generateTransactionId() {
            
            $lastTransaction = Transaction::orderBy('transaction_id', 'desc')->first();
            
            if ($lastTransaction) {
                
                $lastNumber = (int) substr($lastTransaction->transaction_id, 1);
                $nextNumber = $lastNumber + 1;
            } else {

                $nextNumber = 1;
            }
            
            
            return 'S' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
       }
}

