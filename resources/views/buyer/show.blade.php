<x-buyer>
    <div class="buyer-container">
        <h2 style="text-align: center; margin-bottom: 20px;">商品詳細情報</h2>
        
        <div style="background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto;">
            
            {{-- 商品画像エリア --}}
            <div style="text-align: center; margin-bottom: 30px;">
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="商品画像" style="max-width: 100%; max-height: 300px; border-radius: 8px; border: 1px solid #e2e8f0; object-fit: cover;">
                @else
                    <div style="padding: 60px; background: #f8fafc; color: #94a3b8; border-radius: 8px; border: 2px dashed #cbd5e1; font-weight: bold;">画像なし</div>
                @endif
            </div>

            {{-- 詳細テーブル --}}
            <table style="width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 30px;">
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 15px 10px; width: 30%; color: #64748b;">商品ID</th>
                    <td style="padding: 15px 10px; font-weight: bold;">{{ $product->product_id }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 15px 10px; color: #64748b;">商品名</th>
                    <td style="padding: 15px 10px; font-weight: bold; font-size: 1.1rem; color: #1f2937;">{{ $product->product_name }}</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 15px 10px; color: #64748b;">希望価格</th>
                    <td style="padding: 15px 10px; color: #dc2626; font-weight: bold; font-size: 1.2rem;">{{ number_format($product->wish_price) }} 円</td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 15px 10px; color: #64748b;">落札期限</th>
                    <td style="padding: 15px 10px;">{{ \Carbon\Carbon::parse($product->end_date)->format('Y/m/d H:i') }}</td>
                </tr>
                <tr>
                    <th style="padding: 15px 10px; vertical-align: top; color: #64748b;">商品説明</th>
                    <td style="padding: 15px 10px; white-space: pre-wrap; line-height: 1.6; color: #374151;">{{ $product->comment }}</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="javascript:history.back()" class="btn-action" style="background-color: #64748b; width: 100%; box-sizing: border-box; padding: 12px; font-size: 1.1rem;">戻る</a>
            </div>
        </div>
    </div>
</x-buyer>