@extends('layouts.master')
@section('css')
@endsection
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">Parameters</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Products</span>
						</div>
					</div>
					<div class="d-flex my-xl-auto right-content">
						
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row row-sm">
					<div class="col-xl-12">
						<div class="card">
						<div class="col-sm-6 col-md-3 mg-t-10 mg-sm-t-0"><br>
						<button type="button" class="btn btn-secondary-gradient btn-block" data-toggle="modal" data-target="#exampleModal">
    add new product <i class="typcn typcn-edit"></i>
</button>
				@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
</div>
@endif 
@if (session()->has('Add'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('Add') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if (session()->has('edit'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('edit') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
	</div>

								<div class="card-body">
								<div class="table-responsive">
									<table class="table text-md-nowrap" id="example1">
										<thead>
											<tr>
											<th class="wd-15p border-bottom-0">#</th>

												<th class="wd-15p border-bottom-0">Products name</th>
												<th class="wd-15p border-bottom-0">Class name</th>
												<th class="wd-20p border-bottom-0">description</th>
												<th class="wd-15p border-bottom-0">Operations</th>
										
											</tr>
										</thead>
										<tbody>
											
												<?php $i=0 ?>
												@foreach($products as $x)
    <!-- Other product rows -->
    <tr>
        <td>{{$i}}</td>
        <td>{{$x->Product_name}}</td>
        <?php foreach($sections as $u){
            if($x->section_id==$u->id){
                $k=$u->section_name;
            }
        } ?>
        <td>{{$k}}</td>
        <td>{{$x->description}}</td>
        <td>
            <!-- Update Button -->
            <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
               data-toggle="modal" href="#editModal{{ $x->id }}" title="Update"><i class="las la-pen"></i></a>
            <!-- Delete Button -->
            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
               data-toggle="modal" href="#deleteModal{{ $x->id }}" title="Delete"><i class="las la-trash"></i></a>
        </td>
        <?php $i++ ?>
    </tr>

    <!-- Update Modal -->
    <div class="modal fade" id="editModal{{ $x->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $x->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $x->id }}">Update Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('products.update', ['product' => $x->id]) }}" method="post" autocomplete="off">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="Product_name" class="col-form-label">Product Name</label>
                            <input class="form-control" name="Product_name" id="Product_name{{ $x->id }}" type="text" value="{{ $x->Product_name }}">
                        </div>
                        <label class="my-1 mr-2" for="section_id">Section</label>
                        <select name="section_id" id="section_id{{ $x->id }}" class="custom-select my-1 mr-sm-2" required>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" @if($x->section_id == $section->id) selected @endif>{{ $section->section_name }}</option>
                            @endforeach
                        </select>
                        <div class="form-group">
                            <label for="description" class="col-form-label">Description</label>
                            <textarea class="form-control" id="description{{ $x->id }}" name="description">{{ $x->description }}</textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal{{ $x->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $x->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="deleteModalLabel{{ $x->id }}">Delete Product</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('products.destroy', ['product' => $x->id]) }}" method="post" autocomplete="off">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Are you sure you want to delete this product?</p><br>
                        <input type="hidden" name="id" id="id{{ $x->id }}" value="{{ $x->id }}">
                        <input class="form-control" name="Product_name" id="delete_Product_name{{ $x->id }}" type="text" value="{{ $x->Product_name }}" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
											
											</tr>
												
											
												
											
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!--/div-->

					<!--div-->
			
					<!--/div-->

					<!--div-->
				
					<!--/div-->

					<!--div-->
				
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
		
        <!-- add -->
        
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('products.store') }}" method="post">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">product name</label>
                                <input type="text" class="form-control" id="Product_name" name="Product_name" >
                            </div>

                            <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Class</label>
                            <select name="section_id" id="section_id" class="form-control" >
                                <option value="" selected disabled> --choose class--</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                                @endforeach
                            </select>

                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
			<!-- Container closed -->
      <!-- Edit Modal -->


		<!-- main-content closed -->
@endsection


@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>


@endsection