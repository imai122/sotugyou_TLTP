@if (session('success'))
    <div style="color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 12px; margin-bottom: 20px; border-radius: 4px; font-weight: bold;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; margin-bottom: 20px; border-radius: 4px; font-weight: bold;">
        {{ session('error') }}
    </div>
@endif