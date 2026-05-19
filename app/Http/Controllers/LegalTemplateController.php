<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class LegalTemplateController extends Controller
{
    private const TEMPLATES = [
        'affidavit' => 'Affidavit',
        'petition' => 'Petition',
        'vakalatnama' => 'Vakalatnama',
        'hearing-notice' => 'Hearing Notice',
    ];

    public function download(string $template): Response
    {
        abort_unless(array_key_exists($template, self::TEMPLATES), 404);

        $title = self::TEMPLATES[$template];
        $body = view('templates.document', ['title' => $title])->render();

        return response($body, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="'.$template.'-template.html"',
        ]);
    }
}
