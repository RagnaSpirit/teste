<?php
namespace App\RestaurantOps\Http\Controllers;
use App\Http\Controllers\Controller;
use App\RestaurantOps\Models\{RestaurantOrder,RestaurantTable,RestaurantTab};
use App\RestaurantOps\Repositories\RestaurantOrderRepository;
use App\RestaurantOps\Services\RestaurantOrderService;
use Illuminate\Http\Request;

class RestaurantOpsController extends Controller
{
 public function pos(){ return view('admin-views.restaurant-ops.pos'); }
 public function kds(RestaurantOrderRepository $repo){ return view('admin-views.restaurant-ops.kds',['orders'=>$repo->kds()]); }
 public function dashboard(RestaurantOrderRepository $repo){ return response()->json($repo->todayMetrics()); }
 public function storeOrder(Request $r, RestaurantOrderService $svc){ $data=$r->validate(['type'=>'required|string','items'=>'required|array','payments'=>'array','customer_name'=>'nullable|string','table_id'=>'nullable|integer','tab_id'=>'nullable|integer','coupon_code'=>'nullable|string','discount_amount'=>'nullable|numeric','notes'=>'nullable|string','priority'=>'nullable|integer']); return response()->json($svc->createOrder($data)->load('histories'),201); }
 public function updateStatus(RestaurantOrder $order, Request $r, RestaurantOrderService $svc){ $data=$r->validate(['status'=>'required|string','notes'=>'nullable|string']); return response()->json($svc->changeStatus($order,$data['status'],['type'=>'admin','notes'=>$data['notes'] ?? null])->load('histories')); }
 public function print(RestaurantOrder $order, Request $r, RestaurantOrderService $svc){ return response()->json(['print_jobs'=>$svc->createPrintJobs($order,$r->input('sectors',['kitchen','beverages','desserts']))]); }
 public function tables(){ return response()->json(RestaurantTable::orderBy('name')->get()); }
 public function tabs(Request $r){ return response()->json(RestaurantTab::create($r->validate(['code'=>'required|string|unique:restaurant_tabs,code','restaurant_table_id'=>'nullable|integer']))); }
}
