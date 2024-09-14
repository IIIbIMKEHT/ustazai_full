<?php

namespace App\Livewire;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

class ContentViewer extends Component
{
    
    public function exportToPDF($content): void
    {
        $fileName = Str::random(10).'.pdf';
        $pdf = Pdf::loadHTML($content)->setPaper('a4', 'landscape')->setWarnings(false)->save($fileName);
        $pdf->download($fileName);
    }
    public function render()
    {
        return view('livewire.content-viewer');
    }
}
