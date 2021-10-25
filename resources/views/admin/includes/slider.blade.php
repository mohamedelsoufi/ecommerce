<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
      <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        
        <li class=" nav-item"><a href="{{url('/admin/users')}}"><i class="la la-home"></i><span class="menu-title" data-i18n="nav.dash.main">users</span><span class="badge badge badge-info badge-pill float-right mr-2">{{App\Models\User::count()}}</span></a>
          <ul class="menu-content">
            <li class="@if ($page == 'usersShow') active @endif">
              <a class="menu-item" href="{{url('/admin/users')}}" data-i18n="nav.dash.ecommerce">all users</a>
            </li>
          </ul>
        </li>

        <li class=" nav-item"><a href="{{url('/admin/venders')}}"><i class="la la-home"></i><span class="menu-title" data-i18n="nav.dash.main">venders</span><span class="badge badge badge-info badge-pill float-right mr-2">{{App\Models\Vender::count()}}</span></a>
          <ul class="menu-content">
            <li class="@if ($page == 'vendersShow') active @endif">
              <a class="menu-item" href="{{url('/admin/venders')}}" data-i18n="nav.dash.ecommerce">all venders</a>
            </li>
          </ul>
        </li>

        <li class=" nav-item"><a href="{{url('/admin/products')}}"><i class="la la-home"></i><span class="menu-title" data-i18n="nav.dash.main">products</span><span class="badge badge badge-info badge-pill float-right mr-2">{{App\Models\Product::where('status', '!=', -1)->count()}}</span></a>
          <ul class="menu-content">
            <li class="@if ($page == 'productsShow') active @endif">
              <a class="menu-item" href="{{url('/admin/products')}}" data-i18n="nav.dash.ecommerce">all products</a>
            </li>
          </ul>
        </li>

        <li class=" nav-item"><a href="{{url('/admin/comments')}}"><i class="la la-home"></i><span class="menu-title" data-i18n="nav.dash.main">comments</span><span class="badge badge badge-info badge-pill float-right mr-2">{{App\Models\Comment::count()}}</span></a>
          <ul class="menu-content">
            <li class="@if ($page == 'commentsShow') active @endif">
              <a class="menu-item" href="{{url('/admin/comments')}}" data-i18n="nav.dash.ecommerce">all products</a>
            </li>
          </ul>
        </li>

        <li class=" nav-item"><a href="{{url('/admin/main_categories')}}"><i class="la la-home"></i><span class="menu-title" data-i18n="nav.dash.main">main categories</span><span class="badge badge badge-info badge-pill float-right mr-2">{{App\Models\Main_category::where('status', '!=',-1)->where('parent', 0)->count()}}</span></a>
          <ul class="menu-content">
            <li class="@if ($page == 'maincategoriesShow') active @endif">
              <a class="menu-item" href="{{url('/admin/main_categories')}}" data-i18n="nav.dash.ecommerce">main categories</a>
            </li>

            <li class="@if ($page == 'maincategoriesAdd') active @endif">
              <a class="menu-item" href="{{url('/admin/main_categories/add')}}" data-i18n="nav.dash.ecommerce">add main categories</a>
            </li>
          </ul>
        </li>

        <li class=" nav-item"><a href="{{url('/admin/sub_categories')}}"><i class="la la-home"></i><span class="menu-title" data-i18n="nav.dash.main">Sub categories</span><span class="badge badge badge-info badge-pill float-right mr-2">{{App\Models\Sub_category::where('status', '!=',-1)->where('parent', 0)->count()}}</span></a>
          <ul class="menu-content">
            <li class="@if ($page == 'subcategoriesShow') active @endif">
              <a class="menu-item" href="{{url('/admin/sub_categories')}}" data-i18n="nav.dash.ecommerce">sub categories</a>
            </li>

            <li class="@if ($page == 'subcategoriesAdd') active @endif">
              <a class="menu-item" href="{{url('/admin/sub_categories/add')}}" data-i18n="nav.dash.ecommerce">add sub categories</a>
            </li>
          </ul>
        </li>

        <li class=" nav-item"><a href="{{url('/admin/orders')}}"><i class="la la-home"></i><span class="menu-title" data-i18n="nav.dash.main">orders</span><span class="badge badge badge-info badge-pill float-right mr-2">{{App\Models\Order::count()}}</span></a>
          <ul class="menu-content">
            <li class="@if ($page == 'ordersShow') active @endif">
              <a class="menu-item" href="{{url('/admin/orders')}}" data-i18n="nav.dash.ecommerce">orders</a>
            </li>
          </ul>
        </li>

      </ul>
    </div>
  </div>