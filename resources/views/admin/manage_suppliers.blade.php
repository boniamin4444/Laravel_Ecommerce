@extends('admin/layout')

@section('container')
    <h1 class="mb10">Manage Supplier</h1>
    <a href="{{ url('admin/showspplier') }}">
        <button type="button" class="btn btn-success">Back</button>
    </a>
    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        <form action="{{ route('supplier.manage_supplier_process') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="control-label mb-1">Supplier Name</label>
                                    <input id="name" value="{{ old('name', $name) }}" name="name" type="text" class="form-control" required>
                                    @error('name')
                                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address" class="control-label mb-1">Supplier Address</label>
                                    <input id="address" value="{{ old('address', $address) }}" name="address" type="text" class="form-control" required>
                                    @error('address')
                                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Status</label><br>
                                    <label><input type="radio" name="status" value="active" required> Active</label>
                                    <label><input type="radio" name="status" value="inactive"> Inactive</label>
                                    @error('status')
                                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">Submit</button>
                                </div>
                                <input type="hidden" name="id" value="{{ $id }}"/>
                            </form>
                        </div>
                    </div>
                </div>            
            </div>                        
        </div>
    </div>                        
@endsection
