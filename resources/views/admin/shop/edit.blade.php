<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form action="{{ route('admin.shop.update', $products->product_id) }}" method="POST">
        @csrf
        @method('PUT')
    <h1>出品情報修正</h1>

    <div>
     <label>商品金額</label>
     <input type="text" name="comment" value="{{ old('wish_price', $products->wish_price) }}">
    </div>


    <button type="submit">修正する</button>
</form>
</body>
</html>