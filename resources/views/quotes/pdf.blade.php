<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <title>اقتباس</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .quote { margin: 20px; padding: 20px; border: 1px solid #ccc; }
        .content { font-size: 18px; margin-bottom: 10px; }
        .author { color: #666; }
        .feeling { color: #888; }
    </style>
</head>
<body>
    <div class="quote">
        <div class="content">{{ $quote->content }}</div>
        <div class="author">{{ $quote->user->name }}</div>
        @if($quote->feeling)
            <div class="feeling">#{{ $quote->feeling }}</div>
        @endif
    </div>
</body>
</html>