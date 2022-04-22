<?php
namespace App\Interfaces;

use App\Models\Agent;
use Illuminate\Http\Request;
 interface SearchServiceInterface{

     function api(Request $request, Agent $agent);
     function web(Request $request, $pagination);

 }
