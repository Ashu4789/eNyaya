<?php

return [
    'uri' => env('MONGODB_URI', 'mongodb://127.0.0.1:27017'),
    'database' => env('MONGODB_DATABASE', 'enyaya_documents'),
    'collections' => [
        'activity_logs' => 'activity_logs',
        'document_metadata' => 'document_metadata',
        'audit_history' => 'audit_history',
        'system_logs' => 'system_logs',
        'hearing_notes' => 'hearing_notes',
    ],
];
