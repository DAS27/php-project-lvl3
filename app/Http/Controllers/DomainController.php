<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{
    public function index()
    {
        $domains = DB::table('domains')->get();
        $lastChecks = DB::table('domain_checks')
            ->distinct('domain_id')
            ->orderBy('domain_id')
            ->orderBy('updated_at', 'asc')
            ->get()
            ->keyBy('domain_id');
        return view('domains', compact('domains', 'lastChecks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|url|max:255'
        ]);
        if ($validator->fails()) {
            flash('Not a valid url')->error();
            return redirect()->route('home');
        }
        $domain = $validator->valid();
        $url = parse_url($domain['name']);
        ['scheme' => $scheme, 'host' => $host] = $url;
        $normalizedUrl = strtolower("{$scheme}://{$host}");
        $existingDomain = DB::table('domains')->where('name', $normalizedUrl)->first();
        if ($existingDomain) {
            flash('Url already exists')->warning();
            return redirect()->route('domain.show', ['id' => $existingDomain->id]);
        }
        $id = DB::table('domains')->insertGetId(
            [
                'name' => $normalizedUrl,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
        flash('Url has been added')->success();
        return redirect()->route('domain.show', ['id' => $id]);
    }

    public function show($id)
    {
        $domain = DB::table('domains')->find($id);
        $domainChecks = DB::table('domain_checks')
            ->where('domain_id', $id)
            ->paginate(10);
        return view('show', compact('domain', 'domainChecks'));
    }

    public function check($id)
    {
        $domain = DB::table('domains')->find($id);
        $response = Http::get($domain->name);
        $statusCode = $response->status();
        DB::table('domain_checks')->insert(
            [
                'domain_id' => $id,
                'status_code' => $statusCode,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
        flash('Website has been checked!');
        return redirect()->route('domain.show', ['id' => $id]);
    }
}
