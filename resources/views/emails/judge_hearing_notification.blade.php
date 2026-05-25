<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Georgia', serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 3px double #7b1e2b; padding-bottom: 8px; text-align: center; margin-bottom: 25px; }
        .chamber-title { text-transform: uppercase; font-size: 16px; font-weight: bold; color: #7b1e2b; letter-spacing: 2px; }
        .docket-box { border: 1px solid #cbd5e1; border-top: 4px solid #0b2d4d; border-radius: 4px; padding: 20px; background-color: #fcfcfc; }
        .docket-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .docket-table td { padding: 6px; border-bottom: 1px solid #f1f5f9; }
        .docket-table td.label { font-weight: bold; width: 35%; color: #475569; }
        .summary-block { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 4px; margin-top: 15px; }
        .footer { font-size: 11px; color: #94a3b8; text-align: center; margin-top: 30px; border-top: 1px dashed #cbd5e1; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="chamber-title">Chambers of Hon\'ble Judge</div>
        <span style="font-size: 13px; color: #64748b;">eNyaya Docket Scheduling Alert</span>
    </div>
    
    <p>Respected Judge,</p>
    <p>A new hearing has been listed on your judicial docket. Please review the case scheduling details below:</p>

    <div class="docket-box">
        <h3 style="margin-top: 0; color: #0b2d4d; border-bottom: 1px solid #cbd5e1; padding-bottom: 6px;">Hearing Docket Details</h3>
        <table class="docket-table">
            <tr><td class="label">Case Number:</td><td><strong>{{ $hearing->legalCase?->case_number }}</strong></td></tr>
            <tr><td class="label">Case Title:</td><td>{{ $hearing->legalCase?->title }}</td></tr>
            <tr><td class="label">Category:</td><td>{{ $hearing->legalCase?->category }}</td></tr>
            <tr><td class="label">Priority Level:</td><td><span style="text-transform: uppercase; font-weight: bold; color: {{ $hearing->legalCase?->priority === 'urgent' ? '#7b1e2b' : '#0b2d4d' }}">{{ $hearing->legalCase?->priority }}</span></td></tr>
            <tr><td class="label">Date & Time:</td><td><strong>{{ $hearing->scheduled_at->format('d M Y \a\t h:i A') }}</strong></td></tr>
            <tr><td class="label">Courtroom:</td><td>{{ $hearing->courtroom }}</td></tr>
            <tr><td class="label">List Sequence:</td><td>#{{ $hearing->hearing_sequence ?? 'N/A' }}</td></tr>
        </table>
        
        <div class="summary-block">
            <strong>Hearing Purpose:</strong><br>
            {{ $hearing->purpose ?? 'General Proceedings' }}
            @if($hearing->notes)
                <br><br><strong>Docket Notes:</strong><br>
                <span style="font-size: 13px; color: #475569;">{{ $hearing->notes }}</span>
            @endif
        </div>
    </div>

    <p style="font-size: 14px;">The case summary, past adjournment history, and filed evidence files are available on your judicial portal for pre-hearing review.</p>

    <div class="footer">
        <p>eNyaya Court Registrar & Docket Administration Office. Confidential Judicial Document.</p>
    </div>
</body>
</html>
