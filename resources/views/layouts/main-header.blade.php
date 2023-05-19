<!-- main-header opened -->
			<div class="main-header sticky side-header nav nav-item">
				<div class="container-fluid">
					<div class="main-header-left ">
						<div class="responsive-logo">
							<a href="{{ route('home') }}"><img src="{{asset('assets/img/brand/logo.png')}}" class="logo-1" alt="logo"></a>
							<a href="{{ route('home') }}"><img src="{{asset('assets/img/brand/favicon.png')}}" class="logo-2" alt="logo"></a>
						</div>
						<div class="app-sidebar__toggle" data-toggle="sidebar">
							<a class="open-toggle" href="#"><i class="header-icon fe fe-align-left" ></i></a>
							<a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
						</div>
					</div>
					<div class="main-header-right">
						<div class="nav nav-item  navbar-nav-right ml-auto">
							<div class="dropdown nav-item main-header-notification" onclick="markNotificationsRead()">
								<a class="new nav-link d-flex align-items-center justify-content-center" href="#">
								<svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs feather feather-bell" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class=" pulse" style="animation: none"></span>
                                    @endif
                                </a>
								<div class="dropdown-menu">
									<div class="menu-header-content bg-primary">
										<div class="d-flex">
											<h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">Notifications</h6>
										</div>
										<p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">
                                            You have {{auth()->user()->unreadNotifications->count()}} unread Notifications
                                        </p>
									</div>
									<div class="main-notification-list Notification-scroll overflow-y-auto">
                                        @foreach(auth()->user()->unreadNotifications as $notification)
										    <a class="d-flex align-items-center p-3 border-bottom bg-success-transparent" href="{{route('invoices.show', $notification->data['invoice_id'])}}">
											<div class="notifyimg bg-success">
												<i class="la la-check-circle text-white"></i>
											</div>
											<div class="ml-3">
												<h5 class="notification-label mb-1">New Paid Invoice</h5>
                                                <span class="tx-dark tx-12">Total: {{$notification->data['invoice_total']}}$</span>
												<div class="notification-subtext">{{\Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</div>
											</div>
											<div class="ml-auto" >
												<i class="las la-angle-right text-muted"></i>
											</div>
										</a>
                                        @endforeach
                                        @foreach(auth()->user()->notifications->where('read_at', '!=', null)->take(5) as $notification)
										    <a class="d-flex align-items-center p-3 border-bottom" href="{{route('invoices.show', $notification->data['invoice_id'])}}">
											<div class="notifyimg bg-success">
												<i class="la la-check-circle text-white"></i>
											</div>
											<div class="ml-3">
												<h5 class="notification-label mb-1">New Paid Invoice</h5>
                                                <span class="tx-dark tx-12">Total: {{$notification->data['invoice_total']}}$</span>
												<div class="notification-subtext">{{\Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</div>
											</div>
											<div class="ml-auto" >
												<i class="las la-angle-right text-muted"></i>
											</div>
										</a>
                                        @endforeach
									</div>
								</div>
							</div>
							<div class="dropdown main-profile-menu nav nav-item nav-link">
								<a class="profile-user d-flex" href=""><img alt="" src="{{asset('assets/img/faces/6.jpg')}}"></a>
								<div class="dropdown-menu">
									<div class="main-header-profile bg-primary p-3">
										<div class="d-flex wd-100p">
											<div class="main-img-user"><img alt="" src="{{asset('assets/img/faces/6.jpg')}}" class=""></div>
											<div class="ml-3 my-auto">
												<h6>{{auth()->user()->name}}</h6>
											</div>
										</div>
									</div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                            class="bx bx-log-out"></i>Signout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<!-- /main-header -->
<script>
    function markNotificationsRead(){
        fetch("{{route('notifications.markAsRead')}}");
    }
</script>
