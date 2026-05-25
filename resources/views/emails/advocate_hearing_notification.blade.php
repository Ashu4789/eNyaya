<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #334155; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0b2d4d; color: #ffffff; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { border: 1px solid #e2e8f0; padding: 20px; border-radius: 0 0 8px 8px; border-top: 0; }
        .details-box { background-color: #f8fafc; border-left: 4px solid #198754; padding: 15px; margin: 15px 0; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table td { padding: 6px 0; }
        .details-table td.label { font-weight: 600; color: #475569; width: 35%; }
        .btn { display: inline-block; padding: 10px 18px; background-color: #0b2d4d; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: 600; margin-top: 15px; }
        .footer { font-size: 11px; color: #94a3b8; text-align: center; margin-top: 25px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0; font-size: 20px;">eNyaya Advocate Portal</h2>
        <span style="font-size: 13px; opacity: 0.9;">Professional Hearing Notice & Procedural Agenda</span>
    </div>
    <div class="content">
        <p>Dear Advocate,</p>
        <p>This is to inform you that a hearing has been scheduled/updated for a case in which you are the designated counsel of record:</p>
        
        <div class="details-box">
            <table class="details-table">
                <tr><td class="label">Case Number:</td><td><strong>{{ $hearing->legalCase?->case_number }}</strong></td></tr>
                <tr><td class="label">Case Title:</td><td>{{ $hearing->legalCase?->title }}</td></tr>
                <tr><td class="label">Client:</td><td>{{ $hearing->legalCase?->client?->name ?? $hearing->legalCase?->petitioner_name }}</td></tr>
                <tr><td class="label">Date & Time:</td><td><strong>{{ $hearing->scheduled_at->format('d M Y \a\t h:i A') }}</strong></td></tr>
                <tr><td class="label">Courtroom:</td><td>{{ $hearing->courtroom }}</td></tr>
                <tr><td class="label">Cause List Pos:</td><td>Sequence #{{ $hearing->hearing_sequence ?? 'N/A' }}</td></tr>
            </table>
        </div>

        <p><strong>Hearing Agenda / Purpose:</strong> {{ $hearing->purpose ?? 'General Arguments' }}</p>
        
        @if($hearing->notes)
            <p><strong>Case Notes:</strong> <span style="font-style: italic; color: #64748b;">"{{ $hearing->notes }}"</span></p>
        @endif

        <p>Please log in to the eNyaya dashboard to verify documentation status, upload pending vakalatnama, and prepare necessary evidence folders.</p>
        
        <p style="text-align: center;">
            <a href="{{ route('dashboard') }}" class="btn" style="color: white;">Access Case Dashboard</a>
        </p>
    </div>
    <div class="footer">
        <p>This notification is sent to registered members of the Bar. Please do not reply directly to this email.</p>
    </div>
</body>
</html>
