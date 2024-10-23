@extends('admin/layout')
@section('container')
    {{session('message')}}                          
    <h1 class="mb10">Suppliers</h1>
    <a href="supplier/manage_supplier">
        <button type="button" class="btn btn-success">
            Add Suppliers
        </button>
    </a>
    <div class="row m-t-30">
        <div class="col-md-12">
            <!-- DATA TABLE-->
            <div class="table-responsive m-b-40">
                <table class="table table-borderless table-data3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Supplier Name</th>
                            <th>Address</th>
                            <th>Status</th>                       
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $list)
                        <tr>
                            <td>{{$list->id}}</td>
                            <td>{{$list->name}}</td>
                            <td>{{$list->address}}</td> 
                            <td>{{$list->status}}</td>           
                            <td>
                                <a href="{{url('admin/supplier/delete/')}}/{{$list->id}}"><button type="button" class="btn btn-danger">Delete</button></a>
                                <a href="{{url('admin/supplier/manage_supplier/')}}/{{$list->id}}"><button type="button" class="btn btn-success">Edit</button></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- END DATA TABLE-->
        </div>
    </div>                        
@endsection
