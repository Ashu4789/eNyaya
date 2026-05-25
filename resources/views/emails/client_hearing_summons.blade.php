<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Times New Roman', serif; line-height: 1.6; color: #1a202c; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #0b2d4d; padding-bottom: 10px; margin-bottom: 20px; }
        .emblem { font-size: 24px; font-weight: bold; color: #0b2d4d; }
        .title { text-transform: uppercase; font-size: 18px; margin-top: 10px; letter-spacing: 1px; }
        .summons-box { border: 1px solid #cbd5e1; padding: 15px; border-radius: 8px; background-color: #f8fafc; margin-bottom: 20px; }
        .details-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .details-table th, .details-table td { padding: 8px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        .details-table th { color: #475569; font-weight: bold; width: 30%; }
        .footer { font-size: 12px; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 15px; margin-top: 30px; }
        .highlight { color: #7b1e2b; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="emblem">eNyaya Legal Portal</div>
        <div class="title">Official Court Summons / Notice</div>
    </div>
    <p>Dear {{ $hearing->legalCase?->petitioner_name }},</p>
    <p>You are hereby summoned/notified to appear either in person or through your authorized advocate before the Hon'ble Court for the scheduled hearing in the matter detailed below:</p>
    
    <div class="summons-box">
        <h3 style="margin-top: 0; color: #0b2d4d;">Case Details</h3>
        <table class="details-table">
            <tr><th>Case Number:</th><td><strong>{{ $hearing->legalCase?->case_number }}</strong></td></tr>
            <tr><th>Case Title:</th><td>{{ $hearing->legalCase?->title }}</td></tr>
            <tr><th>Category:</th><td>{{ $hearing->legalCase?->category }}</td></tr>
            <tr><th>Scheduled Date:</th><td class="highlight">{{ $hearing->scheduled_at->format('d M Y') }}</td></tr>
            <tr><th>Scheduled Time:</th><td class="highlight">{{ $hearing->scheduled_at->format('h:i A') }}</td></tr>
            <tr><th>Courtroom:</th><td><strong>{{ $hearing->courtroom }}</strong></td></tr>
            <tr><th>Sequence:</th><td>No. {{ $hearing->hearing_sequence ?? 'N/A' }} on the cause list</td></tr>
        </table>
    </div>

    <p><strong>Purpose of Hearing:</strong> {{ $hearing->purpose ?? 'General Proceedings' }}</p>
    @if($hearing->notes)
        <p><strong>Special Instructions/Notes:</strong> {{ $hearing->notes }}</p>
    @endif

    <p style="font-size: 14px; margin-top: 25px;">Please ensure all necessary documents and pleadings are filed and verified by your advocate prior to the hearing. Non-attendance may result in ex-parte proceedings as per the rules of the court.</p>

    <div class="footer">
        <p>This is a system-generated official notice from eNyaya Portal.<br>Department of Justice & Court Administration Complex.</p>
    </div>
</body>
</html>
