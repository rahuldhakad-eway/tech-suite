<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Http\Requests\SuperAdmin\Software\StoreRequest;
use App\Http\Requests\SuperAdmin\Software\UpdateRequest;
use App\Models\SuperAdmin\GlobalPaymentGatewayCredentials;
use App\Models\SuperAdmin\SoftwareCategory;
use App\Models\SuperAdmin\Software;
use App\Helper\Files;

class SoftwareController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'superadmin.menu.softwareCategory';
        $this->global = global_setting();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->categories = SoftwareCategory::with('softwares')->get();

        // return $dataTable->render('super-admin.softwares.index', $this->data);
        return view('super-admin.softwares.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->pageTitle = __('superadmin.software-category.create');
        $this->categories = SoftwareCategory::all();

        if (request()->ajax()) {
            $html = view('super-admin.softwares.ajax.create', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'super-admin.softwares.ajax.create';

        return view('super-admin.softwares.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $software = $this->storeAndUpdate(new Software(), $request);

        return Reply::redirect(route('superadmin.softwares.index'), __('messages.softwareCategoryCreated'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->pageTitle = __('superadmin.packages.edit');
        $this->package = SoftwareCategory::findOrFail($id);

        if (request()->ajax()) {
            $html = view('super-admin.software-category.ajax.edit', $this->data)->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle]);
        }

        $this->view = 'super-admin.software-category.ajax.edit';

        return view('super-admin.packages.create', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param int $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $package = SoftwareCategory::find($id);
        $data = $request->validated();

        $package->update($data);

        return Reply::redirect(route('superadmin.software-category.index'), __('messages.updateSuccess'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $package = SoftwareCategory::findOrFail($id);

        $package->delete();

        return Reply::redirect(route('superadmin.software-category.index'), __('messages.deleteSuccess'));
    }


    public function storeAndUpdate(Software $software, $request)
    {
        info($request);
        $software->category_id = $request->category_id;
        $software->description = $request->description;

        if ($request->hasFile('logo')) {
            $software->logo = Files::upload($request->logo, 'software-logo');
        }

        $software->save();

        return $software;
    }

}
