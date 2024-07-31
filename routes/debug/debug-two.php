<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvatarsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\CompaniesController;
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
use App\Http\Controllers\MeasurementUnitController;
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
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CarriersController;
use App\Http\Controllers\JobPositionController;
use App\Http\Controllers\MobilitiesController;
use App\Http\Controllers\PurchaseOrdersController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SaleOrdersController;
use App\Http\Controllers\TaxesController;
use App\Http\Controllers\WarehousesController;
use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\WorkAreaController;
use Illuminate\Support\Facades\Route;

//auth
Route::prefix('auth')->group(function () {
  Route::post('login', [AuthController::class, 'login'])->name('login');
  Route::post('register', [AuthController::class, 'register']);
});

Route::group(['middleware' => 'auth:api'], function () {
  //auth
  Route::prefix('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
  });

  Route::controller(AnalyticsController::class)->group(function () {
    Route::get('/audience', 'audience');
  });
  Route::controller(AvatarsController::class)->group(function () {
    Route::get('/ava', 'index');
    Route::get('/ava/{id}', 'getId');
    Route::post('/postava', 'store');
    Route::put('/updateava/{id}', 'update');
    Route::delete('/deleteava/{id}', 'destroy');
    Route::get('/avamax', 'getMaxId');
    Route::delete('/delavamulti', 'destroyMultiple');
  });
  Route::controller(BranchOfficesController::class)->group(function () {
    Route::get('/bo', 'index');
    Route::get('/bo/{branch_offices}', 'show');
    Route::post('/postbo', 'store');
    Route::put('/updatebo/{branch_offices}', 'update');
    Route::delete('/deletebo/{branch_offices}', 'destroy');
    Route::delete('/delbomulti', 'destroyMultiple');
  });
  Route::controller(BranchOfficeStaffController::class)->group(function () {
    Route::get('/bos', 'index');
    Route::get('/bos/{id}', 'getId');
    Route::post('/postbos', 'store');
    Route::put('/updatebos/{id}', 'update');
    Route::delete('/deletebos/{id}', 'destroy');
    Route::get('/bosmax', 'getMaxId');
    Route::delete('/delbosmulti', 'destroyMultiple');
  });
  Route::controller(BrandsController::class)->group(function () {
    Route::get('/brands', 'index');
    Route::get('/brands/{brands}', 'show');
    Route::post('/post_brands', 'store');
    Route::put('/update_brands/{brands}', 'update');
    Route::delete('/delete_brands/{brands}', 'destroy');
    Route::delete('/delbrandsmulti', 'destroyMultiple');
  });
  Route::controller(CategoriesController::class)->group(function () {
    Route::get('/cate', 'index');
    Route::get('/cate/{categories}', 'show');
    Route::post('/postcate', 'store');
    Route::put('/updatecate/{categories}', 'update');
    Route::delete('/deletecate/{categories}', 'destroy');
    Route::delete('/delcatemulti', 'destroyMultiple');
  });
  Route::controller(CarriersController::class)->group(function () {
    Route::get('/carr', 'index');
    Route::get('/carr/{id}', 'getId');
    Route::post('/postcarr', 'store');
    Route::put('/updatecarr/{id}', 'update');
    Route::delete('/deletecarr/{id}', 'destroy');
    Route::get('/carrmax', 'getMaxId');
    Route::delete('/delcarrmulti', 'destroyMultiple');
  });
  Route::controller(CustomersController::class)->group(function () {
    Route::get('/customers', 'index');
    Route::get('/customers/{customers}', 'show');
    Route::post('/postcustomers', 'store');
    Route::put('/updatecustomers/{customers}', 'update');
    Route::delete('/deletecustomers/{customers}', 'destroy');
    Route::delete('/delcustomersmulti', 'destroyMultiple');
  });
  Route::controller(CompaniesController::class)->group(function () {
    Route::get('/companies', 'index');
    Route::get('/companies/{companies}', 'show');
    Route::post('/postcompanies', 'store');
    Route::put('/updatecompanies/{companies}', 'update');
    Route::delete('/deletecompanies/{companies}', 'destroy');
    Route::delete('/delcompaniesmulti', 'destroyMultiple');
  });
  Route::controller(CurrenciesController::class)->group(function () {
    Route::get('/currencies', 'index');
    Route::get('/currencies/{currency}', 'show');
    Route::post('/post_currencies', 'store');
    Route::put('/update_currencies/{currency}', 'update');
    Route::delete('/delete_currencies/{currency}', 'destroy');
    Route::delete('/delcurmulti', 'destroyMultiple');
  });
  Route::controller(UbigeoController::class)->group(function () {
    Route::get('departments', 'departments');
    Route::get('provinces/{department_id}', 'provinces');
    Route::get('districts/{province_id}', 'districts');
  });
  Route::controller(DocumentContentController::class)->group(function () {
    Route::get('/doco', 'index');
    Route::get('/doco/{id}', 'getId');
    Route::post('/postdoco', 'store');
    Route::put('/updatedoco/{id}', 'update');
    Route::delete('/deletedoco/{id}', 'destroy');
    Route::get('/docomax', 'getMaxId');
    Route::delete('/deldocomulti', 'destroyMultiple');
  });
  Route::controller(DocumentStatusController::class)->group(function () {
    Route::get('/dost', 'index');
    Route::get('/dost/{id}', 'getId');
    Route::post('/postdost', 'store');
    Route::put('/updatedost/{id}', 'update');
    Route::delete('/deletedost/{id}', 'destroy');
    Route::get('/dostmax', 'getMaxId');
    Route::delete('/deldostmulti', 'destroyMultiple');
  });
  Route::controller(DocumentTypesController::class)->group(function () {
    Route::get('/doct', 'index');
    Route::get('/doct/{id}', 'getId');
    Route::post('/postdoct', 'store');
    Route::put('/updatedoct/{id}', 'update');
    Route::delete('/deletedoct/{id}', 'destroy');
    Route::get('/doctmax', 'getMaxId');
    Route::delete('/deldoctmulti', 'destroyMultiple');
  });
  Route::controller(EmployeeController::class)->group(function () {
    Route::get('/emp', 'index');
    Route::get('/emp/{employee}', 'show');
    Route::post('/postemp', 'store');
    Route::put('/updateemp/{employee}', 'update');
    Route::delete('/deleteemp/{employee}', 'destroy');
    Route::delete('/delempmulti', 'destroyMultiple');
  });
  Route::controller(InvoiceDetailsController::class)->group(function () {
    Route::get('/invd', 'index');
    Route::get('/invd/{id}', 'getId');
    Route::post('/postinvd', 'store');
    Route::put('/updateinvd/{id}', 'update');
    Route::delete('/deleteinvd/{id}', 'destroy');
    Route::get('/invdmax', 'getMaxId');
    Route::delete('/delinvdmulti', 'destroyMultiple');
  });
  Route::controller(InvoicesController::class)->group(function () {
    Route::get('/inv', 'index');
    Route::get('/inv/{id}', 'getId');
    Route::post('/postinv', 'store');
    Route::put('/updateinv/{id}', 'update');
    Route::delete('/deleteinv/{id}', 'destroy');
    Route::get('/invmax', 'getMaxId');
    Route::delete('/delinvmulti', 'destroyMultiple');
  });
  Route::controller(JobPositionController::class)->group(function () {
    Route::get('/jp', 'index');
    Route::get('/jp/{id}', 'getId');
    Route::post('/postjp', 'store');
    Route::put('/updatejp/{id}', 'update');
    Route::delete('/deletejp/{id}', 'destroy');
    Route::get('/jpmax', 'getMaxId');
    Route::delete('/deljpmulti', 'destroyMultiple');
  });
  Route::controller(MobilitiesController::class)->group(function () {
    Route::get('/mob', 'index');
    Route::get('/mob/{id}', 'getId');
    Route::post('/postmob', 'store');
    Route::put('/updatemob/{id}', 'update');
    Route::delete('/deletemob/{id}', 'destroy');
    Route::get('/mobmax', 'getMaxId');
    Route::delete('/delmobmulti', 'destroyMultiple');
  });
  Route::controller(NamesMenuController::class)->group(function () {
    Route::get('/menu', 'index');
    Route::get('/menu/{id}', 'getId');
    Route::post('/postmenu', 'store');
    Route::put('/updatemenu/{id}', 'update');
    Route::delete('/deletemenu/{id}', 'destroy');
    Route::get('/menumax', 'getMaxId');
    Route::delete('/delmenumulti', 'destroyMultiple');
  });
  Route::controller(PaymentMethodsController::class)->group(function () {
    Route::get('/paym', 'index');
    Route::get('/paym/{id}', 'getId');
    Route::post('/postpaym', 'store');
    Route::put('/updatepaym/{id}', 'update');
    Route::delete('/deletepaym/{id}', 'destroy');
    Route::get('/paymmax', 'getMaxId');
    Route::delete('/delpaymmulti', 'destroyMultiple');
  });
  Route::controller(PaymentTypesController::class)->group(function () {
    Route::get('/payt', 'index');
    Route::get('/payt/{id}', 'getId');
    Route::post('/postpayt', 'store');
    Route::put('/updatepayt/{id}', 'update');
    Route::delete('/deletepayt/{id}', 'destroy');
    Route::get('/paytmax', 'getMaxId');
    Route::delete('/delpaytmulti', 'destroyMultiple');
  });
  Route::controller(PermissionsController::class)->group(function () {
    Route::get('/perm', 'index');
    Route::get('/perm/{id}', 'getId');
    Route::post('/postperm', 'store');
    Route::put('/updateperm/{id}', 'update');
    Route::delete('/deleteperm/{id}', 'destroy');
    Route::get('/permmax', 'getMaxId');
    Route::delete('/delpermmulti', 'destroyMultiple');
  });
  Route::controller(ProductCategoryController::class)->group(function () {
    Route::get('/prca', 'index');
    Route::get('/prca/{id}', 'getId');
    Route::post('/postprca', 'store');
    Route::put('/updateprca/{id}', 'update');
    Route::delete('/deleteprca/{id}', 'destroy');
    Route::get('/prcamax', 'getMaxId');
    Route::delete('/delprcamulti', 'destroyMultiple');
  });
  Route::controller(ProductImagesController::class)->group(function () {
    Route::get('/prim', 'index');
    Route::post('/postprim', 'store');
    Route::post('/updateprim/{id}', 'update');
    Route::delete('/deleteprim/{id}', 'destroy');
    Route::get('/primmax', 'getMaxId');
    Route::delete('/delprimmulti', 'destroyMultiple');
  });
  Route::controller(ProductsController::class)->group(function () {
    Route::get('/prod', 'index');
    Route::get('/prod/{product}', 'show');
    Route::post('/postprod', 'store');
    Route::post('/updateprod', 'update');
    Route::delete('/deleteprod/{id}', 'destroy');
    Route::get('/prodmax', 'getMaxId');
    Route::delete('/delprodmulti', 'destroyMultiple');
    Route::get('/prodfeatured/{featured}', 'featuredId');
  });
  Route::controller(MeasurementUnitController::class)->group(function () {
    Route::get('/measurement_unit', 'index');
    Route::get('/measurement_unit/{measurement_unit}', 'show');
    Route::post('/post_measurement_unit', 'store');
    Route::put('/update_measurement_unit/{measurement_unit}', 'update');
    Route::delete('/delete_measurement_unit/{measurement_unit}', 'destroy');
    Route::delete('/delunitmulti', 'destroyMultiple');
  });
  Route::controller(ProductBranchOfficeController::class)->group(function () {
    Route::get('/prbo', 'index');
    Route::get('/prbo/{id}', 'getId');
    Route::post('/postprbo', 'store');
    Route::put('/updateprbo/{id}', 'update');
    Route::delete('/deleteprbo/{id}', 'destroy');
    Route::get('/prbomax', 'getMaxId');
    Route::delete('/delprbomulti', 'destroyMultiple');
  });
  Route::controller(PurchaseOrdersController::class)->group(function () {
    Route::get('/puor', 'index');
    Route::get('/puor/{purchase_order}', 'show');
    Route::post('/postpuor', 'store');
    Route::put('/updatepuor/{purchase_order}', 'update');
    Route::delete('/deletepuor/{purchase_order}', 'destroy');
    Route::get('/puormax', 'getMaxId');
    Route::delete('/delpuormulti', 'destroyMultiple');
    Route::get('/excelpuor', 'exportExcel');
    Route::get('/pdfpuor', 'exportPDF');
    Route::get('/pdfpuorid/{id}', 'exportPDFId');
  });
  Route::controller(QuotationsController::class)->group(function () {
    Route::get('/qt', 'index');
    Route::get('/qt/{id}', 'getId');
    Route::post('/postqt', 'store');
    Route::put('/updateqt/{id}', 'update');
    Route::delete('/deleteqt/{id}', 'destroy');
    Route::get('/qtmax', 'getMaxId');
    Route::delete('/delqtmulti', 'destroyMultiple');
  });
  Route::controller(QuoteDetailsController::class)->group(function () {
    Route::get('/qtd', 'index');
    Route::get('/qtd/{id}', 'getId');
    Route::post('/postqtd', 'store');
    Route::put('/updateqtd/{id}', 'update');
    Route::delete('/deleteqtd/{id}', 'destroy');
    Route::get('/qtdmax', 'getMaxId');
    Route::delete('/delqtdmulti', 'destroyMultiple');
  });
  Route::controller(ReportsController::class)->group(function () {
    Route::get('/stock_report', 'stock_report');
    Route::get('/stock_report/{id}', 'stock_report_id');
  });
  Route::controller(RolesController::class)->group(function () {
    Route::get('/rol', 'index');
    Route::get('/rol/{id}', 'getId');
    Route::post('/postrol', 'store');
    Route::put('/updaterol/{id}', 'update');
    Route::delete('/deleterol/{id}', 'destroy');
    Route::get('/rolmax', 'getMaxId');
    Route::delete('/delrolmulti', 'destroyMultiple');
  });
  Route::controller(SaleOrdersController::class)->group(function () {
    Route::get('/saor', 'index');
    Route::get('/saor/{id}', 'getId');
    Route::post('/postsaor', 'store');
    Route::put('/updatesaor/{id}', 'update');
    Route::delete('/deletesaor/{id}', 'destroy');
    Route::get('/saormax', 'getMaxId');
    Route::delete('/delsaormulti', 'destroyMultiple');
    Route::get('/excelsaor', 'exportExcel');
    Route::get('/pdfsaor', 'exportPDF');
    Route::get('/pdfsaorid/{id}', 'exportPDFId');
  });
  Route::controller(SerialNumberController::class)->group(function () {
    Route::get('/sn', 'index');
    Route::get('/sn/{id}', 'getId');
    Route::post('/postsn', 'store');
    Route::put('/updatesn/{id}', 'update');
    Route::delete('/deletesn/{id}', 'destroy');
    Route::get('/snmax', 'getMaxId');
    Route::delete('/delsnmulti', 'destroyMultiple');
  });
  Route::controller(SuppliersController::class)->group(function () {
    Route::get('/supp', 'index');
    Route::get('/supp/{suppliers}', 'show');
    Route::post('/postsupp', 'store');
    Route::put('/updatesupp/{suppliers}', 'update');
    Route::delete('/deletesupp/{suppliers}', 'destroy');
    Route::delete('/delsuppmulti', 'destroyMultiple');
  });
  Route::controller(TaxesController::class)->group(function () {
    Route::get('/tax', 'index');
    Route::get('/tax/{id}', 'getId');
    Route::post('/posttax', 'store');
    Route::put('/updatetax/{id}', 'update');
    Route::delete('/deletetax/{id}', 'destroy');
    Route::get('/taxmax', 'getMaxId');
    Route::delete('/deltaxmulti', 'destroyMultiple');
  });
  Route::controller(TicketDetailsController::class)->group(function () {
    Route::get('/ticd', 'index');
    Route::get('/ticd/{id}', 'getId');
    Route::post('/postticd', 'store');
    Route::put('/updateticd/{id}', 'update');
    Route::delete('/deleteticd/{id}', 'destroy');
    Route::get('/ticdmax', 'getMaxId');
    Route::delete('/delticdmulti', 'destroyMultiple');
  });
  Route::controller(TicketsController::class)->group(function () {
    Route::get('/tic', 'index');
    Route::get('/tic/{id}', 'getId');
    Route::post('/posttic', 'store');
    Route::put('/updatetic/{id}', 'update');
    Route::delete('/deletetic/{id}', 'destroy');
    Route::get('/ticmax', 'getMaxId');
    Route::delete('/delticmulti', 'destroyMultiple');
  });
  Route::controller(UsersController::class)->group(function () {
    Route::get('/user', 'index');
    Route::get('/user/{user}', 'show');
    Route::post('/postuser', 'store');
    Route::put('/updateuser/{user}', 'update');
    Route::delete('/deleteuser/{user}', 'destroy');
    Route::delete('/delusermulti', 'destroyMultiple');
  });
  Route::controller(WarehousesController::class)->group(function () {
    Route::get('/warehouse', 'index');
    Route::get('/warehouse/{warehouse}', 'show');
    Route::post('/postwarehouse', 'store');
    Route::put('/updatewarehouse/{warehouse}', 'update');
    Route::delete('/deletewarehouse/{warehouse}', 'destroy');
    Route::delete('/delwarehousemulti', 'destroyMultiple');
  });
  Route::controller(WorkAreaController::class)->group(function () {
    Route::get('/wa', 'index');
    Route::get('/wa/{id}', 'getId');
    Route::post('/postwa', 'store');
    Route::put('/updatewa/{id}', 'update');
    Route::delete('/deletewa/{id}', 'destroy');
    Route::get('/wamax', 'getMaxId');
    Route::delete('/delwamulti', 'destroyMultiple');
  });

});