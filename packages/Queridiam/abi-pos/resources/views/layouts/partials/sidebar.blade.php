<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

	<a href="{{route('home')}}" class="logo">
		<span class="logo-lg">{{ Session::get('business.name') }}</span>
	</a>

    <!-- Sidebar Menu -->

<ul class="sidebar-menu tree" data-widget="tree">
<li class="active"><a href="http://localhost/upos/public/home"><i class="fa fas fa-tachometer-alt"></i> <span>Home</span></a></li>

{{--
<li class="treeview">
                  <a href="#">
                    <i class="fa fas fa-users"></i> <span>User Management</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="http://localhost/upos/public/users"><i class="fa fas fa-user"></i> <span>Users</span></a></li>
<li><a href="http://localhost/upos/public/roles"><i class="fa fas fa-briefcase"></i> <span>Roles</span></a></li>
<li><a href="http://localhost/upos/public/sales-commission-agents"><i class="fa fas fa-handshake"></i> <span>Sales Commission Agents</span></a></li>

                  </ul>
                </li>
<li class="treeview" id="tour_step4">
                  <a href="#">
                    <i class="fa fas fa-address-book"></i> <span>Contacts</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="http://localhost/upos/public/contacts?type=supplier"><i class="fa fas fa-star"></i> <span>Suppliers</span></a></li>
<li><a href="http://localhost/upos/public/contacts?type=customer"><i class="fa fas fa-star"></i> <span>Customers</span></a></li>
<li><a href="http://localhost/upos/public/customer-group"><i class="fa fas fa-users"></i> <span>Customer Groups</span></a></li>
<li><a href="http://localhost/upos/public/contacts/import"><i class="fa fas fa-download"></i> <span>Import Contacts</span></a></li>

                  </ul>
                </li>
<li class="treeview" id="tour_step5">
                  <a href="#">
                    <i class="fa fas fa-cubes"></i> <span>Products</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="http://localhost/upos/public/products"><i class="fa fas fa-list"></i> <span>List Products</span></a></li>
<li><a href="http://localhost/upos/public/products/create"><i class="fa fas fa-plus-circle"></i> <span>Add Product</span></a></li>
<li><a href="http://localhost/upos/public/labels/show"><i class="fa fas fa-barcode"></i> <span>Print Labels</span></a></li>
<li><a href="http://localhost/upos/public/variation-templates"><i class="fa fas fa-circle"></i> <span>Variations</span></a></li>
<li><a href="http://localhost/upos/public/import-products"><i class="fa fas fa-download"></i> <span>Import Products</span></a></li>
<li><a href="http://localhost/upos/public/import-opening-stock"><i class="fa fas fa-download"></i> <span>Import Opening Stock</span></a></li>
<li><a href="http://localhost/upos/public/selling-price-group"><i class="fa fas fa-circle"></i> <span>Selling Price Group</span></a></li>
<li><a href="http://localhost/upos/public/units"><i class="fa fas fa-balance-scale"></i> <span>Units</span></a></li>
<li><a href="http://localhost/upos/public/taxonomies?type=product"><i class="fa fas fa-tags"></i> <span>Categories</span></a></li>
<li><a href="http://localhost/upos/public/brands"><i class="fa fas fa-gem"></i> <span>Brands</span></a></li>
<li><a href="http://localhost/upos/public/warranties"><i class="fa fas fa-shield-alt"></i> <span>Warranties</span></a></li>

                  </ul>
                </li>
<li class="treeview" id="tour_step6">
                  <a href="#">
                    <i class="fa fas fa-arrow-circle-down"></i> <span>Purchases</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="http://localhost/upos/public/purchases"><i class="fa fas fa-list"></i> <span>List Purchases</span></a></li>
<li><a href="http://localhost/upos/public/purchases/create"><i class="fa fas fa-plus-circle"></i> <span>Add Purchase</span></a></li>
<li><a href="http://localhost/upos/public/purchase-return"><i class="fa fas fa-undo"></i> <span>List Purchase Return</span></a></li>

                  </ul>
                </li>
--}}

<li class="treeview" id="tour_step7">
                  <a href="#">
                    <i class="fa fas fa-arrow-circle-up"></i> <span>Sell</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">

{{--
                    <li><a href="http://localhost/upos/public/sells"><i class="fa fas fa-list"></i> <span>All sales</span></a></li>
<li><a href="http://localhost/upos/public/sells/create"><i class="fa fas fa-plus-circle"></i> <span>Add Sale</span></a></li>
--}}

<li><a href="http://localhost/upos/public/pos"><i class="fa fas fa-list"></i> <span>List POS</span></a></li>
<li><a href="{{ route('pos::interface') }}"><i class="fa fas fa-plus-circle"></i> <span>POS</span></a></li>

