<?php $page = "promoCodesShow"?>
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
                                  <li class="breadcrumb-item active">promoCodes
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
                                      <th>status</th>
                                      <th>code</th>
                                      <th>discound</th>
                                      <th>expire_date</th>
                                      <th>controller</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($promo_codes as $promo_code)
                                      <tr>
                                          <td>{{$promo_code->id}}</td>
                                          <td>{{$promo_code->getStatus()}}</td>
                                          <td>{{$promo_code->code}}</td>
                                          <td>{{$promo_code->discound}} %</td>
                                          <td>{{$promo_code->expire_date}}</td>
                                          <td>
                                            @if ($promo_code->expire_date > date("Y-m-d H:i:s"))
                                                <a href="{{url('/admin/promoCodes/expiry/' . $promo_code->id)}}" class="btn btn-danger btn-min-width box-shadow-5 mr-1 mb-1" style="min-width: 6.5rem; margin-right: 8px !important;">Expiry</a>
                                            @endif
                                          </td>
                                      </tr>
                                  @endforeach
                                  @if (count($promo_codes) == 0)
                                      <tr>
                                          <td colspan="5">
                                              <div class="alert alert-secondary mb-2" role="alert">there are no promo Codes</div>
                                          </td>
                                      </tr>
                                  @endif
                              </tbody>
                          </table>
                          {!! $promo_codes->links() !!}
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection