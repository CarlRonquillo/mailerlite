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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key = Key::select('key','id')->first();

        if ($key) {
            //save on session
            $this->saveSession([
                'key' => $key['key'],
                'key_id' => $key['id']
            ]);

            return redirect('/subscribers');
        }

        return redirect('/keys/create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('keys.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
                //save on session
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $key = Key::find($id)->first();

        if($key->delete()) {
            //delete session
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