{{--
<li><a href="http://localhost/upos/public/sells/create?status=draft"><i class="fa fas fa-plus-circle"></i> <span>Add Draft</span></a></li>
<li><a href="http://localhost/upos/public/sells/drafts"><i class="fa fas fa-pen-square"></i> <span>List Drafts</span></a></li>
<li><a href="http://localhost/upos/public/sells/create?status=quotation"><i class="fa fas fa-plus-circle"></i> <span>Add Quotation</span></a></li>
<li><a href="http://localhost/upos/public/sells/quotations"><i class="fa fas fa-pen-square"></i> <span>List quotations</span></a></li>
<li><a href="http://localhost/upos/public/sell-return"><i class="fa fas fa-undo"></i> <span>List Sell Return</span></a></li>
<li><a href="http://localhost/upos/public/shipments"><i class="fa fas fa-truck"></i> <span>Shipments</span></a></li>
<li><a href="http://localhost/upos/public/discount"><i class="fa fas fa-percent"></i> <span>Discounts</span></a></li>
<li><a href="http://localhost/upos/public/import-sales"><i class="fa fas fa-file-import"></i> <span>Import Sales</span></a></li>
--}}

                  </ul>
                </li>

{{--
<li class="treeview">
                  <a href="#">
                    <i class="fa fas fa-truck"></i> <span>Stock Transfers</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="http://localhost/upos/public/stock-transfers"><i class="fa fas fa-list"></i> <span>List Stock Transfers</span></a></li>
<li><a href="http://localhost/upos/public/stock-transfers/create"><i class="fa fas fa-plus-circle"></i> <span>Add Stock Transfer</span></a></li>

                  </ul>
                </li>
<li class="treeview">
                  <a href="#">
                    <i class="fa fas fa-database"></i> <span>Stock Adjustment</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="http://localhost/upos/public/stock-adjustments"><i class="fa fas fa-list"></i> <span>List Stock Adjustments</span></a></li>
<li><a href="http://localhost/upos/public/stock-adjustments/create"><i class="fa fas fa-plus-circle"></i> <span>Add Stock Adjustment</span></a></li>

                  </ul>
                </li>
<li class="treeview">
                  <a href="#">
                    <i class="fa fas fa-minus-circle"></i> <span>Expenses</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="http://localhost/upos/public/expenses"><i class="fa fas fa-list"></i> <span>List Expenses</span></a></li>
<li><a href="http://localhost/upos/public/expenses/create"><i class="fa fas fa-plus-circle"></i> <span>Add Expense</span></a></li>
<li><a href="http://localhost/upos/public/expense-categories"><i class="fa fas fa-circle"></i> <span>Expense Categories</span></a></li>

                  </ul>
                </li>
<li class="treeview" id="tour_step8">
                  <a href="#">
                    <i class="fa fas fa-chart-bar"></i> <span>Reports</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="http://localhost/upos/public/reports/profit-loss"><i class="fa fas fa-file-invoice-dollar"></i> <span>Profit / Loss Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/product-purchase-report"><i class="fa fas fa-arrow-circle-down"></i> <span>Product Purchase Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/sales-representative-report"><i class="fa fas fa-user"></i> <span>Sales Representative Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/register-report"><i class="fa fas fa-briefcase"></i> <span>Register Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/expense-report"><i class="fa fas fa-search-minus"></i> <span>Expense Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/sell-payment-report"><i class="fa fas fa-search-dollar"></i> <span>Sell Payment Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/purchase-payment-report"><i class="fa fas fa-search-dollar"></i> <span>Purchase Payment Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/product-sell-report"><i class="fa fas fa-arrow-circle-up"></i> <span>Product Sell Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/items-report"><i class="fa fas fa-tasks"></i> <span>Items Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/purchase-sell"><i class="fa fas fa-exchange-alt"></i> <span>Purchase &amp; Sale</span></a></li>
<li><a href="http://localhost/upos/public/reports/trending-products"><i class="fa fas fa-chart-line"></i> <span>Trending Products</span></a></li>
<li><a href="http://localhost/upos/public/reports/stock-adjustment-report"><i class="fa fas fa-sliders-h"></i> <span>Stock Adjustment Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/stock-report"><i class="fa fas fa-hourglass-half"></i> <span>Stock Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/customer-group"><i class="fa fas fa-users"></i> <span>Customer Groups Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/customer-supplier"><i class="fa fas fa-address-book"></i> <span>Supplier &amp; Customer Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/tax-report"><i class="fa fas fa-percent"></i> <span>Tax Report</span></a></li>
<li><a href="http://localhost/upos/public/reports/activity-log"><i class="fa fas fa-user-secret"></i> <span>Activity Log</span></a></li>

                  </ul>
                </li>
<li><a href="http://localhost/upos/public/notification-templates"><i class="fa fas fa-envelope"></i> <span>Notification Templates</span></a></li>
--}}

<li class="treeview" id="tour_step3">
                  <a href="#">
                    <i class="fa fas fa-cog"></i> <span>Settings</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="http://localhost/upos/public/business/settings" id="tour_step2"><i class="fa fas fa-cogs"></i> <span>Business Settings</span></a></li>
<li><a href="http://localhost/upos/public/business-location"><i class="fa fas fa-map-marker"></i> <span>Business Locations</span></a></li>
<li><a href="http://localhost/upos/public/invoice-schemes"><i class="fa fas fa-file"></i> <span>Invoice Settings</span></a></li>
<li><a href="http://localhost/upos/public/barcodes"><i class="fa fas fa-barcode"></i> <span>Barcode Settings</span></a></li>
<li><a href="http://localhost/upos/public/printers"><i class="fa fas fa-share-alt"></i> <span>Receipt Printers</span></a></li>
<li><a href="http://localhost/upos/public/tax-rates"><i class="fa fas fa-bolt"></i> <span>Tax Rates</span></a></li>

                  </ul>
                </li>

</ul>

    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>
