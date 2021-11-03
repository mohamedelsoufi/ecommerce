<?php $page = "ordersShow"?>
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
                                  <li class="breadcrumb-item active">orders
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
                                      <th>product</th>
                                      <th>quantity</th>
                                      <th>color</th>
                                      <th>size</th>
                                      <th>product price</th>
                                      <th>product discound</th>
                                      <th>product price with discound</th>
                                      <th>total</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($orders_details as $order_details)
                                    <tr>
                                        <td>{{$order_details->id}}</td>
                                        <td>{{$order_details->Product->name}}</td>
                                        <td>{{$order_details->quantity}}</td>
                                        <td>{{$order_details->color}}</td>
                                        <td>{{$order_details->size}}</td>
                                        <td>{{$order_details->product_price}} $</td>
                                        <td>{{$order_details->product_discound}} %</td>
                                        <td>{{$order_details->product_total_price}} $</td>
                                        <td>{{$order_details->product_total_price * $order_details->quantity}} $</td>
                                    </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection