<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR Code Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 1.5rem;
            color: #333;
        }

        img {
            max-width: 300px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: #fff;
        }

        .button {
            display: inline-block;
            margin: 1rem 0.5rem 0 0.5rem;
            padding: 0.75rem 1.5rem;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .button.secondary {
            background-color: #6c757d;
        }

        .button.secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>QR Code for Serial: {{ $serial }}</h1>
        <img src="{{ $imageData }}" alt="QR Code">
        <br>
        <a href="{{ $downloadUrl }}" class="button">Download QR Code</a>
        <button class="button secondary" onclick="history.back()">Return</button>
    </div>
</body>
</html>
