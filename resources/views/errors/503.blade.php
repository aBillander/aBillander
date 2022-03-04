<!DOCTYPE html>
<html>
	<head>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

		<style>

    @import url(http://fonts.googleapis.com/css?family=Roboto);
    *
    {
        font-family: 'Roboto' , sans-serif;
    }
    body
    {
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMC8yOS8xMiKqq3kAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzVxteM2AAABHklEQVRIib2Vyw6EIAxFW5idr///Qx9sfG3pLEyJ3tAwi5EmBqRo7vHawiEEERHS6x7MTMxMVv6+z3tPMUYSkfTM/R0fEaG2bbMv+Gc4nZzn+dN4HAcREa3r+hi3bcuu68jLskhVIlW073tWaYlQ9+F9IpqmSfq+fwskhdO/AwmUTJXrOuaRQNeRkOd5lq7rXmS5InmERKoER/QMvUAPlZDHcZRhGN4CSeGY+aHMqgcks5RrHv/eeh455x5KrMq2yHQdibDO6ncG/KZWL7M8xDyS1/MIO0NJqdULLS81X6/X6aR0nqBSJcPeZnlZrzN477NKURn2Nus8sjzmEII0TfMiyxUuxphVWjpJkbx0btUnshRihVv70Bv8ItXq6Asoi/ZiCbU6YgAAAABJRU5ErkJggg==);
    } 
    .error-template
    {
        padding: 40px 15px;
        text-align: center;
    }
    .error-actions
    {
        margin-top: 15px;
        margin-bottom: 15px;
    }
    .error-actions .btn
    {
        margin-right: 10px;
    }
    .message-box h1
    {
        color: #252932;
        font-size: 98px;
        font-weight: 700;
        line-height: 98px;
        text-shadow: rgba(61, 61, 61, 0.3) 1px 1px, rgba(61, 61, 61, 0.2) 2px 2px, rgba(61, 61, 61, 0.3) 3px 3px;
    }

		</style>
	</head>
	<body>
		<!-- div class="container">
			<div class="content">
				<div class="title">{{ l('Be right back.', 'layouts') }}</div>
				<div class="title">Estamos mejorando el sitio.</div>
			</div>
		</div -->

		<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="error-template">
                <h1>
                    :) Oops!</h1>
                <h2>
                    Temporarily down for maintenance <br /> Estamos mejorando el sitio</h2>
                <h1>
                    We’ll be back soon! <br /> En breve estamos de vuelta!</h1>
                <div>
                    <p>
                        Sorry for the inconvenience but we’re performing some maintenance at the moment.
                        we’ll be back online shortly!</p>
                    <p>
                        — aBillander Fembot Team</p>
                </div>

                <div class="">
                {!! HTML::image('assets/theme/images/fembots.jpg', 'Lara Billander', array('title' => 'aBillander Fembot Team', 'width' => '200', 'xheight' => '176', 'class' => 'center-block', 'style' => 'padding: 10px; -webkit-border-radius: 18px;')) !!}
                </div>

                <!-- div class="error-actions">
                    <a href="#" style="margin-top: 10px;" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-home">
                    </span>Take Me Home </a>
                </div -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="error-template">
        	{!! HTML::image('assets/theme/images/laravatar.png', 'Lara Billander', array('title' => 'Lara Billander :: The Girl with the Dragon Tattoo', 'width' => '150', 'xheight' => '176', 'class' => 'center-block', 'style' => 'padding: 10px; -webkit-border-radius: 18px;')) !!}
            </div>
            <!-- svg class="svg-box" width="380px" height="500px" viewbox="0 0 837 1045" version="1.1"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                        <path d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z" id="Polygon-1" stroke="#3bafda" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        <path d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z" id="Polygon-2" stroke="#7266ba" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        <path d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z" id="Polygon-3" stroke="#f76397" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        <path d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z" id="Polygon-4" stroke="#00b19d" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        <path d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z" id="Polygon-5" stroke="#ffaa00" stroke-width="6" sketch:type="MSShapeGroup"></path>
                    </g>
                </svg -->
        </div>
    </div>
</div>
	</body>
</html>
