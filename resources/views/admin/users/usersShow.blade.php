<?php $page = "usersShow"?>
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
                                  <li class="breadcrumb-item active">users
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
                                      <th>fullName</th>
                                      <th>email</th>
                                      <th>phone</th>
                                      <th>gender</th>
                                      <th>status</th>
                                      <th>controller</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($users as $user)
                                      <tr>
                                          <td>{{$user->id}}</td>
                                          <td>{{$user->fullName}}</td>
                                          <td>{{$user->email}}</td>
                                          <td>{{$user->phone}}</td>
                                          <td>{{$user->getGender()}}</td>
                                          <td>{{$user->getStatus()}}</td>
                                          <td>
                                              @if ($user->status == 1)
                                                <a href="{{url('/admin/users/block/' . $user->id)}}" class="btn btn-danger btn-min-width box-shadow-5 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">{{$user->getChangStatus()}}</a>
                                              @else
                                               <a href="{{url('/admin/users/block/' . $user->id)}}" class="btn btn-info btn-min-width box-shadow-3 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">{{$user->getChangStatus()}}</a>
                                              @endif
                                          </td>
                                      </tr>
                                  @endforeach
                                  @if (count($users) == 0)
                                      <tr>
                                          <td colspan="5">
                                              <div class="alert alert-secondary mb-2" role="alert">there are no users</div>
                                          </td>
                                      </tr>
                                  @endif
                              </tbody>
                          </table>
                          {!! $users->links() !!}
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection