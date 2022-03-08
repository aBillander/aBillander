<ul>
    @foreach($childs as $child)
    <li>
        
        @if(count($child->children))
            {{ $child->name }} <span class="caret"></span>
            @include('manageChild',['childs' => $child->children])
        @else
        	<a href="http://localhost/enatural/public/pricelists" xclass="btn btn-warning">{{ $child->name }}</a>
        @endif
    </li>
    @endforeach
</ul>