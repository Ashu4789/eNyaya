<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} Template</title>
    <style>
        body { font-family: "Times New Roman", serif; line-height: 1.55; margin: 48px; color: #111; }
        h1 { text-align: center; text-transform: uppercase; font-size: 20px; }
        .line { border-bottom: 1px solid #444; display: inline-block; min-width: 240px; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>In the Court of <span class="line"></span></p>
    <p>Case No: <span class="line"></span></p>
    <p>Petitioner: <span class="line"></span></p>
    <p>Respondent: <span class="line"></span></p>
    <p>Subject: <span class="line"></span></p>
    <p>I/We submit this {{ strtolower($title) }} for the consideration of the Hon'ble Court.</p>
    <p style="margin-top: 80px;">Date: <span class="line"></span></p>
    <p>Place: <span class="line"></span></p>
    <p style="text-align: right; margin-top: 80px;">Signature: <span class="line"></span></p>
</body>
</html>
