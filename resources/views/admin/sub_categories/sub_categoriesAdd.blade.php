<?php $page = "subcategoriesAdd"?>
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
                                  <li class="breadcrumb-item active">Add sub categories
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
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <form class="form" action="{{url('admin/sub_categories/add')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <h4 class="form-section"><i class="ft-user"></i>Add</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="projectinput1">category image</label>
                                                <br>
                                                <input type="file" name="image" multiple value="{{ old('image') }}">
                                                @error('image')
                                                    <span style="color: red;">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="projectinput1">main category</label>
                                                <br>
                                                <select class="form-control" name="main_category_id">
                                                    @foreach ($main_categories as $main_category)
                                                        <option value="{{$main_category->id}}" @if ($main_category->id == old('main_category_id')) selected @endif>{{$main_category->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @foreach (app('langs') as $key => $lang)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="projectinput1">name in {{$lang}}</label>
                                                    <br>
                                                    <input type="text" name="sub_cate[{{$key}}][name]"value="{{ old('sub_cate.' . $key . '.name') }}" class="form-control" autocomplete="off">
                                                    @error('sub_cate.' . $key . '.name')
                                                        <span style="color: red;">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary" style="margin-left: 13px;">
                                            <i class="la la-check-square-o"></i>add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </div>
  <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection