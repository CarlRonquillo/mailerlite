<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Key;
use MailerLiteApi\MailerLite;

class KeysController extends Controller
{
    protected $request;

    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    public function index()
    {
        $key = Key::select('key','id')->first();

        if ($key) {
            $this->saveSession([
                'key' => $key['key'],
                'key_id' => $key['id']
            ]);

            return redirect('/subscribers');
        }

        return redirect('/keys/create');
    }

    public function create()
    {
        $key = Key::select('key')->first();
        if(isset($key->key) && $this->request->path() !== '/keys/create') {
            return back();
        }
        
        return view('keys.create');
    }

    public function store()
    {
        $this->request->validate([
            'api_key' => 'required'
        ]);

        $message = '';
        $inputKey = $this->request->input('api_key');
        
        $groupsApi = (new MailerLite($inputKey))
                        ->groups()
                        ->get()
                        ->toArray();

        if (isset($groupsApi['0']->error)) {
            $message = 'API Key is invalid.';
        } else {
            $key = Key::create(['key' => $inputKey]);
    
            if($key) {
                $this->saveSession([
                    'key' => $key['key'],
                    'key_id' => $key['id']
                ]);
    
                return redirect('/subscribers');
            }
        }

        $this->request->session()->flash('key', $inputKey);

        return redirect('/keys/create')->with('error',$message);
    }

    public function destroy($id)
    {
        $key = Key::find($id)->first();

        if($key->delete()) {
            $this->request->session()->flush();
        }

        return redirect('/');
    }

    public function saveSession($data)
    {
        foreach($data as $key => $value) {
            $this->request->session()->put($key,$value);
        }
    }
}
