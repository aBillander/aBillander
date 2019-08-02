{{-- back_to_top_button --}}
{{-- https://www.templatemonster.com/blog/back-to-top-button-css-jquery/
     https://codepen.io/matthewcain/pen/ZepbeR --}}

<!-- link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Merriweather:400,900,900i" rel="stylesheet" -->

<!-- Back to top button -->
<a id="button_to_top"></a>


@section('scripts')    @parent

<script type="text/javascript">

	var btn = $('#button_to_top');

	$(window).scroll(function() {
	  if ($(window).scrollTop() > 300) {
	    btn.addClass('show');
	  } else {
	    btn.removeClass('show');
	  }
	});

	btn.on('click', function(e) {
	  e.preventDefault();
	  $('html, body').animate({scrollTop:0}, '300');
	});

</script>

@endsection

@section('styles')    @parent

<style>

#button_to_top {
  display: inline-block;
  background-color: #FF9800;
  width: 50px;
  height: 50px;
  text-align: center;
  border-radius: 4px;
  position: fixed;
  bottom: 30px;
  right: 30px;
  transition: background-color .3s, 
    opacity .5s, visibility .5s;
  opacity: 0;
  visibility: hidden;
  z-index: 1000;
}
#button_to_top::after {
  content: "\f077";
  font-family: FontAwesome;
  font-weight: normal;
  font-style: normal;
  font-size: 2em;
  line-height: 50px;
  color: #fff;
}
#button_to_top:hover {
  cursor: pointer;
  background-color: #333;
}
#button_to_top:active {
  background-color: #555;
}
#button_to_top.show {
  opacity: 1;
  visibility: visible;
}

</style>

@endsection