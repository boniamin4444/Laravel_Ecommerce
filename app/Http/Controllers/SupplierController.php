<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.showspplier', compact('suppliers'));
    }

    public function manage_supplier(Request $request, $id = '')
    {
        if ($id > 0) {
            $supplier = Supplier::find($id);
            if ($supplier) {
                $result['name'] = $supplier->name;
                $result['address'] = $supplier->address;
                $result['status'] = $supplier->status;
                $result['id'] = $supplier->id;
            } else {
                return redirect('admin/showspplier')->with('error', 'Supplier not found.');
            }
        } else {
            $result['name'] = '';
            $result['address'] = '';
            $result['status'] = '';
            $result['id'] = 0;
        }
        return view('admin.manage_suppliers', $result);
    }

    public function manage_supplier_process(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'status' => 'required',
        ]);

        // Create or update supplier
        $model = $request->post('id') > 0 ? Supplier::find($request->post('id')) : new Supplier();
        $msg = $model->exists ? "Supplier Updated" : "Supplier Inserted";

        $model->name = $request->post('name');
        $model->address = $request->post('address'); // Fixed from name to address
        $model->status = $request->post('status'); // Fixed from name to status
        $model->save();

        $request->session()->flash('message', $msg);
        return redirect('admin/showspplier');
    }

    public function delete(Request $request, $id)
    {
        $model = Supplier::find($id);
        if ($model) {
            $model->delete();
            $request->session()->flash('message', 'Supplier Deleted');
        } else {
            $request->session()->flash('error', 'Supplier not found.');
        }
        return redirect('admin/showspplier');
    }
}
