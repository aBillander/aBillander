
<style>

/*
Usage:

<div id="cover-spin"></div>

<button onclick="$('#cover-spin').show(0)">Save</button>
Another Way to Use

You don't need to add the element into the page initially as shown in the beginning. And you can style it as {display:block;} instead of {display:none;}. You can dynamically add the element when you need it. For example:

<button onclick="$('#contentContainer').html('<div id=\'cover-spin\'></div>'); myAjaxSave();">Save</button>

*/


#cover-spin {
    position:fixed;
    width:100%;
    left:0;right:0;top:0;bottom:0;
    background-color: rgba(255,255,255,0.7);
    z-index:9999;
    display:none;
}

@-webkit-keyframes spin {
	from {-webkit-transform:rotate(0deg);}
	to {-webkit-transform:rotate(360deg);}
}

@keyframes spin {
	from {transform:rotate(0deg);}
	to {transform:rotate(360deg);}
}

#cover-spin::after {
    content:'';
    display:block;
    position:absolute;
    left:48%;top:40%;
    width:40px;height:40px;
    border-style:solid;
    border-color:grey;
    border-top-color:transparent;
    border-width: 4px;
    border-radius:50%;
    -webkit-animation: spin .8s linear infinite;
    animation: spin .8s linear infinite;
}

</style>

@section('scripts')     @parent

<script type="text/javascript">

	function fakeLoad()
	{
		$('#cover-spin').show(0);
	}

</script>

@endsection
