<?php

namespace App\Http\Controllers;

use App\Models\SoilSample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PublicController extends Controller
{
    public function home()
    {
        $total = SoilSample::count();
        $reported = SoilSample::where('status', 'reported')->count();
        $farmers = \App\Models\Farmer::count();
        $pending = SoilSample::whereIn('status', ['collected', 'received'])->count();

        return view('public.home', compact('total', 'reported', 'farmers', 'pending') + ['samples' => collect(), 'phone' => '']);
    }

    public function lookup(Request $request)
    {
        $phone = $request->input('phone');
        $samples = collect();

        $total = SoilSample::count();
        $reported = SoilSample::where('status', 'reported')->count();
        $farmers = \App\Models\Farmer::count();
        $pending = SoilSample::whereIn('status', ['collected', 'received'])->count();

        if ($phone) {
            $samples = SoilSample::with(['farmer', 'plot.crop', 'plot.soilType', 'results.parameter', 'lab'])
                ->whereHas('farmer', fn($q) => $q->where('phone', $phone))
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('public.home', compact('samples', 'phone', 'total', 'reported', 'farmers', 'pending'));
    }

    public function track()
    {
        return view('public.track', ['samples' => collect(), 'phone' => '']);
    }

    public function accessibility()
    {
        return view('public.accessibility');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function trackLookup(Request $request)
    {
        if (setting('recaptcha_enabled', false) && app()->environment('production')) {
            $request->validate(['g-recaptcha-response' => 'required']);
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => setting('recaptcha_secret_key', ''),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);
            if (!($response->json()['success'] ?? false)) {
                return back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.']);
            }
        }

        $phone = $request->input('phone');
        $samples = collect();

        if ($phone) {
            $samples = SoilSample::with(['farmer', 'plot.crop', 'plot.soilType'])
                ->whereHas('farmer', fn($q) => $q->where('phone', $phone))
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('public.track', compact('samples', 'phone'));
    }

}
