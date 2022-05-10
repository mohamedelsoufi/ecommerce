<?php $page = "maincategoriesShow"?>
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
                                  <li class="breadcrumb-item active">Main categories
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
                                      <th>iamge</th>
                                      <th>name</th>
                                      <th>status</th>
                                      <th>controller</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($main_categories as $main_category)
                                      <tr>
                                          <td>{{$main_category->id}}</td>
                                          <td><img src="{{$main_category->getImage()}}" style="width: 70px;"></td>
                                          <td>{{$main_category->translate('en')->name}}</td>
                                          <td>{{$main_category->getStatus()}}</td>
                                          <td>
                                            <a href="{{url('/admin/main_categories/edit/' . $main_category->id)}}" class="btn btn-purple btn-min-width box-shadow-5 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">edit</a>
                                            @if ($main_category->status == 1)
                                                <a href="{{url('/admin/main_categories/active/' . $main_category->id)}}" class="btn btn-warning btn-min-width box-shadow-5 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">{{$main_category->getChangStatus()}}</a>
                                            @else
                                                <a href="{{url('/admin/main_categories/active/' . $main_category->id)}}" class="btn btn-info btn-min-width box-shadow-3 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">{{$main_category->getChangStatus()}}</a>
                                            @endif
                                            <a href="{{url('/admin/main_categories/delete/' . $main_category->id)}}" class="btn btn-danger btn-min-width box-shadow-5 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">delete</a>
                                        </td>
                                      </tr>
                                  @endforeach
                                  @if (count($main_categories) == 0)
                                      <tr>
                                          <td colspan="5">
                                              <div class="alert alert-secondary mb-2" role="alert">there are no category</div>
                                          </td>
                                      </tr>
                                  @endif
                              </tbody>
                          </table>
                          {!! $main_categories->links() !!}
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection