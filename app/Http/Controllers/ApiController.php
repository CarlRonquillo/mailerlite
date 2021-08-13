<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MailerLiteApi\MailerLite;
use App\Models\Key;

class ApiController extends Controller
{
    public function list(Request $request)
    {
        try {
            $key = Key::select('key')->first();
            $subscribersApi = (new MailerLite($key->key))->subscribers();

            $columns = ['#','email','name','country','date','time'];

            $draw = $request->input('draw');
            $limit = $request->input('length') ? : 10;
            $offset = $request->input('start') ? : 0;
            $field = $columns[(int)$request->input('order.0.column') ? : 4];
            $direction = $request->input('order.0.dir') ? : 'DESC';
            $searchQuery = $request->input('search.value') ? : null;
            $subsTotal = $subscribersApi->get()->count();
            $filteredCount = $subsTotal;

            if ($searchQuery) {
                $subscribers = $subscribersApi->search($searchQuery);
                $filteredCount = count($subscribers);
            } else {
                $subscribers = $subscribersApi
                    ->orderBy($field, strtoupper($direction))
                    ->limit($limit)
                    ->offset($offset)
                    ->get()
                    ->toArray();
            }

            if (isset($subscribers[0]->error)) {
                return response()->json($subscribers[0]->error, 500);
            }

            $data = [];
            $count = 0;
            if ($filteredCount) {
                foreach ($subscribers as $subscriber) {
                    $count++;
                    //convert id to string. frontend is rounding off
                    $id = (string)$subscriber->id;

                    $datum  = [
                        '#' => $count,
                        'email' => '<a href="/subscribers/'. $id .'/edit" class="fs-6 text-primary">'
                                    .$subscriber->email.'</a>',
                        'name' => $subscriber->name,
                        'country' => get_country($subscriber->fields),
                        'date' => get_date_time($subscriber->date_created),
                        'time' => get_date_time($subscriber->date_created, true),
                        'delete' => '<button class="btn-delete fs-6 text-danger btn btn-link p-0" data-id='
                                    . $id .'>delete</button>'
                    ];

                    array_push($data,$datum);
                }
            }

            $responseData = [
                'draw' => $draw,
                'recordsTotal' => $subsTotal,
                'recordsFiltered' => $filteredCount,
                'data' => $data,
                'query' => $searchQuery
            ];

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
