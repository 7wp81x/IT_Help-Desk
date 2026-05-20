<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function general()
    {
        return view('admin.settings.general');
    }

    public function emailTemplates()
    {
        return view('admin.settings.email-templates');
    }

    public function sla()
    {
        return view('admin.settings.sla');
    }

    public function knowledgeBase()
    {
        // Knowledge base settings removed
        return redirect()->route('admin.settings.index');
    }

    public function faqs()
    {
        return view('admin.settings.faqs');
    }

    public function backup()
    {
        return view('admin.settings.backup');
    }
}