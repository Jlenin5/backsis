<?php

use App\Http\Controllers\AvatarsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\DocumentContentController;
use App\Http\Controllers\DocumentStatusController;
use App\Http\Controllers\DocumentTypesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\NamesMenuController;
use App\Http\Controllers\PaymentMethodsController;
use App\Http\Controllers\PaymentTypesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductImagesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductBranchOfficeController;
use App\Http\Controllers\QuotationsController;
use App\Http\Controllers\QuoteDetailsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SerialNumberController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\TicketDetailsController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BranchOfficesController;
use App\Http\Controllers\BranchOfficeStaffController;
use App\Http\Controllers\CarriersController;
use App\Http\Controllers\JobPositionController;
use App\Http\Controllers\MobilitiesController;
use App\Http\Controllers\PurchaseOrdersController;
use App\Http\Controllers\TaxesController;
use App\Http\Controllers\WarehousesController;
use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\WorkAreaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AvatarsController::class)->group(function() {
    Route::get('/ava', 'index');
    Route::get('/ava/{id}', 'getId');
    Route::post('/postava', 'store');
    Route::put('/updateava/{id}', 'update');
    Route::delete('/deleteava/{id}', 'destroy');
    Route::get('/avamax', 'getMaxId');
    Route::delete('/delavamulti', 'destroyMultiple');
});
Route::controller(BranchOfficesController::class)->group(function() {
    Route::get('/bo', 'index');
    Route::get('/bo/{id}', 'getId');
    Route::post('/postbo', 'store');
    Route::put('/updatebo/{id}', 'update');
    Route::delete('/deletebo/{id}', 'destroy');
    Route::get('/bomax', 'getMaxId');
    Route::delete('/delbomulti', 'destroyMultiple');
});
Route::controller(BranchOfficeStaffController::class)->group(function() {
    Route::get('/bos', 'index');
    Route::get('/bos/{id}', 'getId');
    Route::post('/postbos', 'store');
    Route::put('/updatebos/{id}', 'update');
    Route::delete('/deletebos/{id}', 'destroy');
    Route::get('/bosmax', 'getMaxId');
    Route::delete('/delbosmulti', 'destroyMultiple');
});
Route::controller(CarriersController::class)->group(function() {
    Route::get('/carr', 'index');
    Route::get('/carr/{id}', 'getId');
    Route::post('/postcarr', 'store');
    Route::put('/updatecarr/{id}', 'update');
    Route::delete('/deletecarr/{id}', 'destroy');
    Route::get('/carrmax', 'getMaxId');
    Route::delete('/delcarrmulti', 'destroyMultiple');
});
Route::controller(CategoriesController::class)->group(function() {
    Route::get('/cate', 'index');
    Route::get('/cate/{id}', 'getId');
    Route::post('/postcate', 'store');
    Route::put('/updatecate/{id}', 'update');
    Route::delete('/deletecate/{id}', 'destroy');
    Route::get('/catemax', 'getMaxId');
    Route::delete('/delcatemulti', 'destroyMultiple');
});
Route::controller(ClientsController::class)->group(function() {
    Route::get('/cli', 'index');
    Route::get('/cli/{id}', 'getId');
    Route::post('/postcli', 'store');
    Route::put('/updatecli/{id}', 'update');
    Route::delete('/deletecli/{id}', 'destroy');
    Route::get('/climax', 'getMaxId');
    Route::delete('/delclimulti', 'destroyMultiple');
});
Route::controller(CompanyController::class)->group(function() {
    Route::get('/com', 'index');
    Route::get('/com/{id}', 'getId');
    Route::post('/postcom', 'store');
    Route::post('/updatecom/{id}', 'update');
    Route::delete('/deletecom/{id}', 'destroy');
    Route::get('/commax', 'getMaxId');
    Route::delete('/delcommulti', 'destroyMultiple');
});
Route::controller(CurrenciesController::class)->group(function() {
    Route::get('/cur', 'index');
    Route::get('/cur/{id}', 'getId');
    Route::post('/postcur', 'store');
    Route::post('/updatecur/{id}', 'update');
    Route::delete('/deletecur/{id}', 'destroy');
    Route::get('/curmax', 'getMaxId');
    Route::delete('/delcurmulti', 'destroyMultiple');
});
Route::controller(UbigeoController::class)->group(function() {
    Route::get('departments', 'departments');
    Route::get('provinces/{department_id}', 'provinces');
    Route::get('districts/{province_id}', 'districts');
});
Route::controller(DocumentContentController::class)->group(function() {
    Route::get('/doco', 'index');
    Route::get('/doco/{id}', 'getId');
    Route::post('/postdoco', 'store');
    Route::post('/updatedoco/{id}', 'update');
    Route::delete('/deletedoco/{id}', 'destroy');
    Route::get('/docomax', 'getMaxId');
    Route::delete('/deldocomulti', 'destroyMultiple');
});
Route::controller(DocumentStatusController::class)->group(function() {
    Route::get('/dost', 'index');
    Route::get('/dost/{id}', 'getId');
    Route::post('/postdost', 'store');
    Route::post('/updatedost/{id}', 'update');
    Route::delete('/deletedost/{id}', 'destroy');
    Route::get('/dostmax', 'getMaxId');
    Route::delete('/deldostmulti', 'destroyMultiple');
});
Route::controller(DocumentTypesController::class)->group(function() {
    Route::get('/doct', 'index');
    Route::get('/doct/{id}', 'getId');
    Route::post('/postdoct', 'store');
    Route::put('/updatedoct/{id}', 'update');
    Route::delete('/deletedoct/{id}', 'destroy');
    Route::get('/doctmax', 'getMaxId');
    Route::delete('/deldoctmulti', 'destroyMultiple');
});
Route::controller(EmployeeController::class)->group(function() {
    Route::get('/emp', 'index');
    Route::get('/emp/{id}', 'getId');
    Route::post('/postemp', 'store');
    Route::put('/updateemp/{id}', 'update');
    Route::delete('/deleteemp/{id}', 'destroy');
    Route::get('/empmax', 'getMaxId');
    Route::delete('/delempmulti', 'destroyMultiple');
});
Route::controller(InvoiceDetailsController::class)->group(function() {
    Route::get('/invd', 'index');
    Route::get('/invd/{id}', 'getId');
    Route::post('/postinvd', 'store');
    Route::put('/updateinvd/{id}', 'update');
    Route::delete('/deleteinvd/{id}', 'destroy');
    Route::get('/invdmax', 'getMaxId');
    Route::delete('/delinvdmulti', 'destroyMultiple');
});
Route::controller(InvoicesController::class)->group(function() {
    Route::get('/inv', 'index');
    Route::get('/inv/{id}', 'getId');
    Route::post('/postinv', 'store');
    Route::put('/updateinv/{id}', 'update');
    Route::delete('/deleteinv/{id}', 'destroy');
    Route::get('/invmax', 'getMaxId');
    Route::delete('/delinvmulti', 'destroyMultiple');
});
Route::controller(JobPositionController::class)->group(function() {
    Route::get('/jp', 'index');
    Route::get('/jp/{id}', 'getId');
    Route::post('/postjp', 'store');
    Route::put('/updatejp/{id}', 'update');
    Route::delete('/deletejp/{id}', 'destroy');
    Route::get('/jpmax', 'getMaxId');
    Route::delete('/deljpmulti', 'destroyMultiple');
});
Route::controller(MobilitiesController::class)->group(function() {
    Route::get('/mob', 'index');
    Route::get('/mob/{id}', 'getId');
    Route::post('/postmob', 'store');
    Route::put('/updatemob/{id}', 'update');
    Route::delete('/deletemob/{id}', 'destroy');
    Route::get('/mobmax', 'getMaxId');
    Route::delete('/delmobmulti', 'destroyMultiple');
});
Route::controller(NamesMenuController::class)->group(function() {
    Route::get('/menu', 'index');
    Route::get('/menu/{id}', 'getId');
    Route::post('/postmenu', 'store');
    Route::put('/updatemenu/{id}', 'update');
    Route::delete('/deletemenu/{id}', 'destroy');
    Route::get('/menumax', 'getMaxId');
    Route::delete('/delmenumulti', 'destroyMultiple');
});
Route::controller(PaymentMethodsController::class)->group(function() {
    Route::get('/paym', 'index');
    Route::get('/paym/{id}', 'getId');
    Route::post('/postpaym', 'store');
    Route::put('/updatepaym/{id}', 'update');
    Route::delete('/deletepaym/{id}', 'destroy');
    Route::get('/paymmax', 'getMaxId');
    Route::delete('/delpaymmulti', 'destroyMultiple');
});
Route::controller(PaymentTypesController::class)->group(function() {
    Route::get('/payt', 'index');
    Route::get('/payt/{id}', 'getId');
    Route::post('/postpayt', 'store');
    Route::put('/updatepayt/{id}', 'update');
    Route::delete('/deletepayt/{id}', 'destroy');
    Route::get('/paytmax', 'getMaxId');
    Route::delete('/delpaytmulti', 'destroyMultiple');
});
Route::controller(PermissionsController::class)->group(function() {
    Route::get('/perm', 'index');
    Route::get('/perm/{id}', 'getId');
    Route::post('/postperm', 'store');
    Route::put('/updateperm/{id}', 'update');
    Route::delete('/deleteperm/{id}', 'destroy');
    Route::get('/permmax', 'getMaxId');
    Route::delete('/delpermmulti', 'destroyMultiple');
});
Route::controller(ProductCategoryController::class)->group(function() {
    Route::get('/prca', 'index');
    Route::get('/prca/{id}', 'getId');
    Route::post('/postprca', 'store');
    Route::put('/updateprca/{id}', 'update');
    Route::delete('/deleteprca/{id}', 'destroy');
    Route::get('/prcamax', 'getMaxId');
    Route::delete('/delprcamulti', 'destroyMultiple');
});
Route::controller(ProductImagesController::class)->group(function() {
    Route::get('/prim', 'index');
    Route::post('/postprim', 'store');
    Route::post('/updateprim/{id}', 'update');
    Route::delete('/deleteprim/{id}', 'destroy');
    Route::get('/primmax', 'getMaxId');
    Route::delete('/delprimmulti', 'destroyMultiple');
});
Route::controller(ProductsController::class)->group(function() {
    Route::get('/prod', 'index');
    Route::get('/prod/{id}', 'getId');
    Route::post('/postprod', 'store');
    Route::put('/updateprod/{id}', 'update');
    Route::delete('/deleteprod/{id}', 'destroy');
    Route::get('/prodmax', 'getMaxId');
    Route::delete('/delprodmulti', 'destroyMultiple');
    Route::get('/prodfeatured/{featured}', 'featuredId');
});
Route::controller(UnitController::class)->group(function() {
    Route::get('/unit', 'index');
    Route::get('/unit/{id}', 'getId');
    Route::post('/postunit', 'store');
    Route::put('/updateunit/{id}', 'update');
    Route::delete('/deleteunit/{id}', 'destroy');
    Route::get('/unitmax', 'getMaxId');
    Route::delete('/delunitmulti', 'destroyMultiple');
});
Route::controller(ProductBranchOfficeController::class)->group(function() {
    Route::get('/prbo', 'index');
    Route::get('/prbo/{id}', 'getId');
    Route::post('/postprbo', 'store');
    Route::put('/updateprbo/{id}', 'update');
    Route::delete('/deleteprbo/{id}', 'destroy');
    Route::get('/prbomax', 'getMaxId');
    Route::delete('/delprbomulti', 'destroyMultiple');
});
Route::controller(PurchaseOrdersController::class)->group(function() {
    Route::get('/puor', 'index');
    Route::get('/puor/{id}', 'getId');
    Route::post('/postpuor', 'store');
    Route::put('/updatepuor/{id}', 'update');
    Route::delete('/deletepuor/{id}', 'destroy');
    Route::get('/puormax', 'getMaxId');
    Route::delete('/delpuormulti', 'destroyMultiple');
});
Route::controller(QuotationsController::class)->group(function() {
    Route::get('/qt', 'index');
    Route::get('/qt/{id}', 'getId');
    Route::post('/postqt', 'store');
    Route::put('/updateqt/{id}', 'update');
    Route::delete('/deleteqt/{id}', 'destroy');
    Route::get('/qtmax', 'getMaxId');
    Route::delete('/delqtmulti', 'destroyMultiple');
});
Route::controller(QuoteDetailsController::class)->group(function() {
    Route::get('/qtd', 'index');
    Route::get('/qtd/{id}', 'getId');
    Route::post('/postqtd', 'store');
    Route::put('/updateqtd/{id}', 'update');
    Route::delete('/deleteqtd/{id}', 'destroy');
    Route::get('/qtdmax', 'getMaxId');
    Route::delete('/delqtdmulti', 'destroyMultiple');
});
Route::controller(RolesController::class)->group(function() {
    Route::get('/rol', 'index');
    Route::get('/rol/{id}', 'getId');
    Route::post('/postrol', 'store');
    Route::post('/updaterol/{id}', 'update');
    Route::delete('/deleterol/{id}', 'destroy');
    Route::get('/rolmax', 'getMaxId');
    Route::delete('/delrolmulti', 'destroyMultiple');
});
Route::controller(SerialNumberController::class)->group(function() {
    Route::get('/sn', 'index');
    Route::get('/sn/{id}', 'getId');
    Route::post('/postsn', 'store');
    Route::post('/updatesn/{id}', 'update');
    Route::delete('/deletesn/{id}', 'destroy');
    Route::get('/snmax', 'getMaxId');
    Route::delete('/delsnmulti', 'destroyMultiple');
});
Route::controller(SuppliersController::class)->group(function() {
    Route::get('/supp', 'index');
    Route::get('/supp/{id}', 'getId');
    Route::post('/postsupp', 'store');
    Route::put('/updatesupp/{id}', 'update');
    Route::delete('/deletesupp/{id}', 'destroy');
    Route::get('/suppmax', 'getMaxId');
    Route::delete('/delsuppmulti', 'destroyMultiple');
});
Route::controller(TaxesController::class)->group(function() {
    Route::get('/tax', 'index');
    Route::get('/tax/{id}', 'getId');
    Route::post('/posttax', 'store');
    Route::put('/updatetax/{id}', 'update');
    Route::delete('/deletetax/{id}', 'destroy');
    Route::get('/taxmax', 'getMaxId');
    Route::delete('/deltaxmulti', 'destroyMultiple');
});
Route::controller(TicketDetailsController::class)->group(function() {
    Route::get('/ticd', 'index');
    Route::get('/ticd/{id}', 'getId');
    Route::post('/postticd', 'store');
    Route::put('/updateticd/{id}', 'update');
    Route::delete('/deleteticd/{id}', 'destroy');
    Route::get('/ticdmax', 'getMaxId');
    Route::delete('/delticdmulti', 'destroyMultiple');
});
Route::controller(TicketsController::class)->group(function() {
    Route::get('/tic', 'index');
    Route::get('/tic/{id}', 'getId');
    Route::post('/posttic', 'store');
    Route::put('/updatetic/{id}', 'update');
    Route::delete('/deletetic/{id}', 'destroy');
    Route::get('/ticmax', 'getMaxId');
    Route::delete('/delticmulti', 'destroyMultiple');
});
Route::controller(UsersController::class)->group(function() {
    Route::get('/user', 'index');
    Route::get('/user/{id}', 'getId');
    Route::post('/postuser', 'store');
    Route::put('/updateuser/{id}', 'update');
    Route::delete('/deleteuser/{id}', 'destroy');
    Route::get('/usermax', 'getMaxId');
    Route::delete('/delusermulti', 'destroyMultiple');
});
Route::controller(WarehousesController::class)->group(function() {
    Route::get('/wh', 'index');
    Route::get('/wh/{id}', 'getId');
    Route::post('/postwh', 'store');
    Route::put('/updatewh/{id}', 'update');
    Route::delete('/deletewh/{id}', 'destroy');
    Route::get('/whmax', 'getMaxId');
    Route::delete('/delwhmulti', 'destroyMultiple');
});
Route::controller(WorkAreaController::class)->group(function() {
    Route::get('/wa', 'index');
    Route::get('/wa/{id}', 'getId');
    Route::post('/postwa', 'store');
    Route::put('/updatewa/{id}', 'update');
    Route::delete('/deletewa/{id}', 'destroy');
    Route::get('/wamax', 'getMaxId');
    Route::delete('/delwamulti', 'destroyMultiple');
});