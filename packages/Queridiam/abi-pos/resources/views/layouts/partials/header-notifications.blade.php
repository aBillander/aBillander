@php
  $all_notifications = auth()->user()->notifications;
  $unread_notifications = $all_notifications->where('read_at', null);
  $total_unread = count($unread_notifications);
@endphp
<!-- Notifications: style can be found in dropdown.less -->
<li class="dropdown notifications-menu">
  <a href="#" class="dropdown-toggle load_notifications" data-toggle="dropdown" id="show_unread_notifications" data-loaded="false">
    <i class="fas fa-bell"></i>
    <span class="label label-warning notifications_count">@if(!empty($total_unread)){{$total_unread}}@endif</span>
  </a>
  <ul class="dropdown-menu">
    <!-- <li class="header">You have 10 unread notifications</li> -->
    <li>
      <!-- inner menu: contains the actual data -->

      <ul class="menu" id="notifications_list">
      </ul>
    </li>
    
    @if(count($all_notifications) > 10)
      <li class="footer load_more_li">
        <a href="#" class="load_more_notifications">@lang('lang_v1.load_more')</a>
      </li>
    @endif
  </ul>
</li>

<input type="hidden" id="notification_page" value="1">