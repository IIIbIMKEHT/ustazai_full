<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialType;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function generate($id)
    {
        $type = MaterialType::findOrFail($id);
        return view('generate', compact('type'));
    }

    public function myMaterials($subjectID)
    {
        $materials = Material::with('type')->where(['user_id' => auth()->id(), 'subject_id' => $subjectID])->latest()->paginate(20);
        return view('my-materials', compact('materials'));
    }

    public function showMaterial($materialID)
    {
        $material = Material::with('type')->findOrFail($materialID);
        return view('show-material', compact('material'));
    }

    public function deleteMaterial($materialID)
    {
        $material = Material::findOrFail($materialID);
        $material->delete();
        return redirect()->back();
    }
}
