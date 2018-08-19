
<div class="modal fade" id="aboutLaraBillander" tabindex="-1" role="dialog" aria-labelledby="myLaraBillander" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myLaraBillander">{{l('About ...', [], 'layouts')}}</h4>
            </div>
            <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <h4><a class="footer-logo" href="http://www.abillander.com/" target="new"><i class="fa fa-bolt"></i> Lar<span>aBillander</span></a> <span style="font-size: 14px;">by Lara Billander</span></h4>
                    {!! HTML::image('assets/theme/images/laravatar.png', 'Lara Billander', array('title' => 'Lara Billander :: The Girl with the Dragon Tattoo', 'width' => '150', 'xheight' => '176', 'class' => 'center-block', 'style' => 'padding: 10px')) !!}
                <p>{{l('Version', [], 'layouts')}} {{ App\Configuration::get('SW_VERSION') }}</p>


              </div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{l('Continue', [], 'layouts')}}</button>

            </div>
        </div>
    </div>
</div>
