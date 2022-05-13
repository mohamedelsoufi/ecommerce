<?php $page = "productsShow"?>
@extends('layouts.admin')

@section('content')
  <!-- ////////////////////////////////////////////////////////////////////////////-->
  <div class="row">
      <div class="col-lg-12">
          <div class="container">
              <div class="content-header row">
                  <div class="content-header-left col-md-6 col-12 mb-2">
                      <div class="row breadcrumbs-top">
                          <div class="breadcrumb-wrapper col-12">
                              <ol class="breadcrumb" style="margin-top: 25px;">
                                  <li class="breadcrumb-item"><a href="{{url('admin')}}">dashbourd</a>
                                  </li>
                                  <li class="breadcrumb-item active">products
                                  </li>
                              </ol>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          @include('admin.alerts.error')
          @include('admin.alerts.success')
          <div class="card">
              <div class="card-header">
              <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
              <div class="heading-elements">
                  <ul class="list-inline mb-0">
                  <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                  <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                  <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                  <li><a data-action="close"><i class="ft-x"></i></a></li>
                  </ul>
              </div>
              </div>
              <div class="card-content collapse show">
              <div class="card-body card-dashboard ">
              </div>
                  <div class="table-responsive"> 
                      <div class="container">
                          <table class="table mb-0">
                              <thead>
                                  <tr>
                                      <th>id</th>
                                      <th>image</th>
                                      <th>Name</th>
                                      <th>category</th>
                                      <th>vender</th>
                                      <th>status</th>
                                      <th>gender</th>
                                      <th>controller</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($products as $product)
                                      <tr>
                                          <td>{{$product->id}}</td>
                                          <td><img src="{{$product->getImage()}}" style="width: 70px;"></td>
                                          <td>{{$product->name}}</td>
                                          <td>{{$product->Sub_category->Main_categories->name}} -> {{$product->Sub_category->name}}</td>
                                          <td>{{$product->Vendor->fullName}}</td>
                                          <td>{{$product->getStatus()}}</td>
                                          <td>{{$product->getGender()}}</td>
                                          <td>
                                            <a href="{{url('/admin/products/delete/' . $product->id)}}" class="btn btn-danger btn-min-width box-shadow-5 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">delete</a>
                                              @if ($product->status == 1)
                                                <a href="{{url('/admin/products/active/' . $product->id)}}" class="btn btn-warning btn-min-width box-shadow-5 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">{{$product->getChangStatus()}}</a>
                                              @else
                                               <a href="{{url('/admin/products/active/' . $product->id)}}" class="btn btn-info btn-min-width box-shadow-3 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">{{$product->getChangStatus()}}</a>
                                              @endif
                                          </td>
                                      </tr>
                                  @endforeach
                                  @if (count($products) == 0)
                                      <tr>
                                          <td colspan="5">
                                              <div class="alert alert-secondary mb-2" role="alert">there are no products</div>
                                          </td>
                                      </tr>
                                  @endif
                              </tbody>
                          </table>
                          {!! $products->links() !!}
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection